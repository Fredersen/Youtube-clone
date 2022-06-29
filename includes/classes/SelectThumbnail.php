<?php

class SelectThumbnail {

    private $con, $video;

    public function __construct($con, $video) {
        $this->con = $con;
        $this->video = $video;
    }

    public function create() {
        $thumbnailData = $this->getThumbnailsData();

        $html = "";

        foreach($thumbnailData as $data) {
            $html .= $this->createThumbnailItem($data);
        }

        return "<div class='thumbnailItemsContainer'>
                    $html
                </div>";
    }

    private function getThumbnailsData() {
        $data = [];
        $id = $this->video->getId();

        $query = $this->con->prepare("SELECT * FROM thumbnails WHERE videoId=:videoId");
        $query->bindValue(":videoId", $id);
        $query->execute();

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return $data;
    }

    private function createThumbnailItem($data) {
        $id = $data["id"];
        $filePath = $data["filePath"];
        $videoId = $data["videoId"];
        $selected = $data["selected"] == 1 ? "selected" : "";

        return "<div class='thumbnailItem $selected' onclick='setNewThumbnail($id, $videoId, this)'>
                    <img src='$filePath'>
                </div>";
    }

}

?>