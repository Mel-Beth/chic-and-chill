<?php
// Inclusion des fichiers de structure pour la page admin
include('src/app/Views/includes/admin/admin_head.php');   // Contient les métadonnées et les scripts/styles de base
include('src/app/Views/includes/admin/admin_header.php'); // En-tête de la page admin (navbar, etc.)
include('src/app/Views/includes/admin/admin_sidebar.php'); // Barre latérale avec navigation admin
?>

<style>
    /* Styles pour la notification flottante */
    #notification {
        position: fixed;
        /* Position fixe pour rester visible lors du défilement */
        top: 20px;
        /* Distance du haut */
        right: 20px;
        /* Distance du bord droit */
        z-index: 9999;
        /* Assure que la notification est au-dessus des autres éléments */
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        /* Ombre pour effet de profondeur */
    }

    /* Style pour l'onglet actif (non utilisé dans ce code, mais défini) */
    .active-tab {
        background-color: #e2e8f0;
        /* Couleur de fond pour un onglet actif */
    }

    /* Style pour la ligne de détails */
    .details-row {
        background-color: #f9fafb;
        /* Couleur de fond légère pour les détails */
    }

    /* Conteneur des détails */
    .details-content {
        padding: 1.5rem;
        /* Espacement interne */
        border-top: 1px solid #e5e7eb;
        /* Bordure supérieure pour séparation */
    }

    /* Grille à trois colonnes pour organiser les détails */
    .details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        /* Trois colonnes de largeur égale */
        gap: 2rem;
        /* Espacement entre les colonnes */
    }

    /* Style pour chaque section de détails */
    .details-section {
        padding: 1rem;
        /* Espacement interne */
        border: 1px solid #e5e7eb;
        /* Bordure légère */
        border-radius: 0.5rem;
        /* Coins arrondis */
        background-color: #ffffff;
        /* Fond blanc */
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        /* Ombre subtile */
    }

    /* Style pour les titres des sections */
    .details-section h4 {
        font-size: 1.25rem;
        /* Taille de police */
        font-weight: 600;
        /* Gras */
        margin-bottom: 1rem;
        /* Espacement inférieur */
        color: #1f2937;
        /* Couleur gris foncé */
        display: flex;
        align-items: center;
        /* Alignement vertical des éléments */
        gap: 0.5rem;
        /* Espacement entre icône et texte */
    }

    /* Style pour les icônes SVG dans les titres */
    .details-section h4 svg {
        width: 1.25rem;
        /* Largeur de l'icône */
        height: 1.25rem;
        /* Hauteur de l'icône */
        color: #4b5563;
        /* Couleur gris moyen */
    }

    /* Grille pour les champs (label et valeur) */
    .details-section .fields-grid {
        display: grid;
        grid-template-columns: 150px 1fr;
        /* Colonne fixe pour le label, flexible pour la valeur */
        gap: 0.5rem;
        /* Espacement entre les éléments */
        align-items: start;
        /* Alignement en haut */
    }

    /* Style pour les labels des champs */
    .details-section .field-label {
        color: #374151;
        /* Couleur gris foncé */
        font-weight: 600;
        /* Gras */
        text-align: left;
        /* Alignement à gauche */
    }

    /* Style pour les valeurs des champs */
    .details-section .field-value {
        color: #4b5563;
        /* Couleur gris moyen */
        text-align: left;
        /* Alignement à gauche */
        word-break: break-word;
        /* Coupe les mots longs pour éviter le débordement */
    }

    /* Conteneur du tableau avec défilement horizontal */
    .table-container {
        overflow-x: auto;
        /* Active le défilement horizontal si le tableau dépasse */
        -webkit-overflow-scrolling: touch;
        /* Améliore le défilement sur les appareils mobiles */
    }

    /* Largeur minimale pour le tableau */
    table {
        min-width: 800px;
        /* Assure une largeur minimale pour éviter la compression excessive */
    }

    /* Largeur minimale et gestion du texte pour les colonnes */
    th,
    td {
        min-width: 120px;
        /* Largeur minimale pour chaque colonne */
        white-space: nowrap;
        /* Empêche le texte de se couper sur plusieurs lignes */
    }

    /* Ajustement des images dans les colonnes */
    td img {
        max-width: 100%;
        /* Limite la largeur de l'image à la cellule */
        height: auto;
        /* Conserve les proportions de l'image */
    }
</style>

