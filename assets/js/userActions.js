function subscribe(userTo, userFrom, button) {

    let formData = new FormData();

    if (userTo == userFrom) {
        alert("You can't subscribe to yourself");
        return;
    }

    formData.append('userTo', userTo);
    formData.append('userFrom', userFrom);
    fetch("ajax/subscribe.php", {
            method: 'POST',
            body: formData,
        })
        .then((res) => res.json())
        .then(body => {
            if (body.count != null) {
                let subscribeButton = button;
                if (body.count > 0) {
                    subscribeButton.classList = "unsubscribe button";
                    subscribeButton.textContent = "SUBSCRIBED " + body.count;
                } else {
                    subscribeButton.classList = "subscribe button";
                    subscribeButton.textContent = "SUBSCRIBE " + body.count;
                }

            } else {
                alert("something went wrong");
            }
        })

}