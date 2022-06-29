<?php
require_once("../includes/config.php");

if(isset($_POST['userTo']) && isset($_POST['userFrom'])) {

    $userTo = $_POST['userTo'];
    $userFrom = $_POST['userFrom'];

    $query = $con->prepare("SELECT * FROM subscribers WHERE userTo=:userTo AND userFrom=:userFrom");
    $query->bindValue(":userTo", $userTo);
    $query->bindValue(":userFrom", $userFrom);
    $query->execute();

    if($query->rowCount() == 0) {
        $query = $con->prepare("INSERT INTO subscribers(userTo, userFrom) VALUES(:userTo, :userFrom)");
        $query->bindValue(":userTo", $userTo);
        $query->bindValue(":userFrom", $userFrom); 
        $query->execute();
    } else {
        //delete
        $query = $con->prepare("DELETE FROM subscribers WHERE userTo=:userTo AND userFrom=:userFrom");
        $query->bindValue(":userTo", $userTo);
        $query->bindValue(":userFrom", $userFrom); 
        $query->execute();
    }

    $query = $con->prepare("SELECT * FROM subscribers WHERE userTo=:userTo");
    $query->bindValue(":userTo", $userTo);
    $query->execute();

    echo json_encode(["count" => $query->rowCount()]);
}

?>