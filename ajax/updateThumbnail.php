<?php
require_once("../includes/config.php");

if(isset($_POST['thumbnailId']) && isset($_POST['videoId'])) {
 
    $videoId = $_POST['videoId'];
    $thumbnailId = $_POST['thumbnailId'];

    $query = $con->prepare("UPDATE thumbnails SET selected=0 WHERE videoId=:videoId");
    $query->bindValue(":videoId", $videoId); 
    $query->execute();

    $query = $con->prepare("UPDATE thumbnails SET selected=1 WHERE id=:thumbnailId");
    $query->bindValue(":thumbnailId", $thumbnailId); 
    $query->execute();

    echo json_encode(["message" => "success"]);

} else {
    echo "The thumbnail has not been updated";
}

?>
