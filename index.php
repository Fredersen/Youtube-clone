<?php require_once("includes/header.php");?>

<div class="videoSection">
    <?php
        $subscriptionsProvider = new SubscriptionsProvider($con, $userLoggedInObj);
        $subscriptionsVideos = $subscriptionsProvider->getVideos();

        $videoGrid = new VideoGrid($con, $userLoggedInObj->getUsername());
    
        if(User::isLoggedIn() && sizeof($subscriptionsVideos) > 0) {
            echo $videoGrid->create($subscriptionsVideos, "Subscriptions", false);
        }

        echo $videoGrid->create(null, "Recommended", false);
    ?>
</div>

<?php require_once("includes/footer.php"); ?>