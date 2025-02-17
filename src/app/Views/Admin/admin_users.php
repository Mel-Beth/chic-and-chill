<?php
include('src/app/Views/includes/admin_head.php');
include('src/app/Views/includes/admin_header.php');
include('src/app/Views/includes/admin_sidebar.php');
?>

<div class="min-h-screen flex flex-col lg:pl-64 mt-12">
    <div class="container mx-auto px-6 py-8 flex-grow">
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
                            <?php $status = $user['status'] ?? 'inactive'; ?>
                            <button class="toggleStatus px-3 py-1 rounded-md text-white text-xs font-bold 
        <?= $status === 'active' ? 'bg-green-500' : 'bg-red-500' ?> "
                                data-id="<?= $user['id'] ?>"
                                data-status="<?= $status ?>">
                                <?= $status === 'active' ? '🟢 Actif' : '🔴 Inactif' ?>
                            </button>
                        </td>
                        <td class="border p-3 flex space-x-4">
                            <button class="viewHistory text-blue-600 font-semibold hover:underline" data-id="<?= $user['id'] ?>">📜 Voir historique</button>
                            <button class="text-red-600 font-semibold hover:underline deleteUserBtn" data-id="<?= $user['id'] ?>">❌ Supprimer</button>
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