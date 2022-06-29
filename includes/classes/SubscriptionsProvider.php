<?php 

class SubscriptionsProvider {

    private $con, $userLoggedInObj;

    public function __construct($con, $userLoggedInObj) {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function getVideos() {
        $videos = [];
        $subscriptions = $this->userLoggedInObj->getSubscriptions();
        $size = count($subscriptions);

        if(sizeof($subscriptions) > 0) {

            $condition = "";
            $i = 0;

            while($i < sizeof($subscriptions)) {
                if($i == 0) {
                    $condition .= "WHERE uploadedBy=?";
                } else {
                    $condition .= " OR uploadedBy=?";
                }
                $i++;
            }

            $videoSql = "SELECT * FROM videos $condition ORDER BY uploadDate DESC";
            $query = $this->con->prepare($videoSql);

            $i = 1;

            foreach($subscriptions as $sub) {
                $subUsername = $sub->getUsername();
                $query->bindValue($i, $subUsername);
                $i++;
            }

            $query->execute();

            while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $video = new Video($this->con, $row, $this->userLoggedInObj);
                array_push($videos, $video);
            }
        }

        return $videos;
    }

}

?>