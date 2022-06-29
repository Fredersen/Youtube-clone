$(document).ready(function() {
    
    $(".navShowHide").on("click", function() {
        let main = $("#mainSectionContainer");
        let nav = $("#sideNavContainer");

        if(main.hasClass("leftPadding")) {
            nav.hide();
        } else {
            nav.show();
        }

        main.toggleClass("leftPadding");
    })

})

function notSignedIn() {
    alert("You must be signed in to perform this action");
}

const mediaQuery = window.matchMedia("(max-width: 768px)");

if (mediaQuery.matches) {
    $(document).ready(function() {
        let logo = $("#youtubeLogo");
        logo.attr("src", "assets/images/icons/youtube-small.png");
    })} else {
        $(document).ready(function() {
            let logo = $("#youtubeLogo");
            logo.attr("src", "assets/images/icons/youtube-logo.png");
        })
        
    }

function changeLogo() {
    if (mediaQuery.matches) {
    $(document).ready(function() {
        let logo = $("#youtubeLogo");
        logo.attr("src", "assets/images/icons/youtube-small.png");
    })} else {
        $(document).ready(function() {
            let logo = $("#youtubeLogo");
            logo.attr("src", "assets/images/icons/youtube-logo.png");
        })
    
    }
}

window.addEventListener('resize', changeLogo);