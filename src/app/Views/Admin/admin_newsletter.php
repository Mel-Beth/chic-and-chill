<?php 
include('src/app/Views/includes/admin_head.php');
include('src/app/Views/includes/admin_header.php');
include('src/app/Views/includes/admin_sidebar.php');
?>

<div class="min-h-screen flex flex-col lg:pl-64 mt-12">
    <div class="container mx-auto px-6 py-8 flex-grow">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">📩 Gestion de la Newsletter</h2>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <!-- Barre de recherche et filtres -->
            <div class="flex justify-between mb-4">
                <input id="search" type="text" placeholder="Rechercher un abonné..." class="border px-4 py-2 rounded-md w-1/3 focus:ring focus:ring-[#8B5A2B]">
                <div class="flex space-x-4">
                    <button id="exportBtn" class="border px-4 py-2 rounded-md hover:bg-gray-100">Exporter</button>
                </div>
            </div>

            <!-- Liste des abonnés -->
            <table class="w-full border-collapse border">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-3">Email</th>
                        <th class="border p-3">Date d'inscription</th>
                        <th class="border p-3">Actions</th>
                    </tr>
                </thead>
                <tbody id="newsletterTable">
                    <?php foreach ($subscribers as $subscriber) : ?>
                        <tr class="hover:bg-gray-100">
                            <td class="border p-3"><?= htmlspecialchars($subscriber['email']) ?></td>
                            <td class="border p-3"><?= date('d/m/Y H:i', strtotime($subscriber['created_at'])) ?></td>
                            <td class="border p-3">
                                <button class="text-red-600 font-semibold hover:underline deleteSubscriberBtn" data-id="<?= $subscriber['id'] ?>">❌ Supprimer</button>
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
        <p class="text-gray-600">Voulez-vous vraiment supprimer cet abonné ? Cette action est irréversible.</p>
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
    document.addEventListener("DOMContentLoaded", function () {
        let deleteSubscriberId = null;

        // Recherche dynamique
        document.getElementById('search').addEventListener('input', function () {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#newsletterTable tr');
            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });

        // Pagination
        function paginateTable(rowsPerPage = 10) {
            let rows = document.querySelectorAll('#newsletterTable tr');
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

        // Suppression des abonnés avec confirmation
        document.querySelectorAll('.deleteSubscriberBtn').forEach(button => {
            button.addEventListener('click', function () {
                deleteSubscriberId = this.dataset.id;
                document.getElementById('deleteModal').classList.remove('hidden');
            });
        });

        document.getElementById('cancelDelete').addEventListener('click', function () {
            document.getElementById('deleteModal').classList.add('hidden');
        });

        document.getElementById('confirmDelete').addEventListener('click', function () {
            if (deleteSubscriberId) {
                window.location.href = `admin/newsletter/supprimer/${deleteSubscriberId}`;
            }
        });

        // Exporter la liste des abonnés
        document.getElementById('exportBtn').addEventListener('click', function () {
            let csv = "Email, Date d'inscription\n";
            document.querySelectorAll("#newsletterTable tr").forEach(row => {
                let cells = row.querySelectorAll("td");
                if (cells.length > 0) {
                    csv += `${cells[0].textContent},${cells[1].textContent}\n`;
                }
            });

            let blob = new Blob([csv], { type: 'text/csv' });
            let a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = "abonnés_newsletter.csv";
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        });
    });
</script>
