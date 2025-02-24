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
        <!-- En-tête -->

        <!-- Notification -->
        <div id="notification" class="hidden fixed top-0 right-0 m-4 p-4 bg-green-500 text-white rounded-md"></div>

        <?php if (isset($success)): ?>
            <?php if ($success == 1): ?>
                <?php if (isset($_GET['action']) && $_GET['action'] == 'add'): ?>
                    <div id="successMessage" class="bg-green-500 text-white p-3 rounded-md mb-4">
                        ✅ Événement ajouté avec succès !
                    </div>
                <?php elseif (isset($_GET['action']) && $_GET['action'] == 'update'): ?>
                    <div id="successMessage" class="bg-green-500 text-white p-3 rounded-md mb-4">
                        ✅ Événement modifié avec succès !
                    </div>

                <?php endif; ?>
            <?php elseif ($success == 0): ?>
                <?php if (isset($_GET['action']) && $_GET['action'] == 'add'): ?>
                    <div id="successMessage" class="bg-red-500 text-white p-3 rounded-md mb-4">
                        ❌ Une erreur est survenue lors de l'ajout de l'événement. Veuillez réessayer.
                    </div>
                <?php elseif (isset($_GET['action']) && $_GET['action'] == 'update'): ?>
                    <div id="successMessage" class="bg-red-500 text-white p-3 rounded-md mb-4">
                        ❌ Une erreur est survenue lors de la modification de l'événement. Veuillez réessayer.
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>

        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">📅 Gestion des Événements</h2>
            <button id="openModal" class="bg-black text-white px-5 py-2 rounded-lg flex items-center space-x-2 hover:bg-gray-800 transition">
                <span class="material-icons">add</span>
                <span>Ajouter un événement</span>
            </button>
        </div>

        <!-- Recherche et Export -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex justify-between mb-4">
                <input id="search" type="text" placeholder="Rechercher un événement..." class="border px-4 py-2 rounded-md w-1/3 focus:ring focus:ring-[#8B5A2B]">
                <div class="flex space-x-4">
                    <div class="relative">
                        <button id="exportBtn" class="border px-4 py-2 rounded-md hover:bg-gray-100">Exporter</button>
                        <div id="exportOptions" class="hidden absolute mt-2 bg-white border rounded shadow-md">
                            <a href="admin/export/csv" class="block px-4 py-2 hover:bg-gray-200">CSV</a>
                            <a href="admin/export/pdf" class="block px-4 py-2 hover:bg-gray-200">PDF</a>
                            <a href="admin/export/excel" class="block px-4 py-2 hover:bg-gray-200">Excel</a>
                        </div>
                    </div>
                    <select id="sort" class="border px-4 py-2 rounded-md">
                        <option value="title">Trier par Titre</option>
                        <option value="date">Trier par Date</option>
                        <option value="status">Trier par Statut</option>
                    </select>
                </div>
            </div>

            <!-- Liste des événements -->
            <table class="w-full border-collapse border">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-3">Titre</th>
                        <th class="border p-3">Date</th>
                        <th class="border p-3">Statut</th>
                        <th class="border p-3">Actions</th>
                    </tr>
                </thead>
                <tbody id="eventTable">
                    <?php foreach ($events as $event) : ?>
                        <tr class="hover:bg-gray-100">
                            <td class="border p-3"> <?= htmlspecialchars($event['title']) ?> </td>
                            <td class="border p-3"> <?= htmlspecialchars($event['date_event']) ?> </td>
                            <td class="border p-3">
                                <span class="px-3 py-1 rounded-full text-white text-xs font-bold <?= $event['status'] == 'active' ? 'bg-green-500' : 'bg-red-500' ?>">
                                    <?= htmlspecialchars($event['status']) ?>
                                </span>
                            </td>
                            <td class="border p-3">
                                <button class="edit-event text-blue-600 hover:underline"
                                    data-id="<?= $event['id'] ?>"
                                    data-title="<?= htmlspecialchars($event['title']) ?>"
                                    data-description="<?= htmlspecialchars($event['description']) ?>"
                                    data-date="<?= $event['date_event'] ?>"
                                    data-status="<?= $event['status'] ?>">
                                    Modifier
                                </button>
                                <button class="text-red-600 font-semibold hover:underline deleteEventBtn" data-id="<?= $event['id'] ?>">❌ Supprimer</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="mt-4 flex justify-center space-x-2" id="pagination"></div>
        </div>
    </div>

    <!-- Modale ajout et modification -->
    <div id="eventModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-lg w-full relative">
            <h3 id="modalTitle" class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <span id="modalIcon" class="material-icons text-purple-600 mr-2">add_circle</span> Ajouter un événement
            </h3>
            <form id="eventForm" action="admin/evenements/ajouter" method="POST">
                <input type="hidden" id="eventId" name="id"> <!-- Ajouté pour la modification -->
                <input type="text" id="eventTitle" name="title" placeholder="Titre de l'événement" required class="w-full p-3 border border-gray-300 rounded-md mb-2">
                <textarea id="eventDescription" name="description" placeholder="Description" required class="w-full p-3 border border-gray-300 rounded-md mb-2"></textarea>
                <input type="date" id="eventDate" name="date_event" required class="w-full p-3 border border-gray-300 rounded-md mb-2">
                <select id="eventStatus" name="status" class="w-full p-3 border border-gray-300 rounded-md mb-2">
                    <option value="active">Actif</option>
                    <option value="inactive">Inactif</option>
                </select>
                <div class="flex justify-between mt-4">
                    <button type="button" id="closeModal" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                        ❌ Annuler
                    </button>
                    <button type="submit" id="submitButton" class="bg-[#8B5A2B] text-white px-6 py-3 rounded-md hover:scale-105 transition">
                        ✅ Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modale pour la suppression -->
