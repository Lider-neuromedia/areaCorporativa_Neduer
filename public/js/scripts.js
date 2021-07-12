document.addEventListener('DOMContentLoaded', function () {
    initProfileMenu();
    toggleSideMenu();
});

function initProfileMenu() {
    let btnMenu = document.querySelectorAll('.toggle-profile-menu');
    btnMenu.forEach(function (el) {
        el.addEventListener('click', toggleProfileMenu);
    });
}

function toggleProfileMenu() {
    let profileMenu = document.getElementById('profile-menu');
    profileMenu.classList.toggle('show');
}


function toggleSideMenu() {
    let menuButton = document.getElementById('aside-button');
    let asideMenu = document.getElementById('board-aside');

    menuButton.addEventListener('click', function (e) {

        menuButton.classList.toggle('close');
        asideMenu.classList.toggle('show');

    });
}
