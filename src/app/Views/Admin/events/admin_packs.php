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
        /* Distance du haut de l'écran */
        right: 20px;
        /* Distance du bord droit */
        z-index: 9999;
        /* Assure que la notification est au-dessus des autres éléments */
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        /* Ombre légère pour effet de profondeur */
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

        <!-- Affichage des messages de succès ou d'erreur après action -->
        <?php if (isset($success)): ?>
            <?php if ($success == 1): ?>
                <?php if (isset($_GET['action']) && $_GET['action'] == 'add'): ?>
                    <div id="successMessage" class="bg-green-500 text-white p-3 rounded-md mb-4">
                        ✅ Pack ajouté avec succès !
                    </div>
                <?php elseif (isset($_GET['action']) && $_GET['action'] == 'update'): ?>
                    <div id="successMessage" class="bg-green-500 text-white p-3 rounded-md mb-4">
                        ✅ Pack modifié avec succès !
                    </div>
                <?php endif; ?>
            <?php elseif ($success == 0): ?>
                <?php if (isset($_GET['action']) && $_GET['action'] == 'add'): ?>
                    <div id="successMessage" class="bg-red-500 text-white p-3 rounded-md mb-4">
                        ❌ Une erreur est survenue lors de l'ajout du pack. Veuillez réessayer.
                    </div>
                <?php elseif (isset($_GET['action']) && $_GET['action'] == 'update'): ?>
                    <div id="successMessage" class="bg-red-500 text-white p-3 rounded-md mb-4">
                        ❌ Une erreur est survenue lors de la modification du pack. Veuillez réessayer.
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>

        <!-- En-tête de la section avec bouton pour ajouter un pack -->
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">🎁 Gestion des Packs</h2>
            <button id="openModal" class="bg-black text-white px-5 py-2 rounded-lg flex items-center space-x-2 hover:bg-gray-800 transition">
                <span class="material-icons">add</span>
                <span>Ajouter un pack</span>
            </button>
        </div>

        <!-- Section de recherche, tri et exportation -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex justify-between mb-4">
                <!-- Champ de recherche -->
                <input id="search" type="text" placeholder="Rechercher un pack..." class="border px-4 py-2 rounded-md w-1/-tap focus:ring focus:ring-[#8B5A2B]">
                <div class="flex space-x-4">
                    <!-- Bouton d'exportation -->
                    <button id="exportBtn" class="border px-4 py-2 rounded-md bg-blue-500 text-white hover:bg-blue-600">Exporter les données </button>
                    <!-- Menu déroulant pour le tri -->
                    <select id="sort" class="border px-4 py-2 rounded-md">
                        <option value="title">Trier par Nom</option>
                        <option value="price">Trier par Prix</option>
                        <option value="stock">Trier par Stock</option>
                    </select>
                </div>
            </div>

            <!-- Tableau des packs -->
            <div class="table-container">

                <table class="w-full border-collapse border">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border p-3">Titre</th>
                            <th class="border p-3">Description</th>
                            <th class="border p-3">Prix</th>
                            <th class="border p-3">Durée en jours</th>
                            <th class="border p-3">Comprant</th>
                            <th class="border p-3">Statut</th>
                            <th class="border p-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="packTable">
                        <?php foreach ($packs as $pack) : ?>
                            <tr class="hover:bg-gray-100">
                                <!-- Affichage des données du pack -->
                                <td class="border p-3"><?= htmlspecialchars($pack['title']) ?></td>
                                <td class="border p-3"><?= htmlspecialchars($pack['description']) ?></td>
                                <td class="border p-3"><?= htmlspecialchars($pack['price']) ?>€</td>
                                <td class="border p-3"><?= htmlspecialchars($pack['duration']) ?></td>
                                <td class="border p-3"><?= htmlspecialchars($pack['included']) ?></td>
                                <!-- Statut avec badge coloré -->
                                <td class="border p-3">
                                    <span class="px-3 py-1 rounded-full text-white text-xs font-bold <?= $pack['status'] == 'active' ? 'bg-green-500' : 'bg-red-500' ?>">
                                        <?= htmlspecialchars($pack['status']) ?>
                                    </span>
                                </td>
                                <!-- Boutons d'action -->
                                <td class="border p-3">
                                    <button class="edit-pack text-blue-600 hover:underline"
                                        data-id="<?= $pack['id'] ?>"
                                        data-title="<?= htmlspecialchars($pack['title']) ?>"
                                        data-description="<?= htmlspecialchars($pack['description']) ?>"
                                        data-price="<?= htmlspecialchars($pack['price']) ?>"
                                        data-duration="<?= $pack['duration'] ?>"
                                        data-included="<?= htmlspecialchars($pack['included']) ?>"
                                        data-status="<?= $pack['status'] ?>">
                                        ✏️ Modifier
                                    </button>
                                    <button class="text-red-600 font-semibold hover:underline deletePackBtn" data-id="<?= $pack['id'] ?>">❌ Supprimer</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <!-- Conteneur pour la pagination -->
            <div class="mt-4 flex justify-center space-x-2" id="pagination"></div>
        </div>
    </div>

    <!-- Modal pour ajouter ou modifier un pack -->
    <div id="packModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-lg w-full relative">
            <h3 id="modalTitle" class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <span id="modalIcon" class="material-icons text-purple-600 mr-2">add_circle</span> Ajouter un pack
            </h3>
            <!-- Formulaire pour ajout/modification -->
            <form id="packForm" action="admin/packs/ajouter" method="POST">
                <input type="hidden" id="packId" name="id"> <!-- Champ caché pour l'ID lors de la modification -->
                <input type="text" id="packTitle" name="title" placeholder="Nom du pack" required class="w-full p-3 border border-gray-300 rounded-md mb-2 focus:ring focus:ring-[#8B5A2B]">
                <textarea id="packDescription" name="description" placeholder="Description" required class="w-full p-3 border border-gray-300 rounded-md mb-2"></textarea>
                <input type="number" id="packPrice" name="price" placeholder="Prix" required class="w-full p-3 border border-gray-300 rounded-md mb-2 focus:ring focus:ring-[#8B5A2B]">
                <input type="number" id="packDuration" name="duration" placeholder="Durée en jours" required class="w-full p-3 border border-gray-300 rounded-md mb-2 focus:ring focus:ring-[#8B5A2B]">
                <input type="text" id="packIncluded" name="included" placeholder="Comprant" required class="w-full p-3 border border-gray-300 rounded-md mb-2 focus:ring focus:ring-[#8B5A2B]">
                <!-- Sélection du statut -->
                <select id="packStatus" name="status" class="w-full p-3 border border-gray-300 rounded-md mb-2">
                    <option value="active">Actif</option>
                    <option value="inactive">Inactif</option>
                </select>
                <!-- Boutons d'action du formulaire -->
                <div class="flex justify-between mt-4">
                    <button type="button" id="closeModal" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 flex items-center">
                        ❌ Annuler
                    </button>
                    <button type="submit" id="submitButton" class="bg-[#8B5A2B] text-white px-6 py-3 rounded-md hover:scale-105 transition flex items-center">
                        ✅ Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de confirmation pour la suppression -->
