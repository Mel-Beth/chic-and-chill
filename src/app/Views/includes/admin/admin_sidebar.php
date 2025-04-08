<aside class="sidebar fixed top-0 left-0 h-screen flex flex-col bg-gray-900 text-white w-64 shadow-lg z-20 transform -translate-x-full lg:translate-x-0 transition-transform duration-300">
    <!-- Bouton de fermeture (mobile uniquement) -->
    <button id="closeSidebar" class="md:hidden absolute top-4 right-4 text-white focus:outline-none">
        <span class="material-icons">close</span>
    </button>

    <!-- Logo et Nom -->
    <div class="flex items-center justify-center py-6">
        <span class="text-3xl font-bold">@</span>
        <span class="text-xl font-semibold ml-2">Chic & Chill</span>
    </div>

    <!-- Navigation -->
    <nav class="flex-grow px-3 overflow-y-auto">
        <ul class="space-y-2">
            <li>
                <a href="admin/dashboard" class="nav-link flex items-center space-x-3 px-4 py-3 hover:bg-gray-700 rounded">
                    <span class="material-icons">grid_view</span>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <button id="toggleEvenements" class="nav-link w-full flex items-center space-x-3 px-4 py-3 hover:bg-gray-700 rounded focus:outline-none">
                    <span class="material-icons">event</span>
                    <span>Événements</span>
                    <span class="material-icons ml-auto">expand_more</span>
                </button>
                <ul id="evenementsMenu" class="hidden bg-gray-800 text-white rounded mt-1">
                    <li><a href="admin/evenements" class="nav-link sub-link block px-4 py-2 hover:bg-gray-700">Liste des événements</a></li>
                    <li><a href="admin/packs" class="nav-link sub-link block px-4 py-2 hover:bg-gray-700">Packs</a></li>
                    <li><a href="admin/outfits" class="nav-link sub-link block px-4 py-2 hover:bg-gray-700">Tenues</a></li>
                    <li><a href="admin/reservations" class="nav-link sub-link block px-4 py-2 hover:bg-gray-700">Réservations</a></li>
                </ul>
            </li>
            <li>
                <button id="toggleMagasin" class="nav-link w-full flex items-center space-x-3 px-4 py-3 hover:bg-gray-700 rounded focus:outline-none">
                    <span class="material-icons">storefront</span>
                    <span>Magasin</span>
                    <span class="material-icons ml-auto">expand_more</span>
                </button>
                <ul id="magasinMenu" class="hidden bg-gray-800 text-white rounded mt-1">
                    <li><a href="admin/crudShop" class="nav-link sub-link block px-4 py-2 hover:bg-gray-700">Articles</a></li>
                </ul>
            </li>
            <li>
                <a href="admin/messages" class="nav-link flex items-center space-x-3 px-4 py-3 hover:bg-gray-700 rounded relative">
                    <span class="material-icons">mail</span>
                    <span>Messages</span>
                    <span id="messageBadge" class="absolute top-2 right-2 bg-red-500 text-xs px-2 py-1 rounded-full hidden">0</span>
                </a>
            </li>
            <li><a href="admin/users" class="nav-link flex items-center space-x-3 px-4 py-3 hover:bg-gray-700 rounded"><span class="material-icons">people</span><span>Utilisateurs</span></a></li>
            <li><a href="admin/newsletter" class="nav-link flex items-center space-x-3 px-4 py-3 hover:bg-gray-700 rounded"><span class="material-icons">subscriptions</span><span>Newsletter</span></a></li>
            <li><a href="admin/settings" class="nav-link flex items-center space-x-3 px-4 py-3 hover:bg-gray-700 rounded"><span class="material-icons">settings</span><span>Paramètres</span></a></li>
        </ul>
    </nav>

    <!-- Déconnexion -->
    <div class="border-t border-gray-700 p-4">
        <a href="admin/logout" class="nav-link flex items-center space-x-3 px-4 py-3 hover:bg-gray-700 rounded">
            <span class="material-icons">logout</span>
            <span>Déconnexion</span>
        </a>
    </div>
</aside>

