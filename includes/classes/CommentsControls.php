<?php
require_once("ButtonProvider.php");

class CommentsControls {

    private $con, $comment, $userLoggedInObj;

    public function __construct($con, $comment, $userLoggedInObj) {
        $this->con = $con;
        $this->comment = $comment;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function create() {

        $replyButton = $this->createReplyButton();
        $likesCount = $this->createLikescount();
        $likeButton = $this->createLikeButton();
        $dislikeButton = $this->createDislikeButton();
        $replySection = $this->createReplySection();
       
        return "<div class='controls'>
                    $replyButton
                    $likesCount
                    $likeButton
                    $dislikeButton
                </div>
                $replySection
        ";
    }

    private function createReplyButton() {
        $text = "REPLY";
        $action = "toggleReply(this)";
        $button = "";

        if(!$this->comment->isResponse()) {
        $button = ButtonProvider::createButton($text, null, $action, null);
        }

        return $button;
    }

    private function createLikescount() {
        $text = $this->comment->getLikes();

        if($text == 0) $text = "";
        return "<span class='likesCount'>$text</span>";
    }

    private function createReplySection() {
        $postedBy = $this->userLoggedInObj->getUsername();
        $videoId = $this->comment->getVideoId();
        $commentId = $this->comment->getId();

        $profileButton = ButtonProvider::createUserProfileButton($this->con, $postedBy);

        $cancelButtonAction = "toggleReply(this)";
        $cancelButton = ButtonProvider::createButton("Cancel", null, $cancelButtonAction, "cancelComment");

        // $postButtonAction = "postComment(this, \"$postedBy\", $ivdeoId, $commentId, \"repliesSection\")";
        $postButtonAction = "postComment(this, \"$postedBy\", $videoId, $commentId, \"repliesSection\")";
        $postButton = ButtonProvider::createButton("Reply", null, $postButtonAction, "postComment");

        return "<div class='commentForm hidden'>
                    $profileButton
                    <textarea class='commentBodyClass' placeholder='Add a public comment'></textarea>
                    $cancelButton
                    $postButton
                </div>";
    }

    private function createLikeButton() {
        $videoId = $this->comment->getId();
        $commentId = $this->comment->getVideoId();
        $action = "likeComment($commentId, this, $videoId)";
        $class = "likeButton";
        $imageSrc = "assets/images/icons/thumb-up.png";

        if($this->comment->wasLikedBy()) {
            $imageSrc = "assets/images/icons/thumb-up-active.png";
        }

        return ButtonProvider::createButton("", $imageSrc, $action, $class);
    }

    private function createDislikeButton() {
        $videoId = $this->comment->getId();
        $commentId = $this->comment->getVideoId();
        $action = "dislikeComment($commentId, this, $videoId)";
        $class = "dislikeButton";
        $imageSrc = "assets/images/icons/thumb-down.png";

        if($this->comment->wasDislikedBy()) {
            $imageSrc = "assets/images/icons/thumb-down-active.png";
        }

        return ButtonProvider::createButton("", $imageSrc, $action, $class);
    }

}

?>