<div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full">
        <h3 class="text-xl font-bold text-gray-800 mb-4">⚠️ Confirmer la suppression</h3>
        <p class="text-gray-600">Voulez-vous vraiment supprimer cet évènement ? Cette action est irréversible.</p>
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
        let deleteEventId = null;

        // Suppression des utilisateurs avec confirmation
        document.querySelectorAll('.deleteEventBtn').forEach(button => {
            button.addEventListener('click', function() {
                deleteEventId = this.dataset.id; // Stocke l'ID de l'utilisateur
                document.getElementById('deleteModal').classList.remove('hidden'); // Affiche la modal
            });
        });

        // Annuler la suppression
        document.getElementById('cancelDelete').addEventListener('click', function() {
            document.getElementById('deleteModal').classList.add('hidden'); // Masque la modal
            deleteEventId = null; // Réinitialise l'ID
        });

        // Confirmer la suppression
        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (deleteEventId) {
                fetch(`admin/evenements/supprimer/${deleteEventId}`, {
                        method: 'DELETE'
                    })
                    .then(response => {
                        if (response.ok) {
                            document.querySelector(`button[data-id="${deleteEventId}"]`).closest('tr').remove(); // Retire l'utilisateur de la table
                            showNotification('Évènement supprimé avec succès.', 'bg-green-500'); // Message de confirmation
                        } else {
                            showNotification('Erreur lors de la suppression de l\'évènement.', 'bg-red-500'); // Message d'erreur
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
    });

    // Recherche dynamique
    document.getElementById('search').addEventListener('input', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#eventTable tr');
        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });

    // Tri des événements
    document.getElementById('sort').addEventListener('change', function() {
        let rows = Array.from(document.querySelectorAll('#eventTable tr'));
        let sortType = this.value;
        rows.sort((a, b) => {
            let valA = a.cells[sortType === 'title' ? 0 : sortType === 'date' ? 1 : 2].textContent.toLowerCase();
            let valB = b.cells[sortType === 'title' ? 0 : sortType === 'date' ? 1 : 2].textContent.toLowerCase();
            return valA.localeCompare(valB);
        });
        document.getElementById('eventTable').append(...rows);
    });

    // Exportation
    document.getElementById('exportBtn').addEventListener('click', function() {
        document.getElementById('exportOptions').classList.toggle('hidden');
    });

    // Gestion de la modale
    document.addEventListener('DOMContentLoaded', function() {
        const eventModal = document.getElementById("eventModal");
        const closeModal = document.getElementById("closeModal");
        const openModalButton = document.getElementById("openModal");
        const eventForm = document.getElementById("eventForm");
        const modalTitle = document.getElementById("modalTitle");
        const modalIcon = document.getElementById("modalIcon");
        const submitButton = document.getElementById("submitButton");

        // Ouvrir le modal pour ajouter un événement
        openModalButton.addEventListener("click", () => {
            eventForm.reset(); // Réinitialiser le formulaire
            eventForm.action = "admin/evenements/ajouter"; // Action pour ajouter
            modalTitle.textContent = "Ajouter un événement";
            modalIcon.textContent = "add_circle";
            submitButton.textContent = "✅ Ajouter";
            eventModal.classList.remove("hidden");
        });

        // Ouvrir le modal pour modifier un événement
        document.querySelectorAll(".edit-event").forEach(button => {
            button.addEventListener("click", function() {
                let eventId = this.getAttribute("data-id");

                document.getElementById("eventId").value = eventId;
                document.getElementById("eventTitle").value = this.getAttribute("data-title");
                document.getElementById("eventDescription").value = this.getAttribute("data-description");
                document.getElementById("eventDate").value = this.getAttribute("data-date");
                document.getElementById("eventStatus").value = this.getAttribute("data-status");

                // Corriger l'action du formulaire en ajoutant correctement l'ID
                document.getElementById("eventForm").action = `admin/evenements/modifier/${eventId}`;

                document.getElementById("modalTitle").textContent = "Modifier un événement";
                document.getElementById("submitButton").textContent = "✅ Mettre à jour";
                document.getElementById("eventModal").classList.remove("hidden");
            });
        });

        // Fermer le modal
        closeModal.addEventListener("click", () => {
            eventModal.classList.add("hidden");
        });
    });

    // Pagination JavaScript
    function paginateTable(rowsPerPage = 10) {
        let rows = document.querySelectorAll('#eventTable tr');
        let totalPages = Math.ceil(rows.length / rowsPerPage);
        let pagination = document.getElementById('pagination');
        pagination.innerHTML = '';

        function showPage(page) {
            rows.forEach((row, index) => {
                row.style.display = index >= (page - 1) * rowsPerPage && index < page * rowsPerPage ? '' : 'none';
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
</script>