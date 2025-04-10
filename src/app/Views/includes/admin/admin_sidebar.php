<!-- Sidebar fixe à gauche, cachée par défaut sur mobile -->
<aside class="sidebar fixed top-0 left-0 h-screen flex flex-col bg-gray-900 text-white w-64 shadow-lg z-20 transform -translate-x-full lg:translate-x-0 transition-transform duration-300">
    <!-- Bouton de fermeture pour mobile, masqué sur desktop -->
    <button id="closeSidebar" class="md:hidden absolute top-4 right-4 text-white focus:outline-none">
        <span class="material-icons">close</span>
    </button>

    <!-- Logo et nom du site -->
    <div class="flex items-center justify-center py-6">
        <span class="text-3xl font-bold">@</span>
        <span class="text-xl font-semibold ml-2">Chic & Chill</span>
    </div>

    <!-- Navigation principale avec défilement si nécessaire -->
    <nav class="flex-grow px-3 overflow-y-auto">
        <ul class="space-y-2">
            <!-- Lien vers le tableau de bord -->
            <li>
                <a href="admin/dashboard" class="nav-link flex items-center space-x-3 px-4 py-3 hover:bg-gray-700 rounded">
                    <span class="material-icons">grid_view</span>
                    <span>Dashboard</span>
                </a>
            </li>
            <!-- Menu déroulant pour les événements -->
            <li>
                <button id="toggleEvenements" class="nav-link w-full flex items-center space-x-3 px-4 py-3 hover:bg-gray-700 rounded focus:outline-none">
                    <span class="material-icons">event</span>
                    <span>Événements</span>
                    <span class="material-icons ml-auto">expand_more</span>
                </button>
                <!-- Sous-menu caché par défaut -->
                <ul id="evenementsMenu" class="hidden bg-gray-800 text-white rounded mt-1">
                    <li><a href="admin/evenements" class="nav-link sub-link block px-4 py-2 hover:bg-gray-700">Liste des événements</a></li>
                    <li><a href="admin/packs" class="nav-link sub-link block px-4 py-2 hover:bg-gray-700">Packs</a></li>
                    <li><a href="admin/outfits" class="nav-link sub-link block px-4 py-2 hover:bg-gray-700">Tenues</a></li>
                    <li><a href="admin/reservations" class="nav-link sub-link block px-4 py-2 hover:bg-gray-700">Réservations</a></li>
                </ul>
            </li>
            <!-- Menu déroulant pour le magasin -->
            <li>
                <button id="toggleMagasin" class="nav-link w-full flex items-center space-x-3 px-4 py-3 hover:bg-gray-700 rounded focus:outline-none">
                    <span class="material-icons">storefront</span>
                    <span>Magasin</span>
                    <span class="material-icons ml-auto">expand_more</span>
                </button>
                <!-- Sous-menu caché par défaut -->
                <ul id="magasinMenu" class="hidden bg-gray-800 text-white rounded mt-1">
                    <li><a href="admin/crudShop" class="nav-link sub-link block px-4 py-2 hover:bg-gray-700">Articles</a></li>
                </ul>
            </li>
            <!-- Lien vers les messages avec badge pour les non-lus -->
            <li>
                <a href="admin/messages" class="nav-link flex items-center space-x-3 px-4 py-3 hover:bg-gray-700 rounded relative">
                    <span class="material-icons">mail</span>
                    <span>Messages</span>
                    <span id="messageBadge" class="absolute top-2 right-2 bg-red-500 text-xs px-2 py-1 rounded-full hidden">0</span>
                </a>
            </li>
            <!-- Autres liens de navigation -->
            <li><a href="admin/users" class="nav-link flex items-center space-x-3 px-4 py-3 hover:bg-gray-700 rounded"><span class="material-icons">people</span><span>Utilisateurs</span></a></li>
            <li><a href="admin/newsletter" class="nav-link flex items-center space-x-3 px-4 py-3 hover:bg-gray-700 rounded"><span class="material-icons">subscriptions</span><span>Newsletter</span></a></li>
            <li><a href="admin/settings" class="nav-link flex items-center space-x-3 px-4 py-3 hover:bg-gray-700 rounded"><span class="material-icons">settings</span><span>Paramètres</span></a></li>
        </ul>
    </nav>

    <!-- Section de déconnexion avec bordure supérieure -->
    <div class="border-t border-gray-700 p-4">
        <a href="admin/logout" class="nav-link flex items-center space-x-3 px-4 py-3 hover:bg-gray-700 rounded">
            <span class="material-icons">logout</span>
            <span>Déconnexion</span>
        </a>
    </div>
