/**
 * ETML
 * Author: Sebastien Tille
 * Date: October 8th, 2020
 * Description: Handles the navbar' state (shown / hidden). Behavior inpired by
 * the Anilist website.
 * Note: Taken from a previous project.
 * See https://gitlab.com/wolfiy/wolfiy.gitlab.io
 */

/**
 * Value from which to hide the navbar.
 */
const SCROLLING_THRESHOLD = 55;

/**
 * Offset between the current position and the top of the page.
 */
var oldScrollPos = window.scrollY;

/**
 * Height of the navbar.
 */
var navHeight = "-9ch";

/**
 * Runs all functions related to the navbar on mouse scroll.
 */
window.onscroll = function() {
    hideShowNavbar();
}

/**
 * Shows or hide the navbar depending on whether the user scrolled up or down.
 */
function hideShowNavbar() {
    var navbar = document.getElementById("navbar");
    var newScrollPos = window.scrollY;

    (oldScrollPos > newScrollPos || window.scrollY <= SCROLLING_THRESHOLD) ? 
        navbar.style.top = "0" : navbar.style.top = navHeight;
    oldScrollPos = newScrollPos;
}