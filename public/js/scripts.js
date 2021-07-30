document.addEventListener('DOMContentLoaded', function () {
    initProfileMenu();
    toggleSideMenu();
    initFilesList();
    initFilesRefreshApp();
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

    if (!menuButton) return;

    menuButton.addEventListener('click', function (e) {

        menuButton.classList.toggle('close');
        asideMenu.classList.toggle('show');

    });
}

function initFilesList() {
    if (!document.getElementById("files-list-app")) return;

    let filesListApp = new Vue({
        el: '#files-list-app',
        data: {
            files: [],
            isLoading: false,
        },
        mounted() {
            this.loadFiles();
        },
        methods: {
            async loadFiles() {
                try {
                    this.isLoading = true;
                    const route = document.querySelector('meta[name=route]').content;
                    const response = await window.axios.get(`${route}/files-list`);
                    this.files = response.data.files || [];
                    this.isLoading = false;
                } catch (error) {
                    console.error(error);
                    this.isLoading = false;
                    this.files = [];
                }
            }
        }
    });
}

function initFilesRefreshApp() {
    if (!document.getElementById("files-refresh-app")) return;

    let filesRefreshApp = new Vue({
        el: '#files-refresh-app',
        data: {
            isLoading: false,
            message: "",
        },
        methods: {
            async refreshFiles() {
                try {
                    this.message = '';
                    this.isLoading = true;
                    const route = document.querySelector('meta[name=route]').content;
                    const response = await window.axios.post(`${route}/files-refresh`);
                    this.message = response.data.message;
                    this.isLoading = false;
                    window.location.reload();
                } catch (error) {
                    console.error(error);
                    this.message = "Ocurrió un error al actualizar la lista de documentos.";
                    this.isLoading = false;
                }
            }
        }
    });
}
