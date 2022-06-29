<?php

class ProfileData {

    private $con, $profileUserObj;

    public function __construct($con, $profileUsername) {
        $this->con = $con;
        $this->profileUserObj = new User($con, $profileUsername);
    }

    public function getProfileUserObj() {
        return$this->profileUserObj;
    }

    public function getProfileUsername() {
        return $this->profileUserObj->getUsername();
    }

    public function userExists() {
        $profileUsername = $this->getProfileUsername();
        $query = $this->con->prepare("SELECT * FROM users WHERE username=:username");
        $query->bindValue(":username", $profileUsername);
        $query->execute();

        return $query->rowCount() != 0;
    }

    public function getCoverPhoto() {
        return "assets/images/coverPhotos/default-cover-photo.jpg";
    }

    public function getProfileFullName() {
        return $this->profileUserObj->getFullName();
    }

    public function getProfilePic() {
        return $this->profileUserObj->getProfilePic();
    }

    public function getSubsscriberCount() {
        return $this->profileUserObj->getSubscriberCount();
    }

    public function getUsersVideos() {
        $profileUsername = $this->getProfileUsername();
        $query = $this->con->prepare("SELECT * FROM videos WHERE uploadedBy=:username ORDER BY uploadDate DESC");
        $query->bindValue(":username", $profileUsername);
        $query->execute();

        $videos = [];

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $videos[] = new Video($this->con, $row, $profileUsername);
        }

        return $videos;
    }

    public function getAllUserDetails() {
        return [
            "Name" => $this->getProfileFullName(),
            "Username" => $this->getProfileUsername(),
            "Subscribers" => $this->getSubsscriberCount(),
            "Total Views" => $this->getTotalViews(),
            "Sign up Date" => $this->getSignUpDate(),
        ];
    }

    private function getTotalViews() {
        $profileUsername = $this->getProfileUsername();
        $query = $this->con->prepare("SELECT sum(views) FROM videos WHERE uploadedBy=:username");
        $query->bindValue(":username", $profileUsername);
        $query->execute();

        return $query->fetchColumn();
    }

    private function getSignUpDate() {
        $date = $this->profileUserObj->getSignUpDate();

        return date("F j, Y", strtotime($date));
    }

}

?>