</aside>

<!-- Overlay sombre pour mobile, affiché lors de l'ouverture de la sidebar -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-10 hidden lg:hidden"></div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Gestion des menus déroulants
        const toggleButtons = {
            toggleEvenements: 'evenementsMenu', // Associe le bouton Événements à son sous-menu
            toggleMagasin: 'magasinMenu'        // Associe le bouton Magasin à son sous-menu
        };

        Object.entries(toggleButtons).forEach(([buttonId, menuId]) => {
            const button = document.getElementById(buttonId);
            const menu = document.getElementById(menuId);

            button.addEventListener("click", function(e) {
                e.preventDefault(); // Empêche tout comportement par défaut
                menu.classList.toggle("hidden"); // Bascule la visibilité du sous-menu
                updateMainLinkState(button, !menu.classList.contains("hidden")); // Met à jour l'apparence du bouton
            });
        });

        // Gestion des clics sur les liens de navigation
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                // Si c'est un sous-lien
                if (link.classList.contains('sub-link')) {
                    const parentMenu = link.closest('ul');
                    const parentButton = parentMenu.previousElementSibling;

                    // Garde le menu parent ouvert et actif
                    parentMenu.classList.remove('hidden');
                    parentButton.classList.add('bg-gray-700');

                    // Supprime les états actifs sauf pour le parent, puis active le sous-lien
                    removeAllActiveStatesExceptParent(parentButton);
                    link.classList.add('bg-gray-700');
                }
                // Si c'est un lien principal sans sous-menu
                else if (!link.matches('#toggleEvenements, #toggleMagasin')) {
                    removeAllActiveStates(); // Désactive tous les autres liens
                    link.classList.add('bg-gray-700'); // Active le lien cliqué
                    closeAllMenus(); // Ferme tous les sous-menus
                }
            });
        });

        // Initialisation de l'état actif basé sur l'URL actuelle
        const currentPath = window.location.pathname.split('/').slice(-2).join('/'); // Extrait "admin/dashboard"
        navLinks.forEach(link => {
            const linkPath = link.getAttribute('href')?.split('/').slice(-2).join('/'); // Extrait le chemin du lien
            if (linkPath && linkPath === currentPath) {
                link.classList.add('bg-gray-700'); // Active le lien correspondant à l'URL
                // Si c'est un sous-lien, ouvre et active le parent
                if (link.classList.contains('sub-link')) {
                    const parentMenu = link.closest('ul');
                    const parentButton = parentMenu.previousElementSibling;
                    parentMenu.classList.remove('hidden');
                    parentButton.classList.add('bg-gray-700');
                }
            }
        });

        // Gestion de la sidebar sur mobile
        const sidebar = document.querySelector(".sidebar");
        const menuToggle = document.getElementById("menuToggle");
        const closeSidebar = document.getElementById("closeSidebar");
        const sidebarOverlay = document.getElementById("sidebarOverlay");

        function toggleSidebar() {
            sidebar.classList.toggle("-translate-x-full"); // Ouvre ou ferme la sidebar
            sidebarOverlay.classList.toggle("hidden"); // Affiche ou masque l'overlay
            setTimeout(() => {
                sidebar.style.overflowY = "auto"; // Active le défilement après l'animation
                window.dispatchEvent(new Event('resize')); // Déclenche un événement resize
            }, 300); // Délai correspondant à la durée de l'animation
        }

        if (menuToggle) menuToggle.addEventListener("click", toggleSidebar);
        if (closeSidebar) closeSidebar.addEventListener("click", toggleSidebar);
        if (sidebarOverlay) sidebarOverlay.addEventListener("click", toggleSidebar);

        // Gestion responsive du bouton hamburger
        window.addEventListener("resize", () => {
            if (window.innerWidth < 1024) {
                menuToggle?.classList.remove("hidden"); // Affiche le bouton sur mobile
            } else {
                menuToggle?.classList.add("hidden"); // Masque sur desktop
            }
        });

        // Mise à jour du badge des messages non lus
        function updateUnreadMessages() {
            fetch("admin/messages/unread_count", {
                method: "GET",
                headers: { "Content-Type": "application/json" }
            })
            .then(response => response.json())
            .then(data => {
                const messageBadge = document.getElementById("messageBadge");
                const unreadCount = data.unread;
                if (unreadCount > 0) {
                    messageBadge.textContent = unreadCount; // Affiche le nombre de messages
                    messageBadge.classList.remove("hidden"); // Rend le badge visible
                } else {
                    messageBadge.classList.add("hidden"); // Masque si aucun message
                }
            })
            .catch(error => console.error("Erreur récupération messages non lus:", error));
        }

        updateUnreadMessages(); // Appel initial
        setInterval(updateUnreadMessages, 10000); // Mise à jour toutes les 10 secondes

        // Fonctions utilitaires
        function removeAllActiveStates() {
            navLinks.forEach(link => link.classList.remove('bg-gray-700')); // Désactive tous les liens
        }

        function removeAllActiveStatesExceptParent(parentButton) {
            navLinks.forEach(link => {
                if (link !== parentButton) {
                    link.classList.remove('bg-gray-700'); // Désactive sauf le parent
                }
            });
        }

        function updateMainLinkState(element, isOpen) {
            if (isOpen) {
                element.classList.add('bg-gray-700'); // Active le bouton si ouvert
            } else {
                element.classList.remove('bg-gray-700'); // Désactive sinon
            }
        }

        function closeAllMenus() {
            document.querySelectorAll('#evenementsMenu, #magasinMenu').forEach(menu => {
                menu.classList.add('hidden'); // Ferme tous les sous-menus
            });
        }
    });
