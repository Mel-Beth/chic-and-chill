<?php
include('src/app/Views/includes/admin_head.php');
include('src/app/Views/includes/admin_header.php');
include('src/app/Views/includes/admin_sidebar.php');
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
    document.addEventListener("DOMContentLoaded", function() {
        let deleteSubscriberId = null;

        // Recherche dynamique
        document.getElementById('search').addEventListener('input', function() {
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
            button.addEventListener('click', function() {
                deleteSubscriberId = this.dataset.id;
                document.getElementById('deleteModal').classList.remove('hidden');
            });
        });

        // Annuler la suppression
        document.getElementById('cancelDelete').addEventListener('click', function() {
            document.getElementById('deleteModal').classList.add('hidden');
        });

        // Confirmer la suppression
        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (deleteSubscriberId) {
                fetch(`admin/newsletter/supprimer/${deleteSubscriberId}`, {
                        method: 'DELETE'
                    })
                    .then(response => {
                        if (response.ok) {
                            document.querySelector(`button[data-id="${deleteSubscriberId}"]`).closest('tr').remove(); // Retire l'utilisateur de la table
                            showNotification('Email supprimé avec succès.', 'bg-green-500'); // Message de confirmation
                        } else {
                            showNotification('Erreur lors de la suppression de l\'Email.', 'bg-red-500'); // Message d'erreur
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

        // Exporter la liste des abonnés
        document.getElementById('exportBtn').addEventListener('click', function() {
            let csv = "Email, Date d'inscription\n";
            document.querySelectorAll("#newsletterTable tr").forEach(row => {
                let cells = row.querySelectorAll("td");
                if (cells.length > 0) {
                    csv += `${cells[0].textContent},${cells[1].textContent}\n`;
                }
            });

            let blob = new Blob([csv], {
                type: 'text/csv'
            });
            let a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = "abonnés_newsletter.csv";
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        });
    });
</script>