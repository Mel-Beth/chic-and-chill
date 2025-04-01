<?php
include('src/app/Views/includes/admin_head.php');
include('src/app/Views/includes/admin_header.php');
include('src/app/Views/includes/admin_sidebar.php');
?>

<style>
    /* Style pour la notification (comme dans admin_user) */
    #notification {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    }

    /* Pour styliser l'onglet actif */
    .active-tab {
        background-color: #e2e8f0;
        /* bg-gray-200 */
    }
</style>

<div class="min-h-screen flex flex-col lg:pl-64 mt-12">
    <div class="container mx-auto px-6 py-8 flex-grow">

        <!-- Notification (succès / erreur) -->
        <div id="notification" class="hidden fixed top-0 right-0 m-4 p-4 bg-green-500 text-white rounded-md"></div>

        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">🎟️ Gestion des Réservations</h2>
        </div>

        <!-- Barre de recherche, export, filtres -->
        <div class="flex justify-between mb-4">
            <!-- Recherche -->
            <input
                id="search"
                type="text"
                placeholder="Rechercher une réservation..."
                class="border px-4 py-2 rounded-md w-1/3 focus:ring focus:ring-[#8B5A2B]">
            <div class="flex space-x-4">
                <!-- Bouton d'export -->
                <button
                    id="exportBtn"
                    class="border px-4 py-2 rounded-md hover:bg-gray-100">
                    Exporter
                </button>
                <!-- Filtre par statut -->
                <select
                    id="filterStatus"
                    class="border px-4 py-2 rounded-md">
                    <option value="all">Tous les statuts</option>
                    <option value="pending">pending</option>
                    <option value="confirmed">Confirmées</option>
                    <option value="cancelled">Annulées</option>
                </select>
            </div>
        </div>

        <!-- Tableau des réservations -->
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
                        <tr
                            class="hover:bg-gray-100 reservation-row"
                            data-status="<?= htmlspecialchars($res['status']) ?>"
                            data-type="<?= htmlspecialchars($res['type']) ?>"
                            data-id="<?= htmlspecialchars($res['id']) ?>">
                            <td class="border p-3"><?= htmlspecialchars($res['customer_name']) ?></td>
                            <td class="border p-3"><?= htmlspecialchars($res['email']) ?></td>
                            <td class="border p-3"><?= htmlspecialchars($res['phone']) ?></td>
                            <td class="border p-3">
                                <!-- Indiquez clairement "event" ou "pack" -->
                                <span class="px-3 py-1 rounded-full text-white text-xs font-bold 
                                    <?= $res['type'] === 'event' ? 'bg-blue-500' : 'bg-green-500' ?>">
                                    <?= $res['type'] === 'event' ? 'Événement' : 'Pack' ?>
                                </span>
                            </td>
                            <td class="border p-3"><?= htmlspecialchars($res['id']) ?></td>
                            <td class="border p-3">
                                <!-- Bouton pour le statut (similaire à l'approche toggleStatus) -->
                                <button
                                    class="toggleReservationStatus px-3 py-1 rounded-md text-white text-xs font-bold
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
                                    <?= $res['status'] === 'confirmed'
                                        ? '✅ Confirmé'
                                        : ($res['status'] === 'cancelled'
                                            ? '❌ Annulé'
                                            : '🟡 pending')
                                    ?>
                                </button>
                            </td>
                            <td class="border p-3">
                                <div class="flex space-x-4">
                                    <?php if ($res['status'] === 'pending') : ?>
                                        <a
                                            href="admin/reservations/modifier/<?= $res['id'] ?>?status=confirmed"
                                            class="text-green-600 font-semibold hover:underline">
                                            ✅ Accepter
                                        </a>
                                        <a href="admin/reservations/modifier/<?= $res['id'] ?>?status=cancelled" class="text-red-600 font-semibold hover:underline">
                                            ❌ Refuser
                                        </a>

                                    <?php elseif ($res['status'] === 'confirmed') : ?>
                                        <a
                                            href="admin/reservations/facture/<?= $res['id'] ?>"
                                            target="_blank"
                                            class="text-blue-600 font-semibold hover:underline">
                                            Voir la facture
                                        </a>
                                        <a href="admin/reservations/modifier/<?= $res['id'] ?>?status=cancelled" class="text-red-600 font-semibold hover:underline">
                                            Annuler
                                        </a>

                                    <?php else: ?>
                                        <!-- Ex. si la réservation est déjà annulée, vous pouvez proposer un bouton pour supprimer définitivement ? -->
                                        <button
                                            class="text-red-800 font-semibold hover:underline deleteReservationBtn"
                                            data-id="<?= $res['id'] ?>">
                                            Supprimer
                                        </button>
                                    <?php endif; ?>
                                </div>
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

        <!-- Pagination -->
        <div class="mt-4 flex justify-center space-x-2" id="pagination"></div>
    </div>
</div>

<!-- Modale pour la suppression d'une réservation -->
<div
    id="deleteModal"
    class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full">
        <h3 class="text-xl font-bold text-gray-800 mb-4">⚠️ Confirmer l'action</h3>
        <p class="text-gray-600">
            Voulez-vous vraiment effectuer cette action sur la réservation ?
            <br>(Cette action peut être irréversible)
        </p>
        <div class="flex justify-between mt-4">
            <button
                id="cancelDelete"
                class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 flex items-center">
                ❌ Annuler
            </button>
            <button
                id="confirmDelete"
                class="bg-red-600 text-white px-6 py-3 rounded-md hover:scale-105 transition flex items-center">
                ✅ Confirmer
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let deleteResId = null; // ID de la réservation à supprimer/annuler
        let showPacks = true; // Pour basculer l'affichage des packs

        // ================================================
        // 1) Notification (fonctions d'affichage)
        // ================================================
        function showNotification(message, bgColor = 'bg-green-500') {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.classList.remove('hidden', 'bg-red-500', 'bg-green-500');
            notification.classList.add(bgColor);
            notification.classList.remove('hidden');

            // On masque la notification après 3 secondes
            setTimeout(() => {
                notification.classList.add('hidden');
            }, 3000);
        }

        // ================================================
        // 2) Boutons : ouvrir la modale pour supprimer/annuler
        // ================================================
        document.querySelectorAll('.deleteReservationBtn').forEach(button => {
            button.addEventListener('click', function() {
                deleteResId = this.dataset.id; // On stocke l'ID de la réservation
                document.getElementById('deleteModal').classList.remove('hidden');
            });
        });

        // ================================================
        // 3) Modale de confirmation : annuler / confirmer
        // ================================================
        document.getElementById('cancelDelete').addEventListener('click', function() {
            document.getElementById('deleteModal').classList.add('hidden');
            deleteResId = null; // On réinitialise
        });

        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (!deleteResId) return;

            // Exemple de route : "admin/reservations/supprimer/{id}"
            // Méthode DELETE ou GET selon votre configuration
            fetch(`admin/reservations/supprimer/${deleteResId}`, {
                    method: 'DELETE' // ou 'GET'
                })
                .then(response => {
                    if (response.ok) {
                        // On retire la ligne du tableau
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

        // ================================================
        // 4) Recherche "live" dans le tableau
        // ================================================
        document.getElementById('search').addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#reservationTable tr');
            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });

        // ================================================
        // 5) Filtre par statut
        // ================================================
        document.getElementById('filterStatus').addEventListener('change', function() {
            let filter = this.value; // all | pending | confirmed | cancelled
            let rows = document.querySelectorAll('#reservationTable tr');
            rows.forEach(row => {
                let status = row.getAttribute('data-status');
                if (filter === 'all' || status === filter) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // ================================================
        // 7) Changement de statut via bouton (optionnel)
        // ================================================
        document.querySelectorAll('.toggleReservationStatus').forEach(button => {
            button.addEventListener('click', function() {
                const reservationId = this.getAttribute('data-id');
                const currentStatus = this.getAttribute('data-status');
                // Exemple : on passe de pending => confirmed => cancelled => ...
                // Ou on ouvre un dropdown, etc. Selon votre logique.
                // Ici, on fait un exemple simple : si pending, passer à confirmed, sinon passer à cancelled
                let newStatus;
                if (currentStatus === 'pending') {
                    newStatus = 'confirmed';
                } else if (currentStatus === 'confirmed') {
                    newStatus = 'cancelled';
                } else {
                    newStatus = 'pending';
                }

                // Requête AJAX pour mettre à jour le statut
                fetch(`admin/reservations/modifier/${reservationId}?status=${newStatus}`, {
                        method: 'GET'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Mise à jour du bouton
                            this.setAttribute('data-status', newStatus);
                            let btnText = newStatus === 'confirmed' ? '✅ Confirmé' : (newStatus === 'cancelled' ? '❌ Annulé' : '🟡 pending');
                            this.textContent = btnText;

                            // Couleurs
                            this.classList.remove('bg-green-500', 'bg-red-500', 'bg-yellow-500');
                            if (newStatus === 'confirmed') {
                                this.classList.add('bg-green-500');
                            } else if (newStatus === 'cancelled') {
                                this.classList.add('bg-red-500');
                            } else {
                                this.classList.add('bg-yellow-500');
                            }

                            // Mettre à jour l'attribut data-status de la ligne
                            const row = document.querySelector(`[data-id="${reservationId}"]`);
                            if (row) row.setAttribute('data-status', newStatus);

                            showNotification('Statut mis à jour avec succès.');
                        } else {
                            showNotification('Erreur lors de la mise à jour du statut.', 'bg-red-500');
                        }
                    })
                    .catch(error => console.error('Erreur:', error));
            });
        });

        // ================================================
        // 8) Pagination (comme dans admin_user)
        // ================================================
        function paginateTable(rowsPerPage = 10) {
            let rows = document.querySelectorAll('#reservationTable tr');
            let totalPages = Math.ceil(rows.length / rowsPerPage);
            let pagination = document.getElementById('pagination');
            pagination.innerHTML = '';

            function showPage(page) {
                rows.forEach((row, index) => {
                    row.style.display = (index >= (page - 1) * rowsPerPage && index < page * rowsPerPage) ?
                        '' :
                        'none';
                });

                // Mise à jour du style du bouton actif
                document.querySelectorAll("#pagination button").forEach(btn =>
                    btn.classList.remove("bg-gray-500", "text-white")
                );
                document.querySelector(`#pagination button:nth-child(${page})`)
                    .classList.add("bg-gray-500", "text-white");
            }

            for (let i = 1; i <= totalPages; i++) {
                let btn = document.createElement('button');
                btn.textContent = i;
                btn.className = "px-3 py-2 rounded-md bg-gray-300 hover:bg-gray-400";
                btn.addEventListener('click', () => showPage(i));
                pagination.appendChild(btn);
            }
            // Affiche la première page par défaut
            showPage(1);
        }
        paginateTable();
    });
</script>
