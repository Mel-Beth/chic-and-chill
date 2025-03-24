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
    .active-tab {
        background-color: #e2e8f0;
    }
    .details-row {
        background-color: #f9fafb;
    }
    .details-content {
        padding: 1.5rem;
        border-top: 1px solid #e5e7eb;
    }
    .details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr; /* Trois colonnes de largeur égale */
        gap: 2rem; /* Espacement entre les colonnes */
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
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .details-section h4 svg {
        width: 1.25rem;
        height: 1.25rem;
        color: #4b5563;
    }
    .details-section .fields-grid {
        display: grid;
        grid-template-columns: 150px 1fr; /* Colonne pour le label (fixe) et colonne pour la valeur (flexible) */
        gap: 0.5rem;
        align-items: start;
    }
    .details-section .field-label {
        color: #374151;
        font-weight: 600;
        text-align: left;
    }
    .details-section .field-value {
        color: #4b5563;
        text-align: left;
        word-break: break-word; /* Pour éviter que les longues valeurs (comme l'adresse) débordent */
    }
</style>

<div class="min-h-screen flex flex-col lg:pl-64 mt-12">
    <div class="container mx-auto px-6 py-8 flex-grow">
        <div id="notification" class="hidden fixed top-0 right-0 m-4 p-4 bg-green-500 text-white rounded-md"></div>
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">🎟️ Gestion des Réservations</h2>
        </div>

        <div class="flex justify-between mb-4">
            <input id="search" type="text" placeholder="Rechercher une réservation..." class="border px-4 py-2 rounded-md w-1/3 focus:ring focus:ring-[#8B5A2B]">
            <div class="flex space-x-4">
                <button id="exportBtn" class="border px-4 py-2 rounded-md hover:bg-gray-100">Exporter</button>
                <select id="filterStatus" class="border px-4 py-2 rounded-md">
                    <option value="all">Tous les statuts</option>
                    <option value="pending">pending</option>
                    <option value="confirmed">Confirmées</option>
                    <option value="cancelled">Annulées</option>
                </select>
            </div>
        </div>

        <table class="w-full border-collapse border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-3">Nom</th>
                    <th class="border p-3">Email</th>
                    <th class="border p-3">Téléphone</th>
                    <th class="border p-3">Type</th>
                    <th class="border p-3">ID</th>
                    <th class="border p-3">Statut</th>
                    <th class="border p-3">Actions</th>
                </tr>
            </thead>
            <tbody id="reservationTable">
                <?php if (!empty($reservations)) : ?>
                    <?php foreach ($reservations as $res) : ?>
                        <tr class="hover:bg-gray-100 reservation-row" data-status="<?= htmlspecialchars($res['status']) ?>" data-type="<?= htmlspecialchars($res['type']) ?>" data-id="<?= htmlspecialchars($res['id']) ?>">
                            <td class="border p-3"><?= htmlspecialchars($res['customer_name']) ?></td>
                            <td class="border p-3"><?= htmlspecialchars($res['email']) ?></td>
                            <td class="border p-3"><?= htmlspecialchars($res['phone']) ?></td>
                            <td class="border p-3">
                                <span class="px-3 py-1 rounded-full text-white text-xs font-bold <?= $res['type'] === 'event' ? 'bg-blue-500' : 'bg-green-500' ?>">
                                    <?= $res['type'] === 'event' ? 'Événement' : 'Pack' ?>
                                </span>
                            </td>
                            <td class="border p-3"><?= htmlspecialchars($res['id']) ?></td>
                            <td class="border p-3">
                                <button class="px-3 py-1 rounded-md text-white text-xs font-bold
                                    <?php
                                    if ($res['status'] === 'confirmed') {
                                        echo 'bg-green-500';
                                    } elseif ($res['status'] === 'cancelled') {
                                        echo 'bg-red-500';
                                    } else {
                                        echo 'bg-yellow-500';
                                    }
                                    ?>"
                                    data-id="<?= $res['id'] ?>"
                                    data-status="<?= $res['status'] ?>">
                                    <?= $res['status'] === 'confirmed' ? '✅ Confirmé' : ($res['status'] === 'cancelled' ? '❌ Annulé' : '🟡 pending') ?>
                                </button>
                            </td>
                            <td class="border p-3">
                                <div class="flex space-x-4">
                                    <button class="text-blue-600 font-semibold hover:underline viewReservationBtn" data-id="<?= $res['id'] ?>" data-type="<?= $res['type'] ?>">👁️ Voir la réservation</button>
                                    <?php if ($res['status'] === 'pending') : ?>
                                        <a href="admin/reservations/modifier/<?= $res['id'] ?>?status=confirmed&type=<?= $res['type'] ?>" class="text-green-600 font-semibold hover:underline">✅ Accepter</a>
                                        <a href="admin/reservations/modifier/<?= $res['id'] ?>?status=cancelled&type=<?= $res['type'] ?>" class="text-red-600 font-semibold hover:underline">❌ Refuser</a>
                                    <?php elseif ($res['status'] === 'confirmed') : ?>
                                        <a href="admin/reservations/facture/<?= $res['id'] ?>" target="_blank" class="text-blue-600 font-semibold hover:underline">🧾 Voir la facture</a>
                                        <a href="admin/reservations/modifier/<?= $res['id'] ?>?status=cancelled&type=<?= $res['type'] ?>" class="text-red-600 font-semibold hover:underline">❌ Annuler</a>
                                    <?php else: ?>
                                        <button class="text-red-800 font-semibold hover:underline deleteReservationBtn" data-id="<?= $res['id'] ?>">Supprimer</button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <!-- Ligne cachée pour les détails, qui sera remplie dynamiquement -->
                        <tr class="details-row hidden" id="details-<?= $res['id'] ?>">
                            <td colspan="7" class="details-content">
                                <!-- Les détails seront insérés ici par JavaScript -->
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">Aucune réservation trouvée.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="mt-4 flex justify-center space-x-2" id="pagination"></div>
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full">
        <h3 class="text-xl font-bold text-gray-800 mb-4">⚠️ Confirmer l'action</h3>
        <p class="text-gray-600">Voulez-vous vraiment effectuer cette action sur la réservation ?<br>(Cette action peut être irréversible)</p>
        <div class="flex justify-between mt-4">
            <button id="cancelDelete" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 flex items-center">❌ Annuler</button>
            <button id="confirmDelete" class="bg-red-600 text-white px-6 py-3 rounded-md hover:scale-105 transition flex items-center">✅ Confirmer</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let deleteResId = null;

        function showNotification(message, bgColor = 'bg-green-500') {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.classList.remove('hidden', 'bg-red-500', 'bg-green-500');
            notification.classList.add(bgColor);
            setTimeout(() => notification.classList.add('hidden'), 3000);
        }

        // Gestion de la suppression
        document.querySelectorAll('.deleteReservationBtn').forEach(button => {
            button.addEventListener('click', function() {
                deleteResId = this.dataset.id;
                document.getElementById('deleteModal').classList.remove('hidden');
            });
        });

        document.getElementById('cancelDelete').addEventListener('click', function() {
            document.getElementById('deleteModal').classList.add('hidden');
            deleteResId = null;
        });

        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (!deleteResId) return;
            fetch(`admin/reservations/supprimer/${deleteResId}`, { method: 'DELETE' })
                .then(response => {
                    if (response.ok) {
                        const row = document.querySelector(`[data-id="${deleteResId}"]`);
                        if (row) row.remove();
                        showNotification('Réservation supprimée avec succès.');
                    } else {
                        showNotification('Erreur lors de la suppression de la réservation.', 'bg-red-500');
                    }
                    document.getElementById('deleteModal').classList.add('hidden');
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showNotification('Une erreur est survenue.', 'bg-red-500');
                    document.getElementById('deleteModal').classList.add('hidden');
                });
        });

        // Recherche dynamique
        document.getElementById('search').addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#reservationTable tr.reservation-row');
            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                let detailsRow = document.getElementById(`details-${row.dataset.id}`);
                row.style.display = text.includes(filter) ? '' : 'none';
                if (detailsRow) detailsRow.style.display = text.includes(filter) ? '' : 'none';
            });
        });

        // Filtrage par statut
        document.getElementById('filterStatus').addEventListener('change', function() {
            let filter = this.value;
            let rows = document.querySelectorAll('#reservationTable tr.reservation-row');
            rows.forEach(row => {
                let status = row.getAttribute('data-status');
                let detailsRow = document.getElementById(`details-${row.dataset.id}`);
                row.style.display = (filter === 'all' || status === filter) ? '' : 'none';
                if (detailsRow) detailsRow.style.display = (filter === 'all' || status === filter) ? '' : 'none';
            });
        });

        // Gestion de l'affichage des détails de la réservation
        document.querySelectorAll('.viewReservationBtn').forEach(button => {
            button.addEventListener('click', function() {
                const resId = this.dataset.id;
                const resType = this.dataset.type;
                const detailsRow = document.getElementById(`details-${resId}`);
                const detailsContent = detailsRow.querySelector('.details-content');

                // Si les détails sont déjà affichés, on les masque
                if (!detailsRow.classList.contains('hidden')) {
                    detailsRow.classList.add('hidden');
                    this.textContent = '👁️ Voir la réservation';
                    return;
                }

                // Sinon, on affiche les détails
                const reservation = <?php echo json_encode($reservations); ?>.find(res => res.id == resId && res.type === resType);
                if (reservation) {
                    let detailsHtml = '<div class="details-grid">';

                    // Section : Informations du client (à gauche)
                    detailsHtml += '<div class="details-section">';
                    detailsHtml += '<h4><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg> Informations du client</h4>';
                    detailsHtml += '<div class="fields-grid">';
                    detailsHtml += `<div class="field-label">Type de client :</div><div class="field-value">${reservation.customer_type}</div>`;
                    if (reservation.customer_type === 'entreprise') {
                        detailsHtml += `<div class="field-label">Nom de l'entreprise :</div><div class="field-value">${reservation.company_name || 'Non spécifié'}</div>`;
                        detailsHtml += `<div class="field-label">SIRET :</div><div class="field-value">${reservation.siret || 'Non spécifié'}</div>`;
                        detailsHtml += `<div class="field-label">Adresse :</div><div class="field-value">${reservation.address || 'Non spécifié'}</div>`;
                    }
                    detailsHtml += `<div class="field-label">Nom :</div><div class="field-value">${reservation.customer_name}</div>`;
                    detailsHtml += `<div class="field-label">Email :</div><div class="field-value">${reservation.email}</div>`;
                    detailsHtml += `<div class="field-label">Téléphone :</div><div class="field-value">${reservation.phone}</div>`;
                    detailsHtml += '</div>';
                    detailsHtml += '</div>';

                    // Section : Détails de la réservation (au milieu)
                    detailsHtml += '<div class="details-section">';
                    detailsHtml += '<h4><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg> Détails de la réservation</h4>';
                    detailsHtml += '<div class="fields-grid">';
                    if (reservation.type === 'event') {
                        detailsHtml += `<div class="field-label">Type d'événement :</div><div class="field-value">${reservation.event_type}</div>`;
                        detailsHtml += `<div class="field-label">Nombre de participants :</div><div class="field-value">${reservation.participants || 'Non spécifié'}</div>`;
                    } else if (reservation.type === 'pack') {
                        detailsHtml += `<div class="field-label">ID du pack :</div><div class="field-value">${reservation.event_id || 'Non spécifié'}</div>`;
                        detailsHtml += `<div class="field-label">Nom du pack : </div><div class="field-value">${reservation.title || 'Non spécifié'}</div>`;
                    }
                    detailsHtml += '</div>';
                    detailsHtml += '</div>';

                    // Section : Informations supplémentaires (à droite)
                    detailsHtml += '<div class="details-section">';
                    detailsHtml += '<h4><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg> Informations supplémentaires</h4>';
                    detailsHtml += '<div class="fields-grid">';
                    detailsHtml += `<div class="field-label">Services :</div><div class="field-value">${reservation.services || 'Aucun'}</div>`;
                    detailsHtml += `<div class="field-label">Commentaires :</div><div class="field-value">${reservation.comments || 'Aucun'}</div>`;
                    detailsHtml += `<div class="field-label">Date de création :</div><div class="field-value">${reservation.created_at}</div>`;
                    detailsHtml += '</div>';
                    detailsHtml += '</div>';

                    detailsHtml += '</div>';

                    detailsContent.innerHTML = detailsHtml;
                    detailsRow.classList.remove('hidden');
                    this.textContent = '👁️ Masquer la réservation';
                }
            });
        });

        // Pagination
        function paginateTable(rowsPerPage = 10) {
            let rows = document.querySelectorAll('#reservationTable tr.reservation-row');
            let totalPages = Math.ceil(rows.length / rowsPerPage);
            let pagination = document.getElementById('pagination');
            pagination.innerHTML = '';

            function showPage(page) {
                rows.forEach((row, index) => {
                    let detailsRow = document.getElementById(`details-${row.dataset.id}`);
                    row.style.display = (index >= (page - 1) * rowsPerPage && index < page * rowsPerPage) ? '' : 'none';
                    if (detailsRow) detailsRow.style.display = (index >= (page - 1) * rowsPerPage && index < page * rowsPerPage) ? '' : 'none';
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