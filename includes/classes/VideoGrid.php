<?php 

class VideoGrid {

    private $con, $userLoggedInObj;
    private $largeMode = false;
    private $gridClass = 'videoGrid';

    public function __construct($con, $userLoggedInObj) {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function create($videos, $title, $showFilter) {

        if($videos == null) {
            $gridItems = $this->generateItems();
        } else {
            $gridItems = $this->generateItemsFromVideos($videos);
        }

        $header = "";

        if($title != null) {
            $header = $this->createGridHeader($title, $showFilter);
        }

        return "$header
                <div class='$this->gridClass'>
                    $gridItems
                </div>";
    }

    public function generateItems() {
        $query = $this->con->query("SELECT * FROM videos ORDER BY RAND() LIMIT 15");

        $elementsHtml = "";

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $video = new Video($this->con, $row, $this->userLoggedInObj);
            $item = new VideoGridItem($video, $this->largeMode);
            $elementsHtml .= $item->create();
        }

        return $elementsHtml;
    }

    public function generateItemsFromVideos($videos) {
        $elementsHtml = "";

        foreach($videos as $video) {
            $item = new VideoGridItem($video, $this->largeMode);
            $elementsHtml .= $item->create();
        }

        return $elementsHtml;
    }

    public function createGridHeader($title, $showFilter) {
        $filter = "";

        if($showFilter) {
            $actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            $urlArray = parse_url($actual_link);
            $query = $urlArray["query"];
            parse_str($query, $params);

            unset($params["orderBy"]);

            $newQuery = http_build_query($params);
            $newUrl = basename($_SERVER['PHP_SELF']) . "?" . $newQuery;

            if($_GET['orderBy'] == 'views') {
                $viewsActive = "class='active'";
            } else {
                $uploadedByActive = "class='active'";
            }
            $filter = "<div class='right'>
                            <span>Order By</span>
                            <a href='$newUrl&orderBy=uploadDate' $uploadedByActive>Upload date</a>
                            <a href='$newUrl&orderBy=views' $viewsActive>Most viewed</a>
                        </div>";
        }

        return "<div class='videoGridHeader'>
                    <div class='left'>
                        $title
                    </div>
                    $filter
                </div>";
    }

    public function createLarge($videos, $title, $showFilter) {
        $this->gridClass .= " large";
        $this->largeMode = true;
        return $this->create($videos, $title, $showFilter);
    }
}

?>