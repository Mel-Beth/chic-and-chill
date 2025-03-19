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
            <h2 class="text-3xl font-bold text-gray-800">👥 Gestion des Utilisateurs</h2>
        </div>

        <div class="flex justify-between mb-4">
            <input id="search" type="text" placeholder="Rechercher un utilisateur..." class="border px-4 py-2 rounded-md w-1/3 focus:ring focus:ring-[#8B5A2B]">
            <div class="flex space-x-4">
                <button id="exportBtn" class="border px-4 py-2 rounded-md hover:bg-gray-100">Exporter</button>
                <select id="filterStatus" class="border px-4 py-2 rounded-md">
                    <option value="all">Tous</option>
                    <option value="active">Actifs</option>
                    <option value="inactive">Inactifs</option>
                </select>
                <button id="toggleAdmins" class="border px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300">
                    Masquer les Admins
                </button>
            </div>
        </div>

        <!-- Liste des utilisateurs -->
        <table class="w-full border-collapse border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-3">Nom</th>
                    <th class="border p-3">Email</th>
                    <th class="border p-3">Rôle</th>
                    <th class="border p-3">Statut</th>
                    <th class="border p-3">Actions</th>
                </tr>
            </thead>
            <tbody id="userTable">
                <?php if (!empty($users)) : ?>
                    <?php foreach ($users as $user) : ?>
                        <tr class="hover:bg-gray-100">
                            <td class="border p-3"><?= htmlspecialchars($user['name']) ?></td>
                            <td class="border p-3"><?= htmlspecialchars($user['email']) ?></td>
                            <td class="border p-3">
                                <span class="px-3 py-1 rounded-full text-white text-xs font-bold <?= $user['role'] === 'admin' ? 'bg-blue-500' : 'bg-gray-500' ?>">
                                    <?= htmlspecialchars($user['role']) ?>
                                </span>
                            </td>
                            <td class="border p-3">
                                <?php $status = !empty($user['status']) ? $user['status'] : 'inactive'; ?>
                                <button class="toggleStatus px-3 py-1 rounded-md text-white text-xs font-bold 
                                    <?= $status === 'active' ? 'bg-green-500' : 'bg-red-500' ?>"
                                    data-id="<?= $user['id'] ?>"
                                    data-status="<?= $status ?>">
                                    <?= $status === 'active' ? '🟢 Actif' : '🔴 Inactif' ?>
                                </button>
                            </td>
                            <td class="border p-3">
                                <div class="flex space-x-4">
                                    <a href="admin/users/historique/<?= $user['id'] ?>" class="text-blue-600 font-semibold hover:underline">📜 Voir historique</a>
                                    <button class="text-red-600 font-semibold hover:underline deleteUserBtn" data-id="<?= $user['id'] ?>">❌ Supprimer</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="text-center py-4">Aucun utilisateur trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4 flex justify-center space-x-2" id="pagination"></div>
    </div>
</div>

<!-- Modale pour la suppression -->
<div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full">
        <h3 class="text-xl font-bold text-gray-800 mb-4">⚠️ Confirmer la suppression</h3>
        <p class="text-gray-600">Voulez-vous vraiment supprimer cet utilisateur ? Cette action est irréversible.</p>
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
        let showAdmins = true;
        let deleteUserId = null;

        // Suppression des utilisateurs avec confirmation
        document.querySelectorAll('.deleteUserBtn').forEach(button => {
            button.addEventListener('click', function() {
                deleteUserId = this.dataset.id; // Stocke l'ID de l'utilisateur
                document.getElementById('deleteModal').classList.remove('hidden'); // Affiche la modal
            });
        });

        // Annuler la suppression
        document.getElementById('cancelDelete').addEventListener('click', function() {
            document.getElementById('deleteModal').classList.add('hidden'); // Masque la modal
            deleteUserId = null; // Réinitialise l'ID
        });

        // Confirmer la suppression
        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (deleteUserId) {
                fetch(`admin/users/supprimer/${deleteUserId}`, {
                        method: 'DELETE'
                    })
                    .then(response => {
                        if (response.ok) {
                            document.querySelector(`button[data-id="${deleteUserId}"]`).closest('tr').remove(); // Retire l'utilisateur de la table
                            showNotification('Utilisateur supprimé avec succès.', 'bg-green-500'); // Message de confirmation
                        } else {
                            showNotification('Erreur lors de la suppression de l\'utilisateur.', 'bg-red-500'); // Message d'erreur
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

        // Gestion de la recherche
        document.getElementById('search').addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#userTable tr');
            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });

        // Filtrage par statut (actif/inactif)
        document.getElementById('filterStatus').addEventListener('change', function() {
            let filter = this.value;
            let rows = document.querySelectorAll('#userTable tr');
            rows.forEach(row => {
                let status = row.querySelector('.toggleStatus')?.getAttribute('data-status');
                row.style.display = (filter === 'all' || status === filter) ? '' : 'none';
            });
        });

        // Afficher/Masquer les admins
        document.getElementById('toggleAdmins').addEventListener('click', function() {
            showAdmins = !showAdmins;
            let rows = document.querySelectorAll('#userTable tr');

            rows.forEach(row => {
                let role = row.querySelector('td:nth-child(3) span')?.textContent.trim();
                if (role === 'admin') {
                    row.style.display = showAdmins ? '' : 'none';
                }
            });

            // Mise à jour du texte du bouton
            this.textContent = showAdmins ? "Masquer les Admins" : "Afficher les Admins";
        });

        // Changement de statut de l'utilisateur
        document.querySelectorAll('.toggleStatus').forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-id');
                const currentStatus = this.getAttribute('data-status');
                const newStatus = currentStatus === 'active' ? 'inactive' : 'active';

                // Envoi de la requête AJAX pour mettre à jour le statut
                fetch(`admin/users/modifier_status/${userId}?status=${newStatus}`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Met à jour le bouton et le statut affiché
                            this.setAttribute('data-status', newStatus);
                            this.textContent = newStatus === 'active' ? '🟢 Actif' : '🔴 Inactif';
                            this.classList.toggle('bg-green-500', newStatus === 'active');
                            this.classList.toggle('bg-red-500', newStatus === 'inactive');
                        } else {
                            showNotification('Erreur lors de la mise à jour du statut.', 'bg-red-500');
                        }
                    })
                    .catch(error => console.error('Erreur:', error));
            });
        });

        // Pagination dynamique
        function paginateTable(rowsPerPage = 10) {
            let rows = document.querySelectorAll('#userTable tr');
            let totalPages = Math.ceil(rows.length / rowsPerPage);
            let pagination = document.getElementById('pagination');
            pagination.innerHTML = '';

            function showPage(page) {
                rows.forEach((row, index) => {
                    row.style.display = (index >= (page - 1) * rowsPerPage && index < page * rowsPerPage) ? '' : 'none';
                });

                document.querySelectorAll("#pagination button").forEach(btn => btn.classList.remove("bg-gray-500", "text-white"));
                document.querySelector(`#pagination button:nth-child(${page})`).classList.add("bg-gray-500", "text-white");
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
    });
</script>