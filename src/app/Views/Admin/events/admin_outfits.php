<?php
include('src/app/Views/includes/admin/admin_head.php');
include('src/app/Views/includes/admin/admin_header.php');
include('src/app/Views/includes/admin/admin_sidebar.php');
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

        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">👗 Gestion des Idées de Tenues</h2>
            <button id="openModal" class="bg-black text-white px-5 py-2 rounded-lg flex items-center space-x-2 hover:bg-gray-800 transition">
                <span class="material-icons">add</span>
                <span>Ajouter une tenue</span>
            </button>
        </div>

        <!-- Recherche et Export -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex justify-between mb-4">
                <input id="search" type="text" placeholder="Rechercher une tenue..." class="border px-4 py-2 rounded-md w-1/3 focus:ring focus:ring-[#8B5A2B]">
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
                        <option value="title">Trier par Nom</option>
                        <option value="date">Trier par Date</option>
                    </select>
                </div>
            </div>

            <!-- Liste des tenues -->
            <table class="w-full border-collapse border">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-3">Nom de la tenue</th>
                        <th class="border p-3">Accessoires</th>
                        <th class="border p-3">Image</th>
                        <th class="border p-3">Statut</th>
                        <th class="border p-3">Produit</th>
                        <th class="border p-3">Actions</th>
                    </tr>
                </thead>
                <tbody id="outfitTable">
                    <?php foreach ($outfits as $outfit) : ?>
                        <tr class="hover:bg-gray-100">
                            <td class="border p-3"> <?= htmlspecialchars($outfit['outfit_name']); ?> </td>
                            <td class="border p-3"> <?= htmlspecialchars($outfit['accessories']); ?> </td>
                            <td class="border p-3">
                                <?php $image = isset($outfit['product_image']) ? htmlspecialchars($outfit['product_image']) : 'assets/images/placeholder.webp'; ?>
                                <img src="<?= $image; ?>" alt="Image tenue" class="rounded-md w-40 h-40 object-cover">
                            </td>
                            <td class="border p-3">
                                <span class="px-3 py-1 rounded-full text-white text-xs font-bold <?= $outfit['status'] == 'active' ? 'bg-green-500' : 'bg-red-500' ?>">
                                    <?= htmlspecialchars($outfit['status']) ?>
                                </span>
                            </td>
                            <td class="border p-3"> <?= $outfit['product_id']; ?> </td>
                            <td class="border p-3">
                                <button class="edit-outfit text-blue-600 hover:underline"
                                    data-id="<?= $outfit['id'] ?>" data-name="<?= htmlspecialchars($outfit['outfit_name']) ?>"
                                    data-accessories="<?= htmlspecialchars($outfit['accessories']) ?>"
                                    data-image="<?= isset($outfit['product_image']) ? htmlspecialchars($outfit['product_image']) : 'assets/images/placeholder.webp' ?>"
                                    data-status="<?= $outfit['status'] ?>"
                                    data-product-id="<?= $outfit['product_id'] ?>">
                                    Modifier
                                </button>
                                <button class="text-red-600 font-semibold hover:underline deleteOutfitBtn" data-id="<?= $outfit['id'] ?>">❌ Supprimer</button>
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
    <div id="outfitModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-lg w-full relative">
            <h3 id="modalTitle" class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <span id="modalIcon" class="material-icons text-purple-600 mr-2">add_circle</span> Ajouter une tenue
            </h3>
            <form id="outfitForm" action="admin/outfits/ajouter" method="POST">
                <input type="hidden" id="outfitId" name="id">
                <input type="text" id="outfitName" name="outfit_name" placeholder="Nom de la tenue" required class="w-full p-3 border border-gray-300 rounded-md mb-2 focus:ring focus:ring-[#8B5A2B]">
                <textarea id="outfitAccessories" name="accessories" placeholder="Accessoires" required class="w-full p-3 border border-gray-300 rounded-md mb-2 focus:ring focus:ring-[#8B5A2B]"></textarea>

                <!-- Sélectionner un produit -->
                <label for="productSelect" class="block mb-2">Sélectionnez un produit :</label>
                <select id="productSelect" name="product_id" class="w-full p-3 border border-gray-300 rounded-md mb-2 focus:ring focus:ring-[#8B5A2B]">
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <option value="<?= $product['id']; ?>" data-image="<?= htmlspecialchars($product['image_path']); ?>">
                                <?= strip_tags($product['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">Aucun produit disponible</option>
                    <?php endif; ?>
                </select>

                <img id="productImagePreview" src="assets/images/placeholder.webp" alt="Aperçu de l'image" class="mt-2 rounded-md w-40 h-40 object-cover hidden">

                <select id="outfitStatus" name="status" class="w-full p-3 border border-gray-300 rounded-md mb-2">
                    <option value="active">Actif</option>
                    <option value="inactive">Inactif</option>
                </select>
                <div class="flex justify-between mt-4">
                    <button type="button" id="closeModal" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 flex items-center">❌ Annuler</button>
                    <button type="submit" id="submitButton" class="bg-[#8B5A2B] text-white px-6 py-3 rounded-md hover:scale-105 transition flex items-center">✅ Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>


<!-- Modale pour la suppression -->
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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let deleteOutfitId = null;

        // Gestion de l'aperçu de l'image du produit sélectionné
        document.getElementById('productSelect').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const imagePath = selectedOption.dataset.image;
            const previewImage = document.getElementById('productImagePreview');

            if (imagePath) {
                previewImage.src = imagePath;
                previewImage.classList.remove('hidden');
            } else {
                previewImage.classList.add('hidden');
            }
        });

        // Suppression des tenues avec confirmation
        document.querySelectorAll('.deleteOutfitBtn').forEach(button => {
            button.addEventListener('click', function() {
                deleteOutfitId = this.dataset.id; // Stocke l'ID de l'utilisateur
                document.getElementById('deleteModal').classList.remove('hidden'); // Affiche la modal
            });
        });

        // Annuler la suppression
        document.getElementById('cancelDelete').addEventListener('click', function() {
            document.getElementById('deleteModal').classList.add('hidden'); // Masque la modal
            deleteOutfitId = null; // Réinitialise l'ID
        });

        // Confirmer la suppression 
        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (deleteOutfitId) {
                fetch(`admin/outfits/supprimer/${deleteOutfitId}`, { // Utilise des backticks pour l'interpolation
                        method: 'DELETE'
                    })
                    .then(response => {
                        if (response.ok) {
                            document.querySelector(`button[data-id="${deleteOutfitId}"]`).closest('tr').remove(); // Retire la tenue de la table
                            showNotification('Tenue supprimée avec succès.', 'bg-green-500'); // Message de confirmation
                        } else {
                            showNotification('Erreur lors de la suppression de cette tenue.', 'bg-red-500'); // Message d'erreur
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
        let rows = document.querySelectorAll('#outfitTable tr');
        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });

    // Tri des tenues
    document.getElementById('sort').addEventListener('change', function() {
        let rows = Array.from(document.querySelectorAll('#outfitTable tr'));
        let sortType = this.value;
        rows.sort((a, b) => {
            let valA = a.cells[sortType === 'title' ? 0 : sortType === 'price' ? 1 : 2].textContent.toLowerCase();
            let valB = b.cells[sortType === 'title' ? 0 : sortType === 'price' ? 1 : 2].textContent.toLowerCase();
            return valA.localeCompare(valB);
        });
        document.getElementById('outfitTable').append(...rows);
    });

    // Exportation
    document.getElementById('exportBtn').addEventListener('click', function() {
        alert("Exportation en cours...");
    });


    // Gestion de la modale
    document.addEventListener('DOMContentLoaded', function() {
        const outfitModal = document.getElementById("outfitModal");
        const closeModal = document.getElementById("closeModal");
        const openModalButton = document.getElementById("openModal");
        const outfitForm = document.getElementById("outfitForm");
        const modalTitle = document.getElementById("modalTitle");
        const modalIcon = document.getElementById("modalIcon");
        const submitButton = document.getElementById("submitButton");

        // Ouvrir le modal pour ajouter une tenue
        openModalButton.addEventListener("click", () => {
            outfitForm.reset(); // Réinitialiser le formulaire
            outfitForm.action = "admin/outfits/ajouter"; // Action pour ajouter
            modalTitle.textContent = "Ajouter une Tenue";
            modalIcon.textContent = "add_circle";
            submitButton.textContent = "✅ Ajouter";
            outfitModal.classList.remove("hidden");
        });

        document.querySelectorAll('.edit-outfit').forEach(button => {
            button.addEventListener('click', function() {
                let outfitId = this.getAttribute("data-id");
                let outfitName = this.getAttribute("data-name");
                let outfitAccessories = this.getAttribute("data-accessories");
                let outfitImage = this.getAttribute("data-image");
                let outfitStatus = this.getAttribute("data-status");
                let outfitProductId = this.getAttribute("data-product-id");

                // Remplir les champs
                document.getElementById('outfitId').value = outfitId;
                document.getElementById('outfitName').value = outfitName;
                document.getElementById('outfitAccessories').value = outfitAccessories;
                // On ne met rien pour outfitImage, car on n’a pas de champ #outfitImage
                document.getElementById('productSelect').value = outfitProductId;
                document.getElementById('outfitStatus').value = outfitStatus;

                // Corriger la route
                document.getElementById("outfitForm").action = `admin/outfits/modifier/${outfitId}`;

                // Mettre à jour le texte du modal
                document.getElementById("modalTitle").textContent = "Modifier un outfit";
                document.getElementById("submitButton").textContent = "✅ Mettre à jour";

                // Afficher la modale
                document.getElementById("outfitModal").classList.remove("hidden");
            });
        });

        // Fermer le modal
        closeModal.addEventListener("click", () => {
            outfitModal.classList.add("hidden");
        });
    });

    // Pagination JavaScript
    function paginateTable(rowsPerPage = 10) {
        let rows = document.querySelectorAll('#outfitTable tr');
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