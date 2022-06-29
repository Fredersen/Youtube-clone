<?php
require_once("../includes/config.php");
require_once("../includes/classes/User.php");
require_once("../includes/classes/Comment.php");

if(isset($_POST['commentText']) && isset($_POST['postedBy']) && isset($_POST['videoId'])) {
    $postedBy = $_POST['postedBy'];
    $videoId = $_POST['videoId'];
    $responseTo = $_POST['responseTo'] == "null" ? 0 : $_POST['responseTo'];
    $body = $_POST['commentText'];

    $query = $con->prepare("INSERT INTO comments(postedBy, videoId, responseTo, body) 
    VALUES(:postedBy, :videoId, :responseTo, :body)");
    $query->bindValue(":postedBy", $postedBy);
    $query->bindValue(":videoId", $videoId); 
    $query->bindValue(":responseTo", $responseTo); 
    $query->bindValue(":body", $body); 

    $query->execute();

    $commentId = $con->lastInsertId();

    $userLoggedInObj = new User($con, $_SESSION['userLoggedIn']);
    $newCommment = new Comment($con, $commentId, $userLoggedInObj, $videoId);

    echo json_encode(["comment" => $newCommment->create()]);

} else {
    echo "The comment has not been inserted";
}

?>