<div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full">
        <h3 class="text-xl font-bold text-gray-800 mb-4">⚠️ Confirmer la suppression</h3>
        <p class="text-gray-600">Voulez-vous vraiment supprimer ce pack ? Cette action est irréversible.</p>
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
        let deletePackId = null; // Variable pour stocker l'ID du pack à supprimer

        // Gestion des boutons "Supprimer" avec ouverture du modal de confirmation
        document.querySelectorAll('.deletePackBtn').forEach(button => {
            button.addEventListener('click', function() {
                deletePackId = this.dataset.id; // Stocke l'ID du pack
                document.getElementById('deleteModal').classList.remove('hidden'); // Affiche le modal
            });
        });

        // Annuler la suppression
        document.getElementById('cancelDelete').addEventListener('click', function() {
            document.getElementById('deleteModal').classList.add('hidden'); // Masque le modal
            deletePackId = null; // Réinitialise l'ID
        });

        // Confirmer la suppression via une requête FETCH
        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (deletePackId) {
                fetch(`admin/packs/supprimer/${deletePackId}`, {
                        method: 'DELETE' // Requête DELETE pour supprimer le pack
                    })
                    .then(response => {
                        if (response.ok) {
                            // Supprime la ligne du tableau si succès
                            document.querySelector(`button[data-id="${deletePackId}"]`).closest('tr').remove();
                            showNotification('Pack supprimé avec succès.', 'bg-green-500');
                        } else {
                            showNotification('Erreur lors de la suppression du pack.', 'bg-red-500');
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
            notification.classList.remove('hidden', 'bg-red-500', 'bg-green-500'); // Réinitialise les classes
            notification.classList.add(bgColor); // Applique la couleur spécifiée
            setTimeout(() => {
                notification.classList.add('hidden'); // Masque après 3 secondes
            }, 3000);
        }

        // Masquer le message de succès/erreur après 3 secondes
        const successDiv = document.getElementById('successMessage');
        if (successDiv) {
            setTimeout(() => {
                successDiv.style.display = 'none';
            }, 3000);
        }
    });

    // Recherche dynamique dans le tableau
    document.getElementById('search').addEventListener('input', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#packTable tr');
        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none'; // Affiche ou cache les lignes
        });
    });

    // Tri des lignes du tableau selon la colonne sélectionnée
    document.getElementById('sort').addEventListener('change', function() {
        let rows = Array.from(document.querySelectorAll('#packTable tr'));
        let sortType = this.value;
        rows.sort((a, b) => {
            let valA = a.cells[sortType === 'title' ? 0 : sortType === 'price' ? 2 : 3].textContent.toLowerCase();
            let valB = b.cells[sortType === 'title' ? 0 : sortType === 'price' ? 2 : 3].textContent.toLowerCase();
            return valA.localeCompare(valB); // Tri alphabétique ou numérique
        });
        document.getElementById('packTable').append(...rows); // Réorganise les lignes
    });

    // Exportation des données en CSV
    document.getElementById('exportBtn').addEventListener('click', function() {
        let csv = "Titre,Description,Prix,Durée en jours,Comprant,Statut\n"; // En-tête du CSV
        document.querySelectorAll("#packTable tr").forEach(row => {
            let cells = row.querySelectorAll("td");
            if (cells.length > 0) {
                csv += `${cells[0].textContent},${cells[1].textContent},${cells[2].textContent},${cells[3].textContent},${cells[4].textContent},${cells[5].querySelector('span').textContent}\n`;
            }
        });

        // Création et téléchargement du fichier CSV
        let blob = new Blob([csv], {
            type: 'text/csv'
        });
        let a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = "packs_export.csv";
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        showNotification('Exportation réussie !', 'bg-green-500');
    });

    // Gestion du modal pour ajout et modification
    document.addEventListener('DOMContentLoaded', function() {
        const packModal = document.getElementById("packModal");
        const closeModal = document.getElementById("closeModal");
        const openModalButton = document.getElementById("openModal");
        const packForm = document.getElementById("packForm");
        const modalTitle = document.getElementById("modalTitle");
        const modalIcon = document.getElementById("modalIcon");
        const submitButton = document.getElementById("submitButton");

        // Ouvrir le modal pour ajouter un pack
        openModalButton.addEventListener("click", () => {
            packForm.reset(); // Réinitialise les champs du formulaire
            packForm.action = "admin/packs/ajouter"; // Définit l'action pour l'ajout
            modalTitle.textContent = "Ajouter un pack";
            modalIcon.textContent = "add_circle";
            submitButton.textContent = "✅ Ajouter";
            packModal.classList.remove("hidden"); // Affiche le modal
        });

        // Gestion des boutons "Modifier" pour remplir le modal avec les données existantes
        document.querySelectorAll(".edit-pack").forEach(button => {
            button.addEventListener("click", function() {
                let packId = this.getAttribute("data-id");
                document.getElementById("packId").value = packId;
                document.getElementById("packTitle").value = this.getAttribute("data-title");
                document.getElementById("packDescription").value = this.getAttribute("data-description");
                document.getElementById("packPrice").value = this.getAttribute("data-price");
                document.getElementById("packDuration").value = this.getAttribute("data-duration");
                document.getElementById("packIncluded").value = this.getAttribute("data-included");
                document.getElementById("packStatus").value = this.getAttribute("data-status");

                // Mise à jour de l'action du formulaire pour la modification
                packForm.action = `admin/packs/modifier/${packId}`;
                modalTitle.textContent = "✏️ Modifier un pack";
                modalIcon.textContent = "edit";
                submitButton.textContent = "✅ Mettre à jour";
                packModal.classList.remove("hidden"); // Affiche le modal
            });
        });

        // Fermer le modal au clic sur "Annuler"
        closeModal.addEventListener("click", () => {
            packModal.classList.add("hidden");
        });
    });

    // Fonction pour gérer la pagination du tableau
    function paginateTable(rowsPerPage = 10) {
        let rows = document.querySelectorAll('#packTable tr');
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
</script>

</html>