</script>

<style>
    @import url('https://fonts.googleapis.com/icon?family=Material+Icons'); /* Importation des icônes Material Icons */

    .sidebar {
        overflow-y: auto; /* Active le défilement vertical si nécessaire */
    }

    .sidebar nav {
        overflow-y: auto; /* Défilement pour la navigation */
    }

    /* Scrollbar personnalisée pour Firefox */
    .sidebar {
        scrollbar-width: thin; /* Épaisseur fine */
        scrollbar-color: #718096 #2D3748; /* Pouce gris moyen, piste gris foncé */
    }

    /* Scrollbar personnalisée pour WebKit (Chrome, Edge, Safari) */
    .sidebar::-webkit-scrollbar {
        width: 8px; /* Largeur de la scrollbar */
    }

    .sidebar::-webkit-scrollbar-track {
        background: #2D3748; /* Piste gris foncé */
        border-radius: 10px; /* Coins arrondis */
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: #718096; /* Pouce gris moyen */
        border-radius: 10px; /* Coins arrondis */
        border: 2px solid #2D3748; /* Bordure pour effet encastré */
    }

    .sidebar::-webkit-scrollbar-thumb:hover {
        background: #A0AEC0; /* Gris clair au survol */
    }

    /* Scrollbar pour la navigation interne */
    .sidebar nav::-webkit-scrollbar {
        width: 8px;
    }

    .sidebar nav::-webkit-scrollbar-track {
        background: #2D3748;
        border-radius: 10px;
    }

    .sidebar nav::-webkit-scrollbar-thumb {
        background: #718096;
        border-radius: 10px;
        border: 2px solid #2D3748;
    }

    .sidebar nav::-webkit-scrollbar-thumb:hover {
        background: #A0AEC0;
    }

    .sidebar nav {
        scrollbar-width: thin;
        scrollbar-color: #718096 #2D3748;
    }
</style>