function likeVideo(button, videoId) {

    let formData = new FormData();

    formData.append('videoId', videoId);
    fetch("ajax/likeVideo.php", {
    method: 'POST',
    body: formData,
    })
    .then((res) => res.json())
    .then(body => {
        let likeButton = button;
        let dislikeButton = likeButton.nextElementSibling;
        likeButton.classList.add("active");
        dislikeButton.classList.remove("active");

        updateLikesValue(likeButton.querySelector(".text"), body.likes);
        updateLikesValue(dislikeButton.querySelector(".text"), body.dislikes);

        if(body.likes < 0) {
            likeButton.classList.remove("active");
            likeButton.querySelector("img").src = "assets/images/icons/thumb-up.png";
        } else {
            likeButton.querySelector("img").src = "assets/images/icons/thumb-up-active.png";
        }

        dislikeButton.querySelector("img").src = "assets/images/icons/thumb-down.png";
    })
}

function dislikeVideo(button, videoId) {

    let formData = new FormData();

    formData.append('videoId', videoId);
    fetch("ajax/dislikeVideo.php", {
    method: 'POST',
    body: formData,
    })
    .then((res) => res.json())
    .then(body => {
        let dislikeButton = button;
        let likeButton = dislikeButton.previousElementSibling;
      
        dislikeButton.classList.add("active");
        likeButton.classList.remove("active");

        updateLikesValue(likeButton.querySelector(".text"), body.likes);
        updateLikesValue(dislikeButton.querySelector(".text"), body.dislikes);

        if(body.dislikes < 0) {
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