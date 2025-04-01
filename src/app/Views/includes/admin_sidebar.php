<aside class="h-screen fixed top-0 left-0 flex flex-col bg-gray-900 text-white w-64 shadow-lg">
    <!-- Logo et Nom -->
    <div class="flex items-center justify-center py-6">
        <span class="text-3xl font-bold">@</span>
        <span class="text-xl font-semibold ml-2">Chic & Chill</span>
    </div>

    <!-- Navigation -->
    <nav class="flex-grow px-3">
        <ul class="space-y-2">
            <li>
                <a href="admin/dashboard" class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-700 rounded">
                    <span class="material-icons">grid_view</span>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Menu Événements (Déroulable) -->
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

            <!-- Location -->
            <li>
                <a href="admin/locations" class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-700 rounded">
                    <span class="material-icons">shopping_bag</span>
                    <span>Locations</span>
                </a>
            </li>

            <!-- Showroom -->
            <li>
                <a href="admin/showroom" class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-700 rounded">
                    <span class="material-icons">storefront</span>
                    <span>Showroom</span>
                </a>
            </li>

            <!-- Lien Messages avec compteur dynamique -->
            <li>
                <a href="admin/messages" class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-700 rounded relative">
                    <span class="material-icons">mail</span>
                    <span>Messages</span>
                    <span id="messageBadge" class="absolute top-2 right-2 bg-red-500 text-xs px-2 py-1 rounded-full hidden">0</span>
                </a>
            </li>

            <li>
                <a href="admin/users" class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-700 rounded">
                    <span class="material-icons">people</span>
                    <span>Utilisateurs</span>
                </a>
            </li>

            <li>
                <a href="admin/newsletter" class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-700 rounded">
                    <span class="material-icons">subscriptions</span>
                    <span>Newsletter</span>
                </a>
            </li>

            <li>
                <a href="admin/settings" class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-700 rounded">
                    <span class="material-icons">settings</span>
                    <span>Paramètres</span>
                </a>
            </li>
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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("toggleEvenements").addEventListener("click", function() {
            document.getElementById("evenementsMenu").classList.toggle("hidden");
        });

        function updateUnreadMessages() {
            fetch("admin/messages/unread_count")
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById("messageBadge");
                    if (data.unread > 0) {
                        badge.textContent = data.unread;
                        badge.classList.remove("hidden");
                    } else {
                        badge.classList.add("hidden");
                    }
                });
        }

        updateUnreadMessages();
        setInterval(updateUnreadMessages, 10000);
    });
</script>

<style>
    @import url('https://fonts.googleapis.com/icon?family=Material+Icons');
</style>
