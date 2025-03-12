/*
 * main.js
 * 
 * INF1005 - Web Systems & Technologies
 * Copyright (c) 2023 Singapore Institute of Technology
 * 
 */

/*
 * Add event listener to the document object to fire when
 * the document is fully loaded. From here, execute any
 * code that should run when the DOM is ready, such as
 * adding additional event listners, etc.
 * 
 * Alternatively, if using jQuery, you can take advantage of the
 * $(document).ready() method.
 */
document.addEventListener("DOMContentLoaded", function (){

    registerEventListeners();
    activateMenu();

});


function registerEventListeners(){

    var photos = document.getElementsByClassName("img-thumbnail");

    if (photos !== null && photos.length > 0)
    {

        for (var i = 0; i < photos.length; i++){
            var photo = photos[i];
            photo.addEventListener("click", togglePhoto);
        }
    }
    else
    {
        console.log("No images found.");
    }

}

function togglePhoto(event){
    var photoName = event.target.alt;
    var imgElement = document.querySelector(`main[class="container"]`)
    checkPopUP = document.getElementsByClassName('img-popup');

    if(checkPopUP != null && checkPopUP.length != 0){
        while(checkPopUP.length > 0){
            checkPopUP[0].parentNode.removeChild(checkPopUP[0])
        }
        document.body.style.overflow = 'auto';
    }
    else{
        var newSpan = document.createElement("span");
        newSpan.className = "img-popup";
        newSpan.alt = photoName;
        newSpan.id = photoName;
        
        newSpan.innerHTML =`<img class='img-popup' src='images/${photoName.toLowerCase()}_large.jpg' alt=${photoName}></img>`;
        newSpan.addEventListener('click', togglePhoto);
        imgElement.insertAdjacentElement('afterbegin', newSpan);
        document.body.style.overflow = 'hidden';
    }


}

function activateMenu(){
    let currentPage = window.location.pathname.split("/").pop();
    if(currentPage.length == 0){
        currentPage = '/';
    }
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link =>
    {
        console.log(currentPage);
        if (link.getAttribute("href") === currentPage){
            link.classList.add('active');
        }
    })
}
