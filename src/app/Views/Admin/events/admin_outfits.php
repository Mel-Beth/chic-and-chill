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
                        ✅ Outfit ajouté avec succès !
                    </div>
                <?php elseif (isset($_GET['action']) && $_GET['action'] == 'update'): ?>
                    <div id="successMessage" class="bg-green-500 text-white p-3 rounded-md mb-4">
                        ✅ Outfit modifié avec succès !
                    </div>
                <?php endif; ?>
            <?php elseif ($success == 0): ?>
                <?php if (isset($_GET['action']) && $_GET['action'] == 'add'): ?>
                    <div id="successMessage" class="bg-red-500 text-white p-3 rounded-md mb-4">
                        ❌ Une erreur est survenue lors de l'ajout de l'outfit. Veuillez réessayer.
                    </div>
                <?php elseif (isset($_GET['action']) && $_GET['action'] == 'update'): ?>
                    <div id="successMessage" class="bg-red-500 text-white p-3 rounded-md mb-4">
                        ❌ Une erreur est survenue lors de la modification de l'outfit. Veuillez réessayer.
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>

        <!-- En-tête de la section avec bouton pour ajouter une tenue -->
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">👗 Gestion des Idées de Tenues</h2>
            <button id="openModal" class="bg-black text-white px-5 py-2 rounded-lg flex items-center space-x-2 hover:bg-gray-800 transition">
                <span class="material-icons">add</span>
                <span>Ajouter une tenue</span>
            </button>
        </div>

        <!-- Section de recherche, tri et exportation -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex justify-between mb-4">
                <!-- Champ de recherche -->
                <input id="search" type="text" placeholder="Rechercher une tenue..." class="border px-4 py-2 rounded-md w-1/3 focus:ring focus:ring-[#8B5A2B]">
                <div class="flex space-x-4">
                    <!-- Bouton d'exportation -->
                    <div class="relative">
                        <button id="exportBtn" class="border px-4 py-2 rounded-md bg-blue-500 text-white hover:bg-blue-600">Exporter</button>
                    </div>
                    <!-- Menu déroulant pour le tri -->
                    <select id="sort" class="border px-4 py-2 rounded-md">
                        <option value="title">Trier par Nom</option>
                        <option value="date">Trier par Date</option>
                    </select>
                </div>
            </div>

            <!-- Tableau des tenues avec défilement horizontal -->
            <div class="table-container">
                <table class="w-full border-collapse border">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border p-3">Nom de la tenue</th>
                            <th class="border p-3">Accessoires</th>
                            <th class="border p-3">Image</th>
                            <th class="border p-3">Statut</th>
                            <th class="border p-3">Numéro produit</th>
                            <th class="border p-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="outfitTable">
                        <?php foreach ($outfits as $outfit) : ?>
                            <tr class="hover:bg-gray-100">
                                <!-- Affichage des données de la tenue -->
                                <td class="border p-3"><?= htmlspecialchars($outfit['product_name']); ?></td>
                                <td class="border p-3"><?= htmlspecialchars($outfit['accessories']); ?></td>
                                <td class="border p-3">
                                    <!-- Gestion de l'image avec un placeholder par défaut -->
                                    <?php $image = isset($outfit['product_image']) ? htmlspecialchars($outfit['product_image']) : 'assets/images/placeholder.webp'; ?>
                                    <img src="<?= $image; ?>" alt="Image tenue" class="rounded-md w-40 h-40 object-cover">
                                </td>
                                <!-- Statut avec badge coloré -->
                                <td class="border p-3">
                                    <span class="px-3 py-1 rounded-full text-white text-xs font-bold <?= $outfit['status'] == 'active' ? 'bg-green-500' : 'bg-red-500' ?>">
                                        <?= htmlspecialchars($outfit['status']) ?>
                                    </span>
                                </td>
                                <td class="border p-3"><?= $outfit['product_id']; ?></td>
                                <!-- Boutons d'action -->
                                <td class="border p-3">
                                    <button class="edit-outfit text-blue-600 hover:underline"
                                        data-id="<?= $outfit['id'] ?>"
                                        data-name="<?= htmlspecialchars($outfit['product_name']) ?>"
                                        data-accessories="<?= htmlspecialchars($outfit['accessories']) ?>"
                                        data-image="<?= isset($outfit['product_image']) ? htmlspecialchars($outfit['product_image']) : 'assets/images/placeholder.webp' ?>"
                                        data-status="<?= $outfit['status'] ?>"
                                        data-product-id="<?= $outfit['product_id'] ?>">
                                        ✏️ Modifier
                                    </button>
                                    <button class="text-red-600 font-semibold hover:underline deleteOutfitBtn" data-id="<?= $outfit['id'] ?>">❌ Supprimer</button>
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

    <!-- Modal pour ajouter ou modifier une tenue -->
    <div id="outfitModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-lg w-full relative">
            <h3 id="modalTitle" class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <span id="modalIcon" class="material-icons text-purple-600 mr-2">add_circle</span> Ajouter une tenue
            </h3>
            <!-- Formulaire pour ajout/modification -->
            <form id="outfitForm" action="admin/outfits/ajouter" method="POST">
                <input type="hidden" id="outfitId" name="id"> <!-- Champ caché pour l'ID lors de la modification -->
                <!-- Sélection du produit associé -->
                <label for="productSelect" class="block mb-2">Sélectionnez un produit :</label>
                <select id="productSelect" name="product_id" class="w-full p-3 border border-gray-300 rounded-md mb-2 focus:ring focus:ring-[#8B5A2B]" required>
                    <option value="">Sélectionnez un produit</option>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <option value="<?= $product['id']; ?>" data-image="<?= htmlspecialchars($product['image'] ?? 'assets/images/placeholder.webp'); ?>">
                                <?= strip_tags($product['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">Aucun produit disponible</option>
                    <?php endif; ?>
                </select>
                <!-- Prévisualisation de l'image du produit -->
                <img id="productImagePreview" src="assets/images/placeholder.webp" alt="Aperçu de l'image" class="mt-2 rounded-md w-40 h-40 object-cover hidden">
                <!-- Champ pour les accessoires -->
                <textarea id="outfitAccessories" name="accessories" placeholder="Accessoires" required class="w-full p-3 border border-gray-300 rounded-md mb-2 mt-2 focus:ring focus:ring-[#8B5A2B]"></textarea>
                <!-- Sélection du statut -->
                <select id="outfitStatus" name="status" class="w-full p-3 border border-gray-300 rounded-md mb-2">
                    <option value="active">Actif</option>
                    <option value="inactive">Inactif</option>
                </select>
                <!-- Boutons d'action du formulaire -->
                <div class="flex justify-between mt-4">
                    <button type="button" id="closeModal" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 flex items-center">❌ Annuler</button>
                    <button type="submit" id="submitButton" class="bg-[#8B5A2B] text-white px-6 py-3 rounded-md hover:scale-105 transition flex items-center">✅ Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de confirmation pour la suppression -->
<div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full">
        <h3 class="text-xl font-bold text-gray-800 mb-4">⚠️ Confirmer la suppression</h3>
        <p class="text-gray-600">Voulez-vous vraiment supprimer cette tenue ? Cette action est irréversible.</p>
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
        let deleteOutfitId = null; // Variable pour stocker l'ID de la tenue à supprimer

        // Gestion de l'aperçu de l'image du produit sélectionné dans le modal
        document.getElementById('productSelect').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const imagePath = selectedOption.dataset.image;
            const previewImage = document.getElementById('productImagePreview');
            if (imagePath) {
                previewImage.src = imagePath; // Met à jour l'image avec celle du produit sélectionné
                previewImage.classList.remove('hidden');
            } else {
                previewImage.classList.add('hidden'); // Cache l'image si aucun produit valide
            }
        });

        // Gestion des boutons "Supprimer" avec ouverture du modal de confirmation
        document.querySelectorAll('.deleteOutfitBtn').forEach(button => {
            button.addEventListener('click', function() {
                deleteOutfitId = this.dataset.id; // Stocke l'ID de la tenue
                document.getElementById('deleteModal').classList.remove('hidden'); // Affiche le modal
            });
        });

        // Annuler la suppression
        document.getElementById('cancelDelete').addEventListener('click', function() {
            document.getElementById('deleteModal').classList.add('hidden'); // Masque le modal
            deleteOutfitId = null; // Réinitialise l'ID
        });

        // Confirmer la suppression via une requête FETCH
        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (deleteOutfitId) {
                fetch(`admin/outfits/supprimer/${deleteOutfitId}`, {
                        method: 'DELETE' // Requête DELETE pour supprimer la tenue
                    })
                    .then(response => {
                        if (response.ok) {
                            // Supprime la ligne du tableau si succès
                            document.querySelector(`button[data-id="${deleteOutfitId}"]`).closest('tr').remove();
                            showNotification('Tenue supprimée avec succès.', 'bg-green-500');
                        } else {
                            showNotification('Erreur lors de la suppression de cette tenue.', 'bg-red-500');
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

        // Recherche dynamique dans le tableau
        document.getElementById('search').addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#outfitTable tr');
            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none'; // Affiche ou cache les lignes
            });
        });

        // Tri des lignes du tableau selon la colonne sélectionnée
        document.getElementById('sort').addEventListener('change', function() {
            let rows = Array.from(document.querySelectorAll('#outfitTable tr'));
            let sortType = this.value;
            rows.sort((a, b) => {
                let valA = a.cells[sortType === 'title' ? 0 : 1].textContent.toLowerCase();
                let valB = b.cells[sortType === 'title' ? 0 : 1].textContent.toLowerCase();
                return valA.localeCompare(valB); // Tri alphabétique
            });
            document.getElementById('outfitTable').append(...rows); // Réorganise les lignes
        });

        // Exportation des données en CSV
        document.getElementById('exportBtn').addEventListener('click', function() {
            let csv = "Nom de la tenue,Accessoires,Image,Statut,Produit\n"; // En-tête du CSV
            document.querySelectorAll("#outfitTable tr").forEach(row => {
                let cells = row.querySelectorAll("td");
                if (cells.length > 0) {
                    let image = cells[2].querySelector('img').src;
                    csv += `${cells[0].textContent},${cells[1].textContent},${image},${cells[3].querySelector('span').textContent},${cells[4].textContent}\n`;
                }
            });

            // Création et téléchargement du fichier CSV
            let blob = new Blob([csv], {
                type: 'text/csv'
            });
            let a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = "outfits_export.csv";
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            showNotification('Exportation réussie !', 'bg-green-500');
        });

        // Gestion du modal pour ajout et modification
        const outfitModal = document.getElementById("outfitModal");
        const closeModal = document.getElementById("closeModal");
        const openModalButton = document.getElementById("openModal");
        const outfitForm = document.getElementById("outfitForm");
        const modalTitle = document.getElementById("modalTitle");
        const modalIcon = document.getElementById("modalIcon");
        const submitButton = document.getElementById("submitButton");

        // Ouvrir le modal pour ajouter une tenue
        openModalButton.addEventListener("click", () => {
            outfitForm.reset(); // Réinitialise les champs du formulaire
            outfitForm.action = "admin/outfits/ajouter"; // Définit l'action pour l'ajout
            modalTitle.textContent = "Ajouter une Tenue";
            modalIcon.textContent = "add_circle";
            submitButton.textContent = "✅ Ajouter";
            document.getElementById('productImagePreview').classList.add('hidden'); // Cache l'image par défaut
            outfitModal.classList.remove("hidden"); // Affiche le modal
        });

        // Gestion des boutons "Modifier" pour remplir le modal avec les données existantes
        document.querySelectorAll('.edit-outfit').forEach(button => {
            button.addEventListener('click', function() {
                let outfitId = this.getAttribute("data-id");
                let outfitAccessories = this.getAttribute("data-accessories");
                let outfitImage = this.getAttribute("data-image");
                let outfitStatus = this.getAttribute("data-status");
                let outfitProductId = this.getAttribute("data-product-id");

                // Remplissage des champs du formulaire
                document.getElementById('outfitId').value = outfitId;
                document.getElementById('outfitAccessories').value = outfitAccessories;
                document.getElementById('productSelect').value = outfitProductId;
                document.getElementById('outfitStatus').value = outfitStatus;
                document.getElementById('productImagePreview').src = outfitImage;
                document.getElementById('productImagePreview').classList.remove('hidden');

                // Mise à jour de l'action du formulaire pour la modification
                outfitForm.action = `admin/outfits/modifier/${outfitId}`;
                modalTitle.textContent = "✏️ Modifier un outfit";
                modalIcon.textContent = "edit";
                submitButton.textContent = "✅ Mettre à jour";
                outfitModal.classList.remove("hidden"); // Affiche le modal
            });
        });

        // Fermer le modal au clic sur "Annuler"
        closeModal.addEventListener("click", () => {
            outfitModal.classList.add("hidden");
        });
    });

    // Fonction pour gérer la pagination du tableau
    function paginateTable(rowsPerPage = 10) {
        let rows = document.querySelectorAll('#outfitTable tr');
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