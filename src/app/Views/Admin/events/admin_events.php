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
        top: 20px;
        right: 20px;
        z-index: 9999;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        /* Ombre légère pour effet de profondeur */
    }

    /* Limite la taille de la prévisualisation d'image */
    #imagePreview {
        max-width: 50%;
        margin-top: 10px;
    }

    /* Style pour l'image actuelle dans le formulaire */
    #currentImage {
        max-width: 100px;
        max-height: 100px;
        object-fit: contain;
        /* Garde les proportions de l'image */
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    /* Styles pour le modal de gestion des événements */
    #eventModal {
        overflow-y: auto;
        /* Permet le défilement si contenu trop long */
        padding: 1rem;
    }

    #eventModal .modal-content {
        max-height: calc(100vh - 2rem);
        /* Limite la hauteur au viewport */
        overflow-y: auto;
        /* Défilement vertical si nécessaire */
    }
</style>

<!-- Conteneur principal avec marge pour la sidebar et espacement -->
<div class="min-h-screen flex flex-col lg:pl-64 mt-12">
    <div class="container mx-auto px-6 py-8 flex-grow">
        <!-- Élément de notification dynamique -->
        <div id="notification" class="hidden fixed top-0 right-0 m-4 p-4 bg-green-500 text-white rounded-md"></div>

        <!-- Affichage des messages de succès ou d'erreur après action -->
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

        <!-- En-tête de la section avec bouton pour ajouter un événement -->
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">📅 Gestion des Événements</h2>
            <button id="openModal" class="bg-black text-white px-5 py-2 rounded-lg flex items-center space-x-2 hover:bg-gray-800 transition">
                <span class="material-icons">add</span>
                <span>Ajouter un événement</span>
            </button>
        </div>

        <!-- Section de recherche, tri et exportation -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex justify-between mb-4">
                <!-- Champ de recherche -->
                <input id="search" type="text" placeholder="Rechercher un événement..." class="border px-4 py-2 rounded-md w-1/3 focus:ring focus:ring-[#8B5A2B]">
                <div class="flex space-x-4">
                    <!-- Bouton d'exportation -->
                    <div class="relative">
                        <button id="exportBtn" class="border px-4 py-2 rounded-md bg-blue-500 text-white hover:bg-blue-600">Exporter les données </button>
                    </div>
                    <!-- Menu déroulant pour le tri -->
                    <select id="sort" class="border px-4 py-2 rounded-md">
                        <option value="title">Trier par Titre</option>
                        <option value="date">Trier par Date</option>
                        <option value="location">Trier par Lieu</option>
                        <option value="status">Trier par Statut</option>
                    </select>
                </div>
            </div>

            <!-- Tableau des événements -->
            <table class="w-full border-collapse border">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-3">Image</th>
                        <th class="border p-3">Titre</th>
                        <th class="border p-3">Date</th>
                        <th class="border p-3">Lieu</th>
                        <th class="border p-3">Statut</th>
                        <th class="border p-3">Actions</th>
                    </tr>
                </thead>
                <tbody id="eventTable">
                    <?php foreach ($events as $event) : ?>
                        <tr class="hover:bg-gray-100">
                            <!-- Affichage de l'image ou texte si absente -->
                            <td class="border p-3">
                                <?php if (!empty($event['image']) && $event['image'] !== 'placeholder.jpg'): ?>
                                    <img src="assets/images/events/<?= htmlspecialchars($event['image']) ?>" alt="<?= htmlspecialchars($event['title'] ?? '') ?>" class="object-cover rounded-md" width="70" height="70">
                                <?php else: ?>
                                    <span class="text-gray-500">Pas d'image</span>
                                <?php endif; ?>
                            </td>
                            <td class="border p-3"><?= htmlspecialchars($event['title'] ?? '') ?></td>
                            <td class="border p-3"><?= htmlspecialchars($event['date_event'] ?? '') ?></td>
                            <td class="border p-3"><?= htmlspecialchars($event['location'] ?? '') ?></td>
                            <!-- Statut avec badge coloré -->
                            <td class="border p-3">
                                <span class="px-3 py-1 rounded-full text-white text-xs font-bold <?= ($event['status'] ?? '') == 'active' ? 'bg-green-500' : 'bg-red-500' ?>">
                                    <?= htmlspecialchars($event['status'] ?? '') ?>
                                </span>
                            </td>
                            <!-- Boutons d'action -->
                            <td class="border p-3 flex space-x-2">
                                <button class="edit-event text-blue-600 hover:underline"
                                    data-image="<?= htmlspecialchars($event['image'] ?? '') ?>"
                                    data-id="<?= $event['id'] ?? '' ?>"
                                    data-title="<?= htmlspecialchars($event['title'] ?? '') ?>"
                                    data-description="<?= htmlspecialchars($event['description'] ?? '') ?>"
                                    data-date="<?= htmlspecialchars($event['date_event'] ?? '') ?>"
                                    data-location="<?= htmlspecialchars($event['location'] ?? '') ?>"
                                    data-status="<?= htmlspecialchars($event['status'] ?? '') ?>">
                                    ✏️ Modifier
                                </button>
                                <button class="text-red-600 font-semibold hover:underline deleteEventBtn" data-id="<?= $event['id'] ?? '' ?>">❌ Supprimer</button>
                                <?php if (($event['status'] ?? '') === 'active'): ?>
                                    <a href="admin/evenements/configurer/<?= $event['id'] ?>" class="text-green-600 hover:underline">🎥 Média</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Conteneur pour la pagination -->
            <div class="mt-4 flex justify-center space-x-2" id="pagination"></div>
        </div>
    </div>

    <!-- Modal pour ajouter ou modifier un événement -->
    <div id="eventModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center hidden z-50">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-lg w-full relative modal-content">
            <h3 id="modalTitle" class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <span id="modalIcon" class="material-icons text-purple-600 mr-2">add_circle</span> Ajouter un événement
            </h3>
            <!-- Formulaire pour ajout/modification -->
            <form id="eventForm" action="admin/evenements/ajouter" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="eventId" name="id"> <!-- Champ caché pour l'ID lors de la modification -->
                <label for="eventImage" class="block text-sm text-gray-600 mb-2">Image de l'événement</label>
                <div class="relative">
                    <!-- Input fichier masqué avec superposition personnalisée -->
                    <input type="file" id="eventImage" name="image" class="absolute opacity-0 w-full h-full cursor-pointer" accept="image/*">
                    <div class="w-full p-3 border border-gray-300 rounded-md bg-white flex items-center justify-between cursor-pointer">
                        <span id="fileName" class="text-gray-500">Choisir une image</span>
                        <span class="text-[#8B5A2B] font-semibold">Parcourir</span>
                    </div>
                </div>
                <!-- Prévisualisation de l'image -->
                <div id="imagePreview" class="mt-2">
                    <img id="currentImage" src="" alt="Aperçu de l'image" class="hidden">
                </div>
                <label for="eventTitle" class="block text-sm text-gray-600 mb-2">Titre</label>
                <input type="text" id="eventTitle" name="title" placeholder="Titre de l'événement" required class="w-full p-3 border border-gray-300 rounded-md mb-2">
                <label for="eventDescription" class="block text-sm text-gray-600 mb-2">Description</label>
                <textarea id="eventDescription" name="description" placeholder="Description" class="w-full p-3 border border-gray-300 rounded-md mb-2"></textarea>
                <label for="eventDate" class="block text-sm text-gray-600 mb-2">Date</label>
                <input type="date" id="eventDate" name="date_event" required class="w-full p-3 border border-gray-300 rounded-md mb-2">
                <label for="eventLocation" class="block text-sm text-gray-600 mb-2">Lieu</label>
                <input type="text" id="eventLocation" name="location" placeholder="Lieu de l'événement" class="w-full p-3 border border-gray-300 rounded-md mb-2">
                <label for="eventStatus" class="block text-sm text-gray-600 mb-2">Statut</label>
                <select id="eventStatus" name="status" class="w-full p-3 border border-gray-300 rounded-md mb-2">
                    <option value="active">Actif</option>
                    <option value="inactive">Inactif</option>
                </select>
                <!-- Boutons d'action du formulaire -->
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

    <!-- Modal de confirmation pour la suppression -->
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

    </body>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Déclaration des éléments du DOM pour une meilleure lisibilité
            const eventModal = document.getElementById("eventModal"); // Modal pour ajout/modification
            const closeModal = document.getElementById("closeModal"); // Bouton de fermeture du modal
            const modalTitle = document.getElementById("modalTitle"); // Titre du modal
            const modalIcon = document.getElementById("modalIcon"); // Icône du modal
            const submitButton = document.getElementById("submitButton"); // Bouton de soumission
            const eventForm = document.getElementById("eventForm"); // Formulaire d'événement
            const openModal = document.getElementById("openModal"); // Bouton pour ouvrir le modal

            let deleteEventId = null; // Variable pour stocker l'ID de l'événement à supprimer

            // Ouvrir le modal pour ajouter un nouvel événement
            openModal.addEventListener("click", function() {
                eventForm.reset(); // Réinitialise les champs du formulaire
                eventForm.action = "admin/evenements/ajouter"; // Définit l'action pour l'ajout
                modalTitle.textContent = "Ajouter un événement";
                modalIcon.textContent = "add_circle";
                submitButton.textContent = "✅ Ajouter";
                document.getElementById("eventId").value = ""; // Pas d'ID pour un nouvel événement
                document.getElementById("currentImage").classList.add("hidden"); // Cache l'image actuelle
                document.getElementById("fileName").textContent = "Choisir une image";
                eventModal.classList.remove("hidden"); // Affiche le modal
            });

            // Gestion des boutons "Modifier" pour remplir le modal avec les données existantes
            document.querySelectorAll(".edit-event").forEach(button => {
                button.addEventListener("click", function() {
                    let eventId = this.getAttribute("data-id");
                    document.getElementById("eventId").value = eventId;
                    document.getElementById("eventTitle").value = this.getAttribute("data-title");
                    document.getElementById("eventDescription").value = this.getAttribute("data-description");
                    document.getElementById("eventDate").value = this.getAttribute("data-date");
                    document.getElementById("eventLocation").value = this.getAttribute("data-location");
                    document.getElementById("eventStatus").value = this.getAttribute("data-status");

                    // Affichage de l'image existante si elle existe
                    const imagePath = this.getAttribute("data-image");
                    const imagePreview = document.getElementById("currentImage");
                    if (imagePath && imagePath !== 'placeholder.jpg') {
                        imagePreview.src = `assets/images/events/${imagePath}`;
                        imagePreview.classList.remove("hidden");
                    } else {
                        imagePreview.classList.add("hidden");
                    }

                    eventForm.action = `admin/evenements/modifier/${eventId}`; // Action pour modification
                    modalTitle.textContent = "Modifier un événement";
                    modalIcon.textContent = "edit";
                    submitButton.textContent = "✅ Mettre à jour";
                    eventModal.classList.remove("hidden");
                });
            });

            // Fermer le modal au clic sur "Annuler"
            closeModal.addEventListener("click", () => {
                eventModal.classList.add("hidden");
            });

            // Prévisualisation de l'image sélectionnée dans le formulaire
            document.getElementById("eventImage").addEventListener("change", function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const currentImage = document.getElementById("currentImage");
                        currentImage.src = e.target.result; // Affiche l'image en base64
                        currentImage.classList.remove("hidden");
                    }
                    reader.readAsDataURL(file);
                    document.getElementById("fileName").textContent = file.name; // Met à jour le nom du fichier
                } else {
                    document.getElementById("fileName").textContent = "Choisir une image";
                }
            });

            // Gestion des boutons "Supprimer" avec ouverture du modal de confirmation
            document.querySelectorAll('.deleteEventBtn').forEach(button => {
                button.addEventListener('click', function() {
                    deleteEventId = this.dataset.id; // Stocke l'ID de l'événement à supprimer
                    document.getElementById('deleteModal').classList.remove('hidden');
                });
            });

            // Annuler la suppression
            document.getElementById('cancelDelete').addEventListener('click', function() {
                document.getElementById('deleteModal').classList.add('hidden');
                deleteEventId = null; // Réinitialise l'ID
            });

            // Confirmer la suppression via une requête FETCH
            document.getElementById('confirmDelete').addEventListener('click', function() {
                if (deleteEventId) {
                    fetch(`admin/evenements/supprimer/${deleteEventId}`, {
                            method: 'DELETE' // Requête DELETE pour supprimer l'événement
                        })
                        .then(response => {
                            if (response.ok) {
                                // Supprime la ligne du tableau si succès
                                document.querySelector(`button[data-id="${deleteEventId}"]`).closest('tr').remove();
                                showNotification('Événement supprimé avec succès.', 'bg-green-500');
                            } else {
                                showNotification('Erreur lors de la suppression de l\'événement.', 'bg-red-500');
                            }
                            document.getElementById('deleteModal').classList.add('hidden');
                        })
                        .catch(error => console.error('Erreur:', error));
                }
            });

            // Fonction pour afficher une notification temporaire
            function showNotification(message, bgColor) {
                const notification = document.getElementById('notification');
                notification.textContent = message;
                notification.classList.remove('hidden', 'bg-red-500', 'bg-green-500');
                notification.classList.add(bgColor);
                setTimeout(() => {
                    notification.classList.add('hidden'); // Cache après 3 secondes
                }, 3000);
            }

            // Masquer le message de succès/erreur après 3 secondes
            const successDiv = document.getElementById('successMessage');
            if (successDiv) {
                setTimeout(() => {
                    successDiv.style.display = 'none';
                }, 3000);
            }

            // Recherche dynamique dans le tableau
            document.getElementById('search').addEventListener('input', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#eventTable tr');
                rows.forEach(row => {
                    let text = row.textContent.toLowerCase();
                    row.style.display = text.includes(filter) ? '' : 'none'; // Affiche ou cache les lignes
                });
            });

            // Tri des lignes du tableau selon la colonne sélectionnée
            document.getElementById('sort').addEventListener('change', function() {
                let rows = Array.from(document.querySelectorAll('#eventTable tr'));
                let sortType = this.value;
                rows.sort((a, b) => {
                    let valA = a.cells[sortType === 'title' ? 1 : sortType === 'date' ? 2 : sortType === 'location' ? 3 : 4].textContent.toLowerCase();
                    let valB = b.cells[sortType === 'title' ? 1 : sortType === 'date' ? 2 : sortType === 'location' ? 3 : 4].textContent.toLowerCase();
                    return valA.localeCompare(valB); // Tri alphabétique
                });
                document.getElementById('eventTable').append(...rows); // Réorganise les lignes
            });

            // Exportation des données en CSV
            document.getElementById('exportBtn').addEventListener('click', function() {
                let csv = "Image,Titre,Date,Lieu,Statut\n"; // En-tête du CSV
                document.querySelectorAll("#eventTable tr").forEach(row => {
                    let cells = row.querySelectorAll("td");
                    if (cells.length > 0) {
                        let image = cells[0].querySelector('img') ? cells[0].querySelector('img').src : 'Pas d\'image';
                        csv += `${image},${cells[1].textContent},${cells[2].textContent},${cells[3].textContent},${cells[4].querySelector('span').textContent}\n`;
                    }
                });

                // Création et téléchargement du fichier CSV
                let blob = new Blob([csv], {
                    type: 'text/csv'
                });
                let a = document.createElement('a');
                a.href = URL.createObjectURL(blob);
                a.download = "evenements_export.csv";
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                showNotification('Exportation réussie !', 'bg-green-500');
            });

            // Fonction pour gérer la pagination du tableau
            function paginateTable(rowsPerPage = 10) {
                let rows = document.querySelectorAll('#eventTable tr');
                let totalPages = Math.ceil(rows.length / rowsPerPage);
                let pagination = document.getElementById('pagination');
                pagination.innerHTML = ''; // Réinitialise la pagination

                // Affiche une page spécifique
                function showPage(page) {
                    rows.forEach((row, index) => {
                        row.style.display = index >= (page - 1) * rowsPerPage && index < page * rowsPerPage ? '' : 'none';
                    });
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