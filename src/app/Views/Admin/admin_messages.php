<?php
include('src/app/Views/includes/Admin/admin_head.php');
include('src/app/Views/includes/Admin/admin_header.php');
include('src/app/Views/includes/Admin/admin_sidebar.php');
?>

<style>
    #notification {
        position: fixed;
        /* Rendre la notification fixe */
        top: 20px;
        /* Ajustez la position verticale */
        right: 20px;
        /* Ajustez la position horizontale */
        z-index: 9999;
        /* Assurez-vous que l'élément est au-dessus des autres éléments */
        /* Ajoutez éventuellement une ombre pour la rendre plus visible */
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    }
</style>

<div class="min-h-screen flex flex-col lg:pl-64 mt-12">
    <div class="container mx-auto px-6 py-8 flex-grow">

        <!-- Notification -->
        <div id="notification" class="hidden fixed top-0 right-0 m-4 p-4 bg-green-500 text-white rounded-md"></div>

        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">📩 Gestion des Messages</h2>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <!-- Barre de recherche et filtres -->
            <div class="flex justify-between mb-4">
                <input id="search" type="text" placeholder="Rechercher un message..." class="border px-4 py-2 rounded-md w-1/3 focus:ring focus:ring-[#8B5A2B]">
                <div class="flex space-x-4">
                    <select id="filterSource" class="border px-4 py-2 rounded-md">
                        <option value="all">Tous</option>
                        <option value="magasin">Magasin</option>
                        <option value="location">Location</option>
                        <option value="evenements">Événements</option>
                    </select>
                    <select id="filterStatus" class="border px-4 py-2 rounded-md">
                        <option value="all">Tous</option>
                        <option value="unread">Non lus</option>
                        <option value="read">Lus</option>
                    </select>
                </div>
            </div>

            <!-- Liste des messages -->
            <table class="w-full border-collapse border">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-3">Nom</th>
                        <th class="border p-3">Email</th>
                        <th class="border p-3">Message</th>
                        <th class="border p-3">Source</th>
                        <th class="border p-3">Statut</th>
                        <th class="border p-3">Actions</th>
                    </tr>
                </thead>
                <tbody id="messagesTable">
                    <?php foreach ($messages as $message) : ?>
                        <tr class="hover:bg-gray-100 message-row <?= $message['status'] === 'unread' ? 'bg-yellow-100' : '' ?>"
                            data-id="<?= $message['id'] ?>"
                            data-status="<?= $message['status'] ?>">

                            <td class="border p-3"><?= htmlspecialchars($message['name']) ?></td>
                            <td class="border p-3"><?= htmlspecialchars($message['email']) ?></td>
                            <td class="border p-3 truncate max-w-xs"><?= htmlspecialchars($message['message']) ?></td>
                            <td class="border p-3"><?= ucfirst(htmlspecialchars($message['source'])) ?></td>
                            <td class="border p-3">
                                <button class="toggleReadStatus px-3 py-1 rounded-md text-white text-xs font-bold 
                        <?= $message['status'] === 'unread' ? 'bg-red-500' : 'bg-green-500' ?>"
                                    data-id="<?= $message['id'] ?>"
                                    data-status="<?= $message['status'] ?>">
                                    <?= $message['status'] === 'unread' ? '🔴 Non lu' : '🟢 Lu' ?>
                                </button>
                            </td>
                            <td class="border p-3">
                                <button class="text-red-600 font-semibold hover:underline deleteMessageBtn" data-id="<?= $message['id'] ?>">❌ Supprimer</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="mt-4 flex justify-center space-x-2" id="pagination"></div>
        </div>
    </div>
</div>

