<?php

class VideoUploadData {

    private $videoDataArray, $title, $description, $privacy, $category, $uploadedBy;

    public function __construct($videoDataArray, $title, $description, $privacy, $category, $uploadedBy) {
        $this->videoDataArray = $videoDataArray;
        $this->title = $title;
        $this->description = $description;
        $this->privacy = $privacy;
        $this->category = $category;
        $this->uploadedBy = $uploadedBy;
    }

    public function updateDetails($con, $videoId) {
        $query = $con->prepare("UPDATE videos SET title=:title, description=:description, privacy=:privacy, categoryId=:category WHERE id=:videoId");
        $query->bindValue(":title", $this->getTitle());
        $query->bindValue(":videoId", $videoId);
        $query->bindValue(":description", $this->getDescription());
        $query->bindValue(":privacy", $this->getPrivacy());
        $query->bindValue(":category", $this->getCategory());
        return $query->execute(); //Return true if the execution is successful
    }

    /**
     * @return mixed
     */
    public function getVideoDataArray()
    {
        return $this->videoDataArray;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getPrivacy()
    {
        return $this->privacy;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return mixed
     */
    public function getUploadedBy()
    {
        return $this->uploadedBy;
    }
}

?>