<!-- Conteneur principal avec marge pour la sidebar et espacement -->
<div class="min-h-screen flex flex-col lg:pl-64 mt-12">
    <div class="container mx-auto px-6 py-8 flex-grow">
        <!-- Élément de notification dynamique -->
        <div id="notification" class="hidden fixed top-0 right-0 m-4 p-4 bg-green-500 text-white rounded-md"></div>

        <!-- En-tête de la section -->
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">🎟️ Gestion des Réservations</h2>
        </div>

        <!-- Section de recherche et filtrage -->
        <div class="flex justify-between mb-4">
            <!-- Champ de recherche -->
            <input id="search" type="text" placeholder="Rechercher une réservation..." class="border px-4 py-2 rounded-md w-1/3 focus:ring focus:ring-[#8B5A2B]">
            <div class="flex space-x-4">
                <!-- Bouton d'exportation -->
                <button id="exportBtn" class="border px-4 py-2 rounded-md bg-blue-500 text-white hover:bg-blue-600">Exporter les données </button>
                <!-- Menu déroulant pour filtrer par statut -->
                <select id="filterStatus" class="border px-4 py-2 rounded-md">
                    <option value="">Filtrer par statut</option>
                    <option value="all">Tous</option>
                    <option value="pending">En attente</option>
                    <option value="confirmed">Confirmées</option>
                    <option value="cancelled">Annulées</option>
                </select>
            </div>
        </div>

        <!-- Tableau des réservations -->
        <div class="table-container">

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
                            <!-- Ligne principale de la réservation -->
                            <tr class="hover:bg-gray-100 reservation-row" data-status="<?= htmlspecialchars($res['status']) ?>" data-type="<?= htmlspecialchars($res['type']) ?>" data-id="<?= htmlspecialchars($res['id']) ?>">
                                <td class="border p-3"><?= htmlspecialchars($res['customer_name']) ?></td>
                                <td class="border p-3"><?= htmlspecialchars($res['email']) ?></td>
                                <td class="border p-3"><?= htmlspecialchars($res['phone']) ?></td>
                                <!-- Type avec badge coloré -->
                                <td class="border p-3">
                                    <span class="px-3 py-1 rounded-full text-white text-xs font-bold <?= $res['type'] === 'event' ? 'bg-blue-500' : 'bg-green-500' ?>">
                                        <?= $res['type'] === 'event' ? 'Événement' : 'Pack' ?>
                                    </span>
                                </td>
                                <td class="border p-3"><?= htmlspecialchars($res['id']) ?></td>
                                <!-- Statut avec bouton coloré -->
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
                                        <?= $res['status'] === 'confirmed' ? '✅ Confirmé' : ($res['status'] === 'cancelled' ? '❌ Annulé' : '🟡 En attente') ?>
                                    </button>
                                </td>
                                <!-- Actions disponibles selon le statut -->
                                <td class="border p-3">
                                    <div class="flex space-x-4">
                                        <button class="text-blue-600 font-semibold hover:underline viewReservationBtn" data-id="<?= $res['id'] ?>" data-type="<?= $res['type'] ?>">👁️ Voir la réservation</button>
                                        <?php if ($res['status'] === 'pending') : ?>
                                            <!-- Options pour accepter ou refuser une réservation en attente -->
                                            <a href="admin/reservations/modifier/<?= $res['id'] ?>?status=confirmed&type=<?= $res['type'] ?>" class="text-green-600 font-semibold hover:underline">✅ Accepter</a>
                                            <a href="admin/reservations/modifier/<?= $res['id'] ?>?status=cancelled&type=<?= $res['type'] ?>" class="text-red-600 font-semibold hover:underline">❌ Refuser</a>
                                        <?php elseif ($res['status'] === 'confirmed') : ?>
                                            <!-- Options pour une réservation confirmée -->
                                            <a href="admin/reservations/facture/<?= $res['id'] ?>" target="_blank" class="text-blue-600 font-semibold hover:underline">🧾 Voir la facture</a>
                                            <a href="admin/reservations/modifier/<?= $res['id'] ?>?status=cancelled&type=<?= $res['type'] ?>" class="text-red-600 font-semibold hover:underline">❌ Annuler</a>
                                        <?php else: ?>
                                            <!-- Option pour supprimer une réservation annulée -->
                                            <button class="text-red-800 font-semibold hover:underline deleteReservationBtn" data-id="<?= $res['id'] ?>">Supprimer</button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <!-- Ligne cachée pour les détails, remplie dynamiquement via JS -->
                            <tr class="details-row hidden" id="details-<?= $res['id'] ?>">
                                <td colspan="7" class="details-content">
                                    <!-- Contenu inséré par JavaScript -->
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <!-- Message si aucune réservation n'est trouvée -->
                        <tr>
                            <td colspan="7" class="text-center py-4">Aucune réservation trouvée.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- Conteneur pour la pagination -->
        <div class="mt-4 flex justify-center space-x-2" id="pagination"></div>
    </div>
