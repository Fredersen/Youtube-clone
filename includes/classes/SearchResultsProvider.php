<?php 

class SearchResultsProvider {

    private $con, $userLoggedInObj;

    public function __construct($con, $userLoggedInObj) {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function getVideos($query, $orderBy) {
        $pdo = $this->con->prepare("SELECT * FROM videos WHERE title LIKE CONCAT('%', ':query', '%')
                                        OR uploadedBy LIKE CONCAT('%', :query, '%') ORDER BY $orderBy DESC");
        $pdo->bindValue(':query', $query);
        $pdo->execute();
        $videos = [];
        while($row = $pdo->fetch(PDO::FETCH_ASSOC)) {
            $video = new Video($this->con, $row, $this->userLoggedInObj);
            array_push($videos, $video);
        }

        return $videos;
    }

}

?>