<!-- Modale pour la suppression -->
<div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full">
        <h3 class="text-xl font-bold text-gray-800 mb-4">⚠️ Confirmer la suppression</h3>
        <p class="text-gray-600">Voulez-vous vraiment supprimer ce message ? Cette action est irréversible.</p>
        <div class="flex justify-between mt-4">
            <button id="cancelDelete" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 flex items-center">
                ❌ Annuler
            </button>
            <button id="confirmDelete" class="bg-red-600 text-white px-6 py-3 rounded-md hover:scale-105 transition flex items-center">
                ✅ Supprimer
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let deleteMessageId = null;

        // Recherche dynamique
        document.getElementById('search').addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#messagesTable tr');
            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });

        // Filtrage par source
        document.getElementById('filterSource').addEventListener('change', function() {
            let filter = this.value;
            let rows = document.querySelectorAll('#messagesTable tr');
            rows.forEach(row => {
                let source = row.querySelector('td:nth-child(4)').textContent.trim().toLowerCase();
                row.style.display = (filter === 'all' || source === filter) ? '' : 'none';
            });
        });

        // Filtrage par statut
        document.getElementById('filterStatus').addEventListener('change', function() {
            let filter = this.value;
            let rows = document.querySelectorAll('#messagesTable tr');
            rows.forEach(row => {
                let status = row.getAttribute('data-status');
                row.style.display = (filter === 'all' || status === filter) ? '' : 'none';
            });
        });

        // Marquer un message comme lu
        document.querySelectorAll('.message-row').forEach(row => {
            row.addEventListener('click', function() {
                let messageId = this.getAttribute('data-id');
                let statusBadge = this.querySelector('.status-badge');

                if (this.getAttribute('data-status') === 'unread') {
                    fetch(`admin/messages/mark_as_read/${messageId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                this.setAttribute('data-status', 'read');
                                this.classList.remove('bg-yellow-100');
                                statusBadge.textContent = 'Lu';
                                statusBadge.classList.remove('bg-red-500');
                                statusBadge.classList.add('bg-green-500');
                                updateUnreadMessages();
                            }
                        });
                }
            });
        });

        // Mise à jour dynamique du nombre de messages non lus dans la sidebar
        function updateUnreadMessages() {
            fetch("admin/messages/unread_count")
                .then(response => response.json())
                .then(data => {
                    const messageBadge = document.getElementById("messageBadge");
                    if (data.unread > 0) {
                        messageBadge.textContent = data.unread;
                        messageBadge.classList.remove("hidden");
                    } else {
                        messageBadge.classList.add("hidden");
                    }
                })
                .catch(error => console.error("Erreur récupération messages non lus:", error));
        }

        updateUnreadMessages();
        setInterval(updateUnreadMessages, 10000);
    });

    // Basculer le statut Lu / Non Lu
    document.querySelectorAll('.toggleReadStatus').forEach(button => {
        button.addEventListener('click', function() {
            let messageId = this.getAttribute('data-id');
            let currentStatus = this.getAttribute('data-status');
            let newStatus = currentStatus === 'unread' ? 'read' : 'unread';

            fetch(`admin/messages/update_status/${messageId}?status=${newStatus}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.setAttribute('data-status', newStatus);
                        this.textContent = newStatus === 'unread' ? '🔴 Non lu' : '🟢 Lu';
                        this.classList.toggle('bg-red-500');
                        this.classList.toggle('bg-green-500');

                        let row = this.closest('tr');
                        row.classList.toggle('bg-yellow-100');

                        updateUnreadMessages();
                    }
                });
        });
    });

    // Pagination
    function paginateTable(rowsPerPage = 10) {
        let rows = document.querySelectorAll('#messagesTable tr');
        let totalPages = Math.ceil(rows.length / rowsPerPage);
        let pagination = document.getElementById('pagination');
        pagination.innerHTML = '';

        function showPage(page) {
            rows.forEach((row, index) => {
                row.style.display = (index >= (page - 1) * rowsPerPage && index < page * rowsPerPage) ? '' : 'none';
            });
        }

        for (let i = 1; i <= totalPages; i++) {
            let btn = document.createElement('button');
            btn.textContent = i;
            btn.className = "px-3 py-2 rounded-md bg-gray-300 hover:bg-gray-400";
            btn.addEventListener('click', () => showPage(i));
            pagination.appendChild(btn);
        }
        showPage(1);
    }
    paginateTable();

    // Suppression des messages avec confirmation
    document.querySelectorAll('.deleteMessageBtn').forEach(button => {
        button.addEventListener('click', function() {
            deleteMessageId = this.dataset.id;
            document.getElementById('deleteModal').classList.remove('hidden');
        });
    });

    // Annuler la suppression
    document.getElementById('cancelDelete').addEventListener('click', function() {
        document.getElementById('deleteModal').classList.add('hidden');
    });

    // Confirmer la suppression
    document.getElementById('confirmDelete').addEventListener('click', function() {
        if (deleteMessageId) {
            fetch(`admin/messages/supprimer/${deleteMessageId}`, {
                    method: 'DELETE'
                })
                .then(response => {
                    if (response.ok) {
                        document.querySelector(`button[data-id="${deleteMessageId}"]`).closest('tr').remove(); // Retire l'utilisateur de la table
                        showNotification('Message supprimé avec succès.', 'bg-green-500'); // Message de confirmation
                    } else {
                        showNotification('Erreur lors de la suppression du message.', 'bg-red-500'); // Message d'erreur
                    }
                    document.getElementById('deleteModal').classList.add('hidden'); // Masque la modal
                })
                .catch(error => console.error('Erreur:', error));
        }
    });

    // Fonction pour afficher la notification
    function showNotification(message, bgColor) {
        const notification = document.getElementById('notification');
        notification.textContent = message;
        notification.classList.remove('hidden', 'bg-red-500', 'bg-green-500'); // Supprime toutes les classes de couleur et 'hidden'
        notification.classList.add(bgColor); // Ajoute la classe de couleur

        // Affiche la notification
        notification.classList.remove('hidden'); // Assurez-vous que la notification est visible

        // Masque la notification après 3 secondes
        setTimeout(() => {
            notification.classList.add('hidden');
        }, 3000);
    }

    // Notifiaction délai message succès
    const successDiv = document.getElementById('successMessage');
    if (successDiv) {
        // Au bout de 3 secondes, on masque la div
        setTimeout(() => {
            successDiv.style.display = 'none';
        }, 3000); // 3000 ms = 3 secondes
    }
</script>