</div>

<!-- Modal de confirmation pour la suppression -->
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

</body>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let deleteResId = null; // Variable pour stocker l'ID de la réservation à supprimer

        // Fonction pour afficher une notification temporaire
        function showNotification(message, bgColor = 'bg-green-500') {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.classList.remove('hidden', 'bg-red-500', 'bg-green-500'); // Réinitialise les classes
            notification.classList.add(bgColor); // Applique la couleur spécifiée
            setTimeout(() => notification.classList.add('hidden'), 3000); // Masque après 3 secondes
        }

        // Exportation des données en CSV
        document.getElementById('exportBtn').addEventListener('click', function() {
            let csv = "Nom,Email,Téléphone,Type,ID,Statut\n"; // En-tête du CSV
            document.querySelectorAll("#reservationTable tr.reservation-row").forEach(row => {
                let cells = row.querySelectorAll("td");
                if (cells.length > 0) {
                    csv += `${cells[0].textContent},${cells[1].textContent},${cells[2].textContent},${cells[3].querySelector('span').textContent},${cells[4].textContent},${cells[5].querySelector('button').textContent}\n`;
                }
            });

            // Création et téléchargement du fichier CSV
            let blob = new Blob([csv], {
                type: 'text/csv'
            });
            let a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = "reservations_export.csv";
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            showNotification('Exportation réussie !', 'bg-green-500');
        });

        // Gestion des boutons "Supprimer" avec ouverture du modal
        document.querySelectorAll('.deleteReservationBtn').forEach(button => {
            button.addEventListener('click', function() {
                deleteResId = this.dataset.id; // Stocke l'ID de la réservation
                document.getElementById('deleteModal').classList.remove('hidden'); // Affiche le modal
            });
        });

        // Annuler la suppression
        document.getElementById('cancelDelete').addEventListener('click', function() {
            document.getElementById('deleteModal').classList.add('hidden'); // Masque le modal
            deleteResId = null; // Réinitialise l'ID
        });

        // Confirmer la suppression via une requête FETCH
        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (!deleteResId) return;
            fetch(`admin/reservations/supprimer/${deleteResId}`, {
                    method: 'DELETE' // Requête DELETE pour supprimer la réservation
                })
                .then(response => {
                    if (response.ok) {
                        const row = document.querySelector(`[data-id="${deleteResId}"]`);
                        if (row) row.remove(); // Supprime la ligne principale
                        const detailsRow = document.getElementById(`details-${deleteResId}`);
                        if (detailsRow) detailsRow.remove(); // Supprime la ligne de détails
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

        // Recherche dynamique dans le tableau
        document.getElementById('search').addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#reservationTable tr.reservation-row');
            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                let detailsRow = document.getElementById(`details-${row.dataset.id}`);
                row.style.display = text.includes(filter) ? '' : 'none'; // Affiche ou cache la ligne principale
                if (detailsRow) detailsRow.style.display = text.includes(filter) ? '' : 'none'; // Affiche ou cache les détails
            });
        });

        // Filtrage par statut
        document.getElementById('filterStatus').addEventListener('change', function() {
            let filter = this.value;
            let rows = document.querySelectorAll('#reservationTable tr.reservation-row');
            rows.forEach(row => {
                let status = row.getAttribute('data-status');
                let detailsRow = document.getElementById(`details-${row.dataset.id}`);
                row.style.display = (filter === '' || filter === 'all' || status === filter) ? '' : 'none'; // Affiche selon le filtre
                if (detailsRow) detailsRow.style.display = (filter === '' || filter === 'all' || status === filter) ? '' : 'none';
            });
        });

        // Gestion de l'affichage des détails de la réservation
        document.querySelectorAll('.viewReservationBtn').forEach(button => {
            button.addEventListener('click', function() {
                const resId = this.dataset.id;
                const resType = this.dataset.type;
                const detailsRow = document.getElementById(`details-${resId}`);
                const detailsContent = detailsRow.querySelector('.details-content');

                // Basculer l'affichage des détails
                if (!detailsRow.classList.contains('hidden')) {
                    detailsRow.classList.add('hidden');
                    this.textContent = '👁️ Voir la réservation';
                    return;
                }

                // Récupérer les données de la réservation depuis PHP
                const reservation = <?php echo json_encode($reservations); ?>.find(res => res.id == resId && res.type === resType);
                if (reservation) {
                    let detailsHtml = '<div class="details-grid">';

                    // Section : Informations du client
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

                    // Section : Détails de la réservation
                    detailsHtml += '<div class="details-section">';
                    detailsHtml += '<h4><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg> Détails de la réservation</h4>';
                    detailsHtml += '<div class="fields-grid">';
                    if (reservation.type === 'event') {
                        detailsHtml += `<div class="field-label">Type d'événement :</div><div class="field-value">${reservation.event_type}</div>`;
                        detailsHtml += `<div class="field-label">Nombre de participants :</div><div class="field-value">${reservation.participants || 'Non spécifié'}</div>`;
                    } else if (reservation.type === 'pack') {
                        detailsHtml += `<div class="field-label">ID du pack :</div><div class="field-value">${reservation.event_id || 'Non spécifié'}</div>`;
                        detailsHtml += `<div class="field-label">Nom du pack :</div><div class="field-value">${reservation.title || 'Non spécifié'}</div>`;
                    }
                    detailsHtml += '</div>';
                    detailsHtml += '</div>';

                    // Section : Informations supplémentaires
                    detailsHtml += '<div class="details-section">';
                    detailsHtml += '<h4><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg> Informations supplémentaires</h4>';
                    detailsHtml += '<div class="fields-grid">';
                    detailsHtml += `<div class="field-label">Services :</div><div class="field-value">${reservation.services || 'Aucun'}</div>`;
                    detailsHtml += `<div class="field-label">Commentaires :</div><div class="field-value">${reservation.comments || 'Aucun'}</div>`;
                    detailsHtml += `<div class="field-label">Date de création :</div><div class="field-value">${reservation.created_at}</div>`;
                    detailsHtml += '</div>';
                    detailsHtml += '</div>';

                    detailsHtml += '</div>';

                    detailsContent.innerHTML = detailsHtml; // Insère le HTML généré
                    detailsRow.classList.remove('hidden'); // Affiche les détails
                    this.textContent = '👁️ Masquer la réservation'; // Met à jour le texte du bouton
                }
            });
        });

        // Tri initial des lignes par statut
        const tbody = document.getElementById('reservationTable');
        const rows = Array.from(tbody.querySelectorAll('tr.reservation-row'));
        const statusOrder = {
            'pending': 0, // Priorité pour "en attente"
            'confirmed': 1, // Priorité pour "confirmé"
            'cancelled': 2 // Priorité pour "annulé"
        };

        rows.sort((a, b) => {
            const statusA = a.dataset.status;
            const statusB = b.dataset.status;
            return statusOrder[statusA] - statusOrder[statusB]; // Trie selon l'ordre défini
        });

        // Réinsérer les lignes triées avec leurs détails
        rows.forEach(row => {
            tbody.appendChild(row);
            const detailsRow = document.getElementById(`details-${row.dataset.id}`);
            if (detailsRow) tbody.appendChild(detailsRow);
        });

        // Fonction pour gérer la pagination
        function paginateTable(rowsPerPage = 10) {
            let rows = document.querySelectorAll('#reservationTable tr.reservation-row');
            let totalPages = Math.ceil(rows.length / rowsPerPage);
            let pagination = document.getElementById('pagination');
            pagination.innerHTML = ''; // Réinitialise la pagination

            // Affiche une page spécifique
            function showPage(page) {
                rows.forEach((row, index) => {
                    let detailsRow = document.getElementById(`details-${row.dataset.id}`);
                    row.style.display = (index >= (page - 1) * rowsPerPage && index < page * rowsPerPage) ? '' : 'none';
                    if (detailsRow) detailsRow.style.display = (index >= (page - 1) * rowsPerPage && index < page * rowsPerPage) ? '' : 'none';
                });
                // Met en évidence le bouton de la page active
                document.querySelectorAll("#pagination button").forEach(btn => btn.classList.remove("bg-gray-500", "text-white"));
                document.querySelector(`#pagination button:nth-child(${page})`).classList.add("bg-gray-500", "text-white");
            }

            // Crée les boutons de pagination
            for (let i = 1; i <= totalPages; i++) {
                let btn = document.createElement('button');
                btn.textContent = i;
                btn.className = "px-3 py-2 rounded-md bg-gray-300 hover:bg-gray-400";
                btn.addEventListener('click', () => showPage(i));
                pagination.appendChild(btn);
            }
            showPage(1); // Affiche la première page par défaut
        }
        paginateTable(); // Initialise la pagination
    });
</script>

</html>