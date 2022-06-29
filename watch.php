<?php 
require_once("includes/header.php");
require_once("includes/classes/VideoPlayer.php");
require_once("includes/classes/VideoInfoSection.php");
require_once("includes/classes/CommentSection.php");
require_once("includes/classes/Comment.php");

if(!isset($_GET["id"])) {
    echo "Page not found";
    exit();
}

$video = New Video($con, $_GET['id'], $userLoggedInObj);
$video->incrementViews();
?>
<script src="assets/js/videoPlayerActions.js"></script>
<script src="assets/js/commentActions.js"></script>
<div class="watchLeftColumn">
<?php 
    $videoPlayer = new VideoPlayer($video);
    echo $videoPlayer->create(true);
    $videoPlayer = new VideoInfoSection($con, $video, $userLoggedInObj);
    echo $videoPlayer->create();
    $commentSection = new CommentSection($con, $video, $userLoggedInObj);
    echo $commentSection->create();
?>
</div>
<div class="suggestions">
    <?php
        $videoGrid = new VideoGrid($con, $userLoggedInObj);
        echo $videoGrid->create(null, "Watch Later", false);
    ?>
</div>
<?php require_once("includes/footer.php"); ?>