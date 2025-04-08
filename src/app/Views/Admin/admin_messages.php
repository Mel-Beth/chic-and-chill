<?php
include('src/app/Views/includes/admin/admin_head.php');
include('src/app/Views/includes/admin/admin_header.php');
include('src/app/Views/includes/admin/admin_sidebar.php');
?>

<style>
    #notification {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    }
    .details-row {
        background-color: #f9fafb;
    }
    .details-content {
        padding: 1.5rem;
        border-top: 1px solid #e5e7eb;
    }
    .details-section {
        padding: 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        background-color: #ffffff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    .details-section h4 {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #1f2937;
    }
    .details-section p {
        color: #4b5563;
    }
</style>

<div class="min-h-screen flex flex-col lg:pl-64 mt-12">
    <div class="container mx-auto px-6 py-8 flex-grow">
        <div id="notification" class="hidden fixed top-0 right-0 m-4 p-4 bg-green-500 text-white rounded-md"></div>
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">📩 Gestion des Messages</h2>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex justify-between mb-4">
                <input id="search" type="text" placeholder="Rechercher un message..." class="border px-4 py-2 rounded-md w-1/3 focus:ring focus:ring-[#8B5A2B]">
                <div class="flex space-x-4">
                    <select id="filterSource" class="border px-4 py-2 rounded-md">
                        <option value="">Sélectionner la source</option>
                        <option value="all">Tous</option>
                        <option value="magasin">Magasin</option>
                        <option value="location">Location</option>
                        <option value="evenements">Événements</option>
                    </select>
                    <select id="filterStatus" class="border px-4 py-2 rounded-md">
                        <option value="">Sélectionner le statut</option>
                        <option value="all">Tous</option>
                        <option value="unread">Non lus</option>
                        <option value="read">Lus</option>
                        <option value="replied">Répondus</option>
                    </select>
                </div>
            </div>

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
                        <tr class="hover:bg-gray-100 message-row <?= $message['status'] === 'unread' ? 'bg-yellow-100' : ($message['status'] === 'replied' ? 'bg-green-100' : '') ?>"
                            data-id="<?= $message['id'] ?>"
                            data-status="<?= $message['status'] ?>">
                            <td class="border p-3"><?= htmlspecialchars($message['name']) ?></td>
                            <td class="border p-3"><?= htmlspecialchars($message['email']) ?></td>
                            <td class="border p-3 truncate max-w-xs"><?= htmlspecialchars($message['message']) ?></td>
                            <td class="border p-3"><?= ucfirst(htmlspecialchars($message['source'])) ?></td>
                            <td class="border p-3">
                                <button class="toggleReadStatus px-3 py-1 rounded-md text-white text-xs font-bold 
                                    <?= $message['status'] === 'unread' ? 'bg-red-500' : ($message['status'] === 'replied' ? 'bg-blue-500' : 'bg-green-500') ?>"
                                    data-id="<?= $message['id'] ?>"
                                    data-status="<?= $message['status'] ?>">
                                    <?= $message['status'] === 'unread' ? '🔴 Non lu' : ($message['status'] === 'replied' ? '📨 Répondu' : '🟢 Lu') ?>
                                </button>
                            </td>
                            <td class="border p-3">
                                <?php if ($message['status'] === 'replied') : ?>
                                    <button class="text-blue-600 font-semibold hover:underline viewReplyBtn" data-id="<?= $message['id'] ?>">👁️ Voir la réponse</button>
                                <?php else : ?>
                                    <a href="admin/messages/reply/<?= $message['id'] ?>" class="text-blue-600 font-semibold hover:underline">✉️ Répondre</a>
                                <?php endif; ?>
                                <button class="text-red-600 font-semibold hover:underline deleteMessageBtn ml-2" data-id="<?= $message['id'] ?>">❌ Supprimer</button>
                            </td>
                        </tr>
                        <!-- Ligne pour les détails de la réponse -->
                        <tr class="details-row hidden" id="details-<?= $message['id'] ?>">
                            <td colspan="6" class="details-content">
                                <!-- Les détails seront insérés ici par JavaScript -->
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
            <button id="cancelDelete" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 flex items-center">❌ Annuler</button>
            <button id="confirmDelete" class="bg-red-600 text-white px-6 py-3 rounded-md hover:scale-105 transition flex items-center">✅ Supprimer</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let deleteMessageId = null;

        function showNotification(message, bgColor = 'bg-green-500') {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.classList.remove('hidden', 'bg-red-500', 'bg-green-500');
            notification.classList.add(bgColor);
            setTimeout(() => notification.classList.add('hidden'), 3000);
        }

        // Recherche dynamique
        document.getElementById('search').addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#messagesTable tr.message-row');
            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                let detailsRow = document.getElementById(`details-${row.dataset.id}`);
                row.style.display = text.includes(filter) ? '' : 'none';
                if (detailsRow) detailsRow.style.display = text.includes(filter) ? '' : 'none';
            });
        });

        // Filtrage par source
        document.getElementById('filterSource').addEventListener('change', function() {
            let filter = this.value;
            let rows = document.querySelectorAll('#messagesTable tr.message-row');
            rows.forEach(row => {
                let source = row.querySelector('td:nth-child(4)').textContent.trim().toLowerCase();
                row.style.display = (filter === 'all' || source === filter) ? '' : 'none';
            });
        });

        // Filtrage par statut
        document.getElementById('filterStatus').addEventListener('change', function() {
            let filter = this.value;
            let rows = document.querySelectorAll('#messagesTable tr.message-row');
            rows.forEach(row => {
                let status = row.getAttribute('data-status');
                row.style.display = (filter === 'all' || status === filter) ? '' : 'none';
            });
        });

        // Basculer le statut Lu / Non Lu
        document.querySelectorAll('.toggleReadStatus').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation(); // Empêche le clic sur la ligne
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
                        }
                    });
            });
        });

        // Afficher les détails de la réponse
        document.querySelectorAll('.viewReplyBtn').forEach(button => {
            button.addEventListener('click', function() {
                const messageId = this.dataset.id;
                const detailsRow = document.getElementById(`details-${messageId}`);
                const detailsContent = detailsRow.querySelector('.details-content');

                if (!detailsRow.classList.contains('hidden')) {
                    detailsRow.classList.add('hidden');
                    this.textContent = '👁️ Voir la réponse';
                    return;
                }

                const message = <?php echo json_encode($messages); ?>.find(m => m.id == messageId);
                if (message) {
                    let detailsHtml = '<div class="details-section">';
                    detailsHtml += '<h4>Réponse envoyée</h4>';
                    detailsHtml += `<p><strong>Date :</strong> ${message.replied_at || 'Non spécifiée'}</p>`;
                    detailsHtml += `<p><strong>Réponse :</strong> ${message.reply_body ? message.reply_body.replace(/\n/g, '<br>') : 'Aucune réponse enregistrée'}</p>`;
                    detailsHtml += '</div>';

                    detailsContent.innerHTML = detailsHtml;
                    detailsRow.classList.remove('hidden');
                    this.textContent = '👁️ Masquer la réponse';
                }
            });
        });

        // Pagination
        function paginateTable(rowsPerPage = 10) {
            let rows = document.querySelectorAll('#messagesTable tr.message-row');
            let totalPages = Math.ceil(rows.length / rowsPerPage);
            let pagination = document.getElementById('pagination');
            pagination.innerHTML = '';

            function showPage(page) {
                rows.forEach((row, index) => {
                    let detailsRow = document.getElementById(`details-${row.dataset.id}`);
                    row.style.display = (index >= (page - 1) * rowsPerPage && index < page * rowsPerPage) ? '' : 'none';
                    if (detailsRow) detailsRow.style.display = (index >= (page - 1) * rowsPerPage && index < page * rowsPerPage) ? '' : 'none';
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

        document.getElementById('cancelDelete').addEventListener('click', function() {
            document.getElementById('deleteModal').classList.add('hidden');
        });

        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (deleteMessageId) {
                fetch(`admin/messages/supprimer/${deleteMessageId}`, { method: 'DELETE' })
                    .then(response => {
                        if (response.ok) {
                            document.querySelector(`tr[data-id="${deleteMessageId}"]`).remove();
                            showNotification('Message supprimé avec succès.', 'bg-green-500');
                        } else {
                            showNotification('Erreur lors de la suppression du message.', 'bg-red-500');
                        }
                        document.getElementById('deleteModal').classList.add('hidden');
                    })
                    .catch(error => console.error('Erreur:', error));
            }
        });
    });
</script>