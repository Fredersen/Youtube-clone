function postComment(button, postedBy, videoId, replyTo, containerClass) {
    let textarea = button.previousElementSibling;
    let testTextArea = button.previousElementSibling;

    while (testTextArea) {
        if (testTextArea.tagName === 'TEXTAREA') {
            textarea = testTextArea;
            break;
        }
        testTextArea = testTextArea.previousElementSibling;
    }

    let commentText = textarea.value;
    textarea.value = "";

    if (commentText) {
        let formData = new FormData();
        formData.append('commentText', commentText);
        formData.append('postedBy', postedBy);
        formData.append('videoId', videoId);
        formData.append('responseTo', replyTo);
        fetch("ajax/postComment.php", {
                method: 'POST',
                body: formData,
            })
            .then((res) => res.json())
            .then(body => {
                if(containerClass === 'repliesSection') {
                    button.parentNode.nextElementSibling.innerHTML += body.comment;
                    button.parentNode.classList.toggle('hidden');
                } else {
                    document.getElementById(containerClass).innerHTML = body.comment + document.getElementById(containerClass).innerHTML;
                }  
            })

    } else {
        alert("You can't post an empty comment")
    }

}

function toggleReply(button) {
    let parent = button.closest('.itemContainer');
    let commentForm = parent.getElementsByClassName("commentForm")[0];
    commentForm.classList.toggle('hidden');
}

function likeComment(videoId, button, commentId) {
    
    let formData = new FormData();

    formData.append('videoId', videoId);
    formData.append('commentId', commentId);
    fetch("ajax/likeComment.php", {
    method: 'POST',
    body: formData,
    })
    .then((res) => res.json())
    .then(body => {
        let likeButton = button;
        let dislikeButton = likeButton.nextElementSibling;
        likeButton.classList.add("active");
        dislikeButton.classList.remove("active");

        let likesCount;
        let testClassLikeCount = likeButton.previousElementSibling;

        while (testClassLikeCount) {
            if (testClassLikeCount.classList.contains('likesCount')) {
                likesCount = testClassLikeCount;
                break;
            }
            testClassLikeCount = testClassLikeCount.previousElementSibling;
        }

        updateLikesValue(likesCount, body);

        if(body < 0) {
            likeButton.classList.remove("active");
            likeButton.querySelector("img").src = "assets/images/icons/thumb-up.png";
        } else {
            likeButton.querySelector("img").src = "assets/images/icons/thumb-up-active.png";
        }

        dislikeButton.querySelector("img").src = "assets/images/icons/thumb-down.png";
    })
}

function dislikeComment(videoId, button, commentId) {
    let formData = new FormData();

    formData.append('videoId', videoId);
    formData.append('commentId', commentId);
    fetch("ajax/dislikeComment.php", {
    method: 'POST',
    body: formData,
    })
    .then((res) => res.json())
    .then(body => {
        let dislikeButton = button;
        let likeButton = dislikeButton.previousElementSibling;
        dislikeButton.classList.add("active");
        likeButton.classList.remove("active");

        let likesCount;
        let testClassLikeCount = likeButton.previousElementSibling;

        while (testClassLikeCount) {
            if (testClassLikeCount.classList.contains('likesCount')) {
                likesCount = testClassLikeCount;
                break;
            }
            testClassLikeCount = testClassLikeCount.previousElementSibling;
        }

        updateLikesValue(likesCount, body);

        if(body > 0) {
            dislikeButton.classList.remove("active");
            dislikeButton.querySelector("img").src = "assets/images/icons/thumb-down.png";
        } else {
            dislikeButton.querySelector("img").src = "assets/images/icons/thumb-down-active.png";
        }

        likeButton.querySelector("img").src = "assets/images/icons/thumb-up.png";
    })
}

function updateLikesValue(element, num) {
    let likesCountVal = element.textContent || 0;
    element.textContent = parseInt(likesCountVal) + parseInt(num);
}

function getReplies(commentId, button, videoId) {
    let formData = new FormData();

    formData.append('videoId', videoId);
    formData.append('commentId', commentId);
    fetch("ajax/getCommentReplies.php", {
    method: 'POST',
    body: formData,
    })
    .then((res) => res.json())
    .then(body => {
        let replies = document.createElement('div');
        replies.classList.add("repliesSection");
        replies.innerHTML = body;

        button.parentNode.replaceChild(replies, button)
    })
}