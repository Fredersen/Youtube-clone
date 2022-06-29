function setNewThumbnail(thumbnnailId, videoId, itemElement) {
    let formData = new FormData();

    formData.append('videoId', videoId);
    formData.append('thumbnailId', thumbnnailId);
    fetch("ajax/updateThumbnail.php", {
    method: 'POST',
    body: formData,
    })
    .then((res) => res.json())
    .then(() => {
        itemElement.parentNode.getElementsByClassName("selected")[0].classList.toggle("selected");
        itemElement.classList.toggle("selected"); 
    })
}