<?php

require 'vendor/autoload.php';

use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;
use FFMpeg\Coordinate\TimeCode;

class VideoProcessor {

    private $con;
    private $sizeLimit = 50000000;
    private $allowedTypes = array("mp4", "flv", "avi", "mpg", "wmv", "mov");
    private $ffmpegPath = "ffmpeg/linux/ffmpeg";

    public function __construct($con) {
        $this->con = $con;
    }

    public function upload($videoUploadData) {
        $targetDir = "upload/videos/";
        $videoData = $videoUploadData->getVideoDataArray();

        $tempFilePath = $targetDir . uniqid() . basename($videoData["name"]);
        $tempFilePath = str_replace(" ", "_", $tempFilePath);

        $isValidData = $this->processData($videoData, $tempFilePath);

        if(!$isValidData) {
            return false;
        }

        if(move_uploaded_file($videoData["tmp_name"], $tempFilePath)) {
            $finalFilePath = $targetDir . uniqid() . ".mp4";

            if(!$this->insertVideoData($videoUploadData, $finalFilePath)) {
                echo "Insert query failed !";
                return false;
            }

            $this->convertVideoToMp4($tempFilePath, $finalFilePath);
            $this->deleteFile($tempFilePath);

            if(!$this->generateThumbnails($finalFilePath)) {
                echo "Upload failed - could not generate thumbnails\n";
                return false;
            }

            return true;
        }
    }

    private function processData($videoData, $filePath) {
        $videoType = pathInfo($filePath, PATHINFO_EXTENSION);

        if(!$this->isValidSize($videoData)) {
            echo "File too large. Can't be more than " . $this->sizeLimit . " bytes";
            return false;
        } else if(!$this->isValidType($videoType)) {
            $errorMessage = "Invalid file type ! Only these types are allowed: ";
            foreach($this->allowedTypes as $type) {
                $errorMessage .= $type . " | ";
            }
           echo $errorMessage;
           return false;
        } else if($this->hasError($videoData)) {
            echo "Error code: " . $videoData["error"];
            return false;
        }

        return true;
    }

    private function isValidSize($data) {
        return $data["size"] <= $this->sizeLimit;
    }

    private function isValidType($type) {
        $lowercased =strtolower($type);
        return in_array($lowercased, $this->allowedTypes);
    }

    private function hasError($data) {
        return $data["error"] != 0;
    }

    private function insertVideoData($uploadData, $file) {
        $query = $this->con->prepare("INSERT INTO videos(title, filePath, uploadedBy, description, privacy, categoryId) VALUES(:title, :filePath, :uploadedBy, :description, :privacy, :categoryId)");
        $query->bindValue(":title", $uploadData->getTitle());
        $query->bindValue(":uploadedBy", $uploadData->getUploadedBy());
        $query->bindValue(":description", $uploadData->getDescription());
        $query->bindValue(":privacy", $uploadData->getPrivacy());
        $query->bindValue(":categoryId", $uploadData->getCategory());
        $query->bindValue(":filePath", $file);

        return $query->execute();
    }

    public function convertVideoToMp4($tempFilePath, $finalFilePath) {
        $ffmpeg = FFMpeg::create();
        $video = $ffmpeg->open($tempFilePath);

        $mp4Format = new X264();
        $mp4Format->setAudioCodec("libmp3lame");
        $video->save($mp4Format, $finalFilePath);
    }

    private function deleteFile($filePath) {
        if(!unlink($filePath)){
            echo "Could not delete file\n";
            return false;
        } 

        return true;
    }

    public function generateThumbnails($filePath) {
        $thumbnailSize = "210x118";
        $numThumbnails = 3;
        $pathToThumbnail = "upload/videos/thumbnails";
        $ffmpeg = FFMpeg::create();

        $duration = $this->getVideoDuration($filePath);
        
        $videoId = $this->con->lastInsertId();
        $this->updateDuration($duration, $videoId);

        for($i = 1; $i <= $numThumbnails; $i++) {
            $imageName = uniqid() . ".jpg";
            $interval = ($duration * 0.8) / $numThumbnails * $i;
            $fullThumbnailPath = "$pathToThumbnail/$videoId-$imageName";

            $video = $ffmpeg->open($filePath);
            $video
                ->frame(TimeCode::fromSeconds($interval))
                ->save($fullThumbnailPath);

            $selected = $i == 1 ? 1 : 0;    

            $query = $this->con->prepare("INSERT INTO thumbnails(videoId, filePath, selected) VALUES(:videoId, :filePath, :selected)");
            $query->bindValue(":videoId", $videoId);
            $query->bindValue(":filePath", $fullThumbnailPath);
            $query->bindValue(":selected", $selected);

            $success = $query->execute();

            if(!$success) {
                echo "Error inserting thumbnails\n";
                return false;
            }
        }

        return true;

    }

    private function getVideoDuration($filePath) {
        $ffmpeg = FFMpeg::create();

        $duration = $ffmpeg->getFFProbe()
            ->format($filePath)
            ->get('duration');

        return $duration;
    }

    private function updateDuration($duration, $videoId) {
    
        $duration = intval($duration);

        $hours = floor($duration / 3600);
        $mins = floor(($duration - ($hours*3600)) / 60);
        $secs = $duration - $minx *60;

        $hours = ($hours < 1) ? "" : $hours . ":";
        $mins = ($mins < 10) ? "0" . $mins . ":" : $mins . ":";
        $secs = ($secs < 10) ? "0" . $secs . ":" : $secs;
        
        $duration = strval($hours . $mins . $secs);

        $query = $this->con->prepare("UPDATE videos SET duration=:duration WHERE id=:id");
        $query->bindValue(":duration", $duration);
        $query->bindValue(":id", $videoId);
        $query->execute();
    }

}

?>
