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
document.addEventListener("DOMContentLoaded", function () {

    registerEventListeners();
    activateMenu();

    let itemsPerSlide = 3; // Default for large screens

    function updateCarouselItems() {
        let screenWidth = window.innerWidth;

        if (screenWidth < 768) {
            itemsPerSlide = 1; // Mobile: 1 per slide
        } else if (screenWidth < 992) {
            itemsPerSlide = 2; // Tablet: 2 per slide
        } else {
            itemsPerSlide = 3; // Desktop: 3 per slide
        }

        let items = document.querySelectorAll("#eventsCarousel .carousel-item");
        let eventCards = document.querySelectorAll("#eventsCarousel .col-md-4");

        // Reset the carousel
        document.querySelector("#eventsCarousel .carousel-inner").innerHTML = "";

        for (let i = 0; i < eventCards.length; i += itemsPerSlide) {
            let slide = document.createElement("div");
            slide.classList.add("carousel-item");
            if (i === 0) slide.classList.add("active");

            let row = document.createElement("div");
            row.classList.add("row", "justify-content-center");

            for (let j = i; j < i + itemsPerSlide && j < eventCards.length; j++) {
                row.appendChild(eventCards[j].cloneNode(true));
            }

            slide.appendChild(row);
            document.querySelector("#eventsCarousel .carousel-inner").appendChild(slide);
        }
    }

    updateCarouselItems(); // Run on page load
    window.addEventListener("resize", updateCarouselItems); // Update on window resize
});


// Function to register event listeners
function registerEventListeners() {
    // Get all elements with the 'img-thumbnail' class
    const thumbnails = document.getElementsByClassName("img-thumbnail");

    // Loop through each thumbnail and attach a click event listener
    for (let i = 0; i < thumbnails.length; i++) {
        thumbnails[i].addEventListener("click", function () {
            // Check if an existing popup exists and remove it
            const existingPopup = document.querySelector(".img-popup");
            if (existingPopup) {
                existingPopup.remove();
                return; // Exit if we're closing the existing popup
            }

            // Dynamically create the popup container
            const popup = document.createElement("div");
            popup.setAttribute("class", "img-popup"); // Add the 'img-popup' class

            // Dynamically create the image element
            const img = document.createElement("img");
            img.setAttribute("src", this.src); // Set the image source to the clicked thumbnail's source
            img.setAttribute("alt", "Popup Image"); // Add an alt attribute for accessibility

            // Add the image to the popup container
            popup.appendChild(img);

            // Insert the popup element into the DOM
            document.body.appendChild(popup);

            // Close the popup when it is clicked
            popup.addEventListener("click", function () {
                popup.remove();
            });
        });
    }
}

function togglePhoto(event) {
    var photoName = event.target.alt;
    var imgElement = document.querySelector(`main[class="container"]`)
    checkPopUP = document.getElementsByClassName('img-popup');

    if (checkPopUP != null && checkPopUP.length != 0) {
        while (checkPopUP.length > 0) {
            checkPopUP[0].parentNode.removeChild(checkPopUP[0])
        }
        document.body.style.overflow = 'auto';
    }
    else {
        var newSpan = document.createElement("span");
        newSpan.className = "img-popup";
        newSpan.alt = photoName;
        newSpan.id = photoName;

        newSpan.innerHTML = `<img class='img-popup' src='images/${photoName.toLowerCase()}_large.jpg' alt=${photoName}></img>`;
        newSpan.addEventListener('click', togglePhoto);
        imgElement.insertAdjacentElement('afterbegin', newSpan);
        document.body.style.overflow = 'hidden';
    }


}

function activateMenu() {
    let currentPage = window.location.pathname.split("/").pop();
    if (currentPage.length == 0) {
        currentPage = '/';
    }
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        console.log(currentPage);
        if (link.getAttribute("href") === currentPage) {
            link.classList.add('active');
        }
    })
}

function initShowMorePopups() {
    $(document).on('click', '.show-more-text', function (e) {
        e.preventDefault();
        const petId = $(this).data('id');
        const $popup = $('#popup-' + petId);

        // Debug output
        console.log('Popup debug:', {
            petId: petId,
            elementExists: $popup.length > 0,
            html: $popup.length ? $popup.html() : 'MISSING'
        });

        if ($popup.length) {
            $('body').css('overflow', 'hidden'); // Prevent scrolling
            $popup.fadeIn(200).css('display', 'flex');
        }
    });

    // Close handlers
    $(document).on('click', '.close-popup, .description-popup', function (e) {
        if ($(e.target).hasClass('description-popup') || $(e.target).hasClass('close-popup')) {
            $('body').css('overflow', '');
            $(this).closest('.description-popup').fadeOut(200);
        }
    });

    $(document).keydown(function (e) {
        if (e.key === "Escape") {
            $('body').css('overflow', '');
            $('.description-popup').fadeOut(200);
        }
    });
}

// Initialize when document is ready
$(document).ready(function () {
    initShowMorePopups();
});
