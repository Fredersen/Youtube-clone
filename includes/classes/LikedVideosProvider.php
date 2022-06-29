<?php 

class LikedVideosProvider {

    private $con, $userLoggedInObj;

    public function __construct($con, $userLoggedInObj) {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function getVideos() {
        $videos = [];

        $query = $this->con->prepare("SELECT videoId FROM likes WHERE username=:username AND commentId IS NULL ORDER BY id DESC");
        $username = $this->userLoggedInObj->getUsername();
        $query->bindValue(":username", $username);
        $query->execute();

        while($row = $query->fetch(PDO::FETCH_ASSOC))  {
            $videos[] = new Video($this->con, $row["videoId"], $this->userLoggedInObj);
        }

        return $videos;
    }
}

?>