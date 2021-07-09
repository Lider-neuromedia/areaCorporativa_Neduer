document.addEventListener('DOMContentLoaded', function () {
    initProfileMenu();
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