<!-- Overlay (fond sombre) pour mobile -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-10 hidden lg:hidden"></div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Gestion des menus déroulants
        const toggleButtons = {
            toggleEvenements: 'evenementsMenu',
            toggleMagasin: 'magasinMenu'
        };

        Object.entries(toggleButtons).forEach(([buttonId, menuId]) => {
            const button = document.getElementById(buttonId);
            const menu = document.getElementById(menuId);

            button.addEventListener("click", function(e) {
                e.preventDefault();
                menu.classList.toggle("hidden");
                updateMainLinkState(button, !menu.classList.contains("hidden"));
            });
        });

        // Gestion des clics sur les liens et sous-liens
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                // Si c'est un sous-lien
                if (link.classList.contains('sub-link')) {
                    const parentMenu = link.closest('ul');
                    const parentButton = parentMenu.previousElementSibling;

                    // Garder le menu ouvert et actif
                    parentMenu.classList.remove('hidden');
                    parentButton.classList.add('bg-gray-700');

                    // Mettre à jour l'état actif
                    removeAllActiveStatesExceptParent(parentButton);
                    link.classList.add('bg-gray-700');
                }
                // Si c'est un lien principal sans sous-menu
                else if (!link.matches('#toggleEvenements, #toggleMagasin')) {
                    removeAllActiveStates();
                    link.classList.add('bg-gray-700');
                    closeAllMenus();
                }
            });
        });

        // Initialiser l'état actif basé sur l'URL actuelle
        const currentPath = window.location.pathname.split('/').slice(-2).join('/'); // Extrait "admin/dashboard"
        navLinks.forEach(link => {
            const linkPath = link.getAttribute('href')?.split('/').slice(-2).join('/'); // Extrait "admin/dashboard" du href
            if (linkPath && linkPath === currentPath) {
                link.classList.add('bg-gray-700');
                // Si c'est un sous-lien, ouvrir et activer le parent
                if (link.classList.contains('sub-link')) {
                    const parentMenu = link.closest('ul');
                    const parentButton = parentMenu.previousElementSibling;
                    parentMenu.classList.remove('hidden');
                    parentButton.classList.add('bg-gray-700');
                }
            }
        });

        // Gestion de la sidebar mobile
        const sidebar = document.querySelector(".sidebar");
        const menuToggle = document.getElementById("menuToggle");
        const closeSidebar = document.getElementById("closeSidebar");
        const sidebarOverlay = document.getElementById("sidebarOverlay");

        function toggleSidebar() {
            sidebar.classList.toggle("-translate-x-full");
            sidebarOverlay.classList.toggle("hidden");
            setTimeout(() => {
                sidebar.style.overflowY = "auto";
                window.dispatchEvent(new Event('resize'));
            }, 300);
        }

        if (menuToggle) menuToggle.addEventListener("click", toggleSidebar);
        if (closeSidebar) closeSidebar.addEventListener("click", toggleSidebar);
        if (sidebarOverlay) sidebarOverlay.addEventListener("click", toggleSidebar);

        // Gestion responsive du menu burger
        window.addEventListener("resize", () => {
            if (window.innerWidth < 1024) {
                menuToggle?.classList.remove("hidden");
            } else {
                menuToggle?.classList.add("hidden");
            }
        });

        // Messages non lus
        function updateUnreadMessages() {
            fetch("admin/messages/unread_count", {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const messageBadge = document.getElementById("messageBadge");
                    const unreadCount = data.unread;
                    if (unreadCount > 0) {
                        messageBadge.textContent = unreadCount;
                        messageBadge.classList.remove("hidden");
                    } else {
                        messageBadge.classList.add("hidden");
                    }
                })
                .catch(error => console.error("Erreur récupération messages non lus:", error));
        }

        updateUnreadMessages();
        setInterval(updateUnreadMessages, 10000);

        // Fonctions utilitaires
        function removeAllActiveStates() {
            navLinks.forEach(link => link.classList.remove('bg-gray-700'));
        }

        function removeAllActiveStatesExceptParent(parentButton) {
            navLinks.forEach(link => {
                if (link !== parentButton) {
                    link.classList.remove('bg-gray-700');
                }
            });
        }

        function updateMainLinkState(element, isOpen) {
            if (isOpen) {
                element.classList.add('bg-gray-700');
            } else {
                element.classList.remove('bg-gray-700');
            }
        }

        function closeAllMenus() {
            document.querySelectorAll('#evenementsMenu, #magasinMenu').forEach(menu => {
                menu.classList.add('hidden');
            });
        }
    });
</script>

<style>
    @import url('https://fonts.googleapis.com/icon?family=Material+Icons');

    .sidebar {
        overflow-y: auto;
    }

    .sidebar nav {
        overflow-y: auto;
    }
</style>