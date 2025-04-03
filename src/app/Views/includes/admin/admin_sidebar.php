<aside class="sidebar fixed top-0 left-0 h-screen flex flex-col bg-gray-900 text-white w-64 shadow-lg z-20 transform -translate-x-full lg:translate-x-0 transition-transform duration-300"> <!-- Bouton de fermeture (mobile uniquement) -->
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
                <a href="admin/dashboard" class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-700 rounded">
                    <span class="material-icons">grid_view</span>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <button id="toggleEvenements" class="w-full flex items-center space-x-3 px-4 py-3 hover:bg-gray-700 rounded focus:outline-none">
                    <span class="material-icons">event</span>
                    <span>Événements</span>
                    <span class="material-icons ml-auto">expand_more</span>
                </button>
                <ul id="evenementsMenu" class="hidden bg-gray-800 text-white rounded">
                    <li><a href="admin/evenements" class="block px-4 py-2 hover:bg-gray-700">Liste des événements</a></li>
                    <li><a href="admin/packs" class="block px-4 py-2 hover:bg-gray-700">Packs</a></li>
                    <li><a href="admin/outfits" class="block px-4 py-2 hover:bg-gray-700">Tenues</a></li>
                    <li><a href="admin/reservations" class="block px-4 py-2 hover:bg-gray-700">Réservations</a></li>
                </ul>
            </li>
            <li>
                <button id="toggleMagasin" class="w-full flex items-center space-x-3 px-4 py-3 hover:bg-gray-700 rounded focus:outline-none">
                    <span class="material-icons">storefront</span>
                    <span>Magasin</span>
                    <span class="material-icons ml-auto">expand_more</span>
                </button>
                <ul id="magasinMenu" class="hidden bg-gray-800 text-white rounded">
                    <li><a href="admin/crudShop" class="block px-4 py-2 hover:bg-gray-700">Articles</a></li>
                </ul>
            </li>
            <li>
                <a href="admin/messages" class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-700 rounded relative">
                    <span class="material-icons">mail</span>
                    <span>Messages</span>
                    <span id="messageBadge" class="absolute top-2 right-2 bg-red-500 text-xs px-2 py-1 rounded-full hidden">0</span>
                </a>
            </li>
            <li><a href="admin/users" class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-700 rounded"><span class="material-icons">people</span><span>Utilisateurs</span></a></li>
            <li><a href="admin/newsletter" class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-700 rounded"><span class="material-icons">subscriptions</span><span>Newsletter</span></a></li>
            <li><a href="admin/settings" class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-700 rounded"><span class="material-icons">settings</span><span>Paramètres</span></a></li>
        </ul>
    </nav>

    <!-- Déconnexion -->
    <div class="border-t border-gray-700 p-4">
        <a href="admin/logout" class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-700 rounded">
            <span class="material-icons">logout</span>
            <span>Déconnexion</span>
        </a>
    </div>
</aside>

<!-- Overlay (fond sombre) pour mobile -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-10 hidden lg:hidden"></div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Basculer l'affichage du menu événements
        document.getElementById("toggleEvenements").addEventListener("click", function() {
            document.getElementById("evenementsMenu").classList.toggle("hidden");
        });
        document.getElementById("toggleMagasin").addEventListener("click", function() {
            document.getElementById("magasinMenu").classList.toggle("hidden");
        });

        // Gestion de la sidebar et de l'overlay
        const sidebar = document.querySelector(".sidebar");
        const menuToggle = document.getElementById("menuToggle");
        const closeSidebar = document.getElementById("closeSidebar");
        const sidebarOverlay = document.getElementById("sidebarOverlay");

        function toggleSidebar() {
            sidebar.classList.toggle("-translate-x-full");
            sidebarOverlay.classList.toggle("hidden");
            // Forcer un recalcul de la mise en page pour la scrollbar
            setTimeout(() => {
                sidebar.style.overflowY = "auto";
                window.dispatchEvent(new Event('resize'));
            }, 300);
        }

        if (menuToggle && sidebar) {
            menuToggle.addEventListener("click", toggleSidebar);
        }
        if (closeSidebar && sidebar) {
            closeSidebar.addEventListener("click", toggleSidebar);
        }
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener("click", toggleSidebar);
        }

        // S'assurer que le menu burger est visible en dessous de 1024px
        if (window.innerWidth < 1024) {
            menuToggle.classList.remove("hidden");
        } else {
            menuToggle.classList.add("hidden");
        }

        // Mettre à jour la visibilité du menu burger lors du redimensionnement
        window.addEventListener("resize", () => {
            if (window.innerWidth < 1024) {
                menuToggle.classList.remove("hidden");
            } else {
                menuToggle.classList.add("hidden");
            }
        });

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

        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('toggleReadStatus')) {
                setTimeout(updateUnreadMessages, 1000);
            }
        });
    });
</script>

<style>
    @import url('https://fonts.googleapis.com/icon?family=Material+Icons');

    /* S'assurer que la sidebar a une scrollbar si nécessaire */
    .sidebar {
        overflow-y: auto;
        /* Activer la scrollbar verticale si le contenu dépasse */
    }

    /* S'assurer que le nav à l'intérieur peut défiler */
    .sidebar nav {
        overflow-y: auto;
        /* Activer la scrollbar sur le nav */
    }
</style>