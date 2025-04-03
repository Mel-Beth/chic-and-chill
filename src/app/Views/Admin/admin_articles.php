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
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">🛍 Gestion des Articles</h2>
            <button id="addArticleBtn" class="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 transition">➕ Ajouter un article</button>
        </div>

        <!-- Barre de recherche et filtres -->
        <div class="flex justify-between mb-4">
            <input id="search" type="text" placeholder="Rechercher un article..." class="border px-4 py-2 rounded-md w-1/3 focus:ring focus:ring-[#8B5A2B]">
            <!-- Formulaire pour filtrer les articles -->
            <form method="GET" action="admin/crudShop">
                <div class="flex space-x-4">
                    <!-- Menu Catégories Femme -->
                    <select name="id_categories" id="categories_femmes" onchange="window.location.href='admin/crudShop?id_categories='+this.value+'&gender=femmes'">
                        <option value="">Catégories femmes</option>
                        <?php foreach ($categories_femmes as $categorie) : ?>
                            <option value="<?= htmlspecialchars($categorie['id_categories']) ?>"
                                <?= ($_GET['id_categories'] ?? '') == $categorie['id_categories'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($categorie['name_categories']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Menu Catégories Enfant -->
                    <select name="id_categories" id="categories_enfants" onchange="window.location.href='admin/crudShop?id_categories='+this.value+'&gender=enfants'">
                        <option value="">Catégories enfants</option>
                        <?php foreach ($categories_enfants as $categorie) : ?>
                            <option value="<?= htmlspecialchars($categorie['id_categories']) ?>"
                                <?= ($_GET['id_categories'] ?? '') == $categorie['id_categories'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($categorie['name_categories']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>









        </div>

        <!-- Liste D articles -->
        <table class="w-full border-collapse border">
            <thead>
                <tr class="bg-gray-200">
                <th class="border p-3">Nom</th>
        <th class="border p-3">Prix</th>
        <th class="border p-3">Prix remisé</th>
        <th class="border p-3">Catégorie</th>
        <th class="border p-3">Actions</th>
                </tr>
            </thead>
            <!-- pr le tableau et les donnes que ça va chercher dans la bdd -->
            <tbody id="articlesTable">
                <?php foreach ($articles as $article) : ?>
                    <tr class="hover:bg-gray-100">
            <td class="border p-3"><?= htmlspecialchars($article['name']) ?></td>
            <td class="border p-3"><?= htmlspecialchars($article['price']) ?>€</td>
            <td class="border p-3">
                <?= isset($article['discount_price']) && $article['discount_price'] !== null
                    ? htmlspecialchars($article['discount_price']) . '€'
                    : '—' ?>
            </td>
            <td class="border p-3"><?= htmlspecialchars($article['name_ss_categories'] ?? '—') ?></td>
            <td class="border p-3">
                <button 
                    type="button"
                    class="editArticleBtn text-blue-600 font-semibold hover:underline"
                    data-id="<?= $article['id'] ?>"
                    data-name="<?= htmlspecialchars($article['name']) ?>"
                    data-price="<?= htmlspecialchars($article['price']) ?>"
                    data-discount_price="<?= htmlspecialchars($article['discount_price'] ?? '') ?>"
                    data-stock="<?= $article['stock'] ?>"
                    data-description="<?= htmlspecialchars($article['description']) ?>"
                    data-brand="<?= htmlspecialchars($article['brand']) ?>"
                    data-code="<?= htmlspecialchars($article['code_ena']) ?>"
                    data-size="<?= htmlspecialchars($article['size']) ?>"
                    data-image="<?= $article['image'] ?>"
                    data-rentable="<?= htmlspecialchars($article['is_rentable']) ?>"
                    data-gender="<?= $article['gender'] ?>"
                    data-category="<?= $article['id_categories'] ?>"
                    data-subcategory="<?= $article['id_ss_categories'] ?>"
                >
                     Modifier/ Lire
                </button>

                <form method="POST" action="admin/crudShop?id_categories=<?= urlencode($_GET['id_categories'] ?? '') ?>&gender=<?= urlencode($_GET['gender'] ?? '') ?>" class="inline">
                    <input type="hidden" name="delete" value="<?= $article['id'] ?>">
                    <button type="submit" class="text-red-600 font-semibold hover:underline ml-2">❌ Supprimer</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>

        </table>
    </div>
</div>

<!-- Modale pour ajouter/modifier un article -->
<div id="articleModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto relative">

        <h3 class="text-xl font-bold text-gray-800 mb-4" id="modalTitle">Ajouter un Article</h3>

        <!-- FORMULAIRE AJOUTER OU MODIFIER AHHHHHHHHH -->
        <!-- j'ai mis ?route= parce que c est ce qu il y a dans le routeur -->

        <form id="articleForm" method="POST" action="?route=admin/crudShop" enctype="multipart/form-data" class="space-y-4">
            <!-- Champs cachés -->
            <input type="hidden" name="id" id="articleId">
            <input type="hidden" name="action" value="update">

            <!-- Nom -->
            <div>
                <label for="articleName" class="block text-sm font-medium text-gray-700">Nom de l'article</label>
                <input type="text" name="name" id="articleName" required class="mt-1 block w-full border px-3 py-2 rounded-md shadow-sm">
            </div>

            <!-- Description -->
            <div>
                <label for="articleDescription" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="articleDescription" rows="3" class="mt-1 block w-full border px-3 py-2 rounded-md shadow-sm"></textarea>
            </div>

            <!-- Prix -->
            <div>
                <label for="articlePrice" class="block text-sm font-medium text-gray-700">Prix (€)</label>
                <input type="number" name="price" id="articlePrice" step="0.01" required class="mt-1 block w-full border px-3 py-2 rounded-md shadow-sm">
            </div>

            <!-- la remise, pr les soldes ou autre -->
            <div>
                <label for="articleDiscountPrice" class="block text-sm font-medium text-gray-700">Prix remisé (€)</label>
                <input type="number" name="discount_price" id="articleDiscountPrice" step="0.01" class="mt-1 block w-full border px-3 py-2 rounded-md shadow-sm">
            </div>


            <!-- Stock -->
            <div>
                <label for="articleStock" class="block text-sm font-medium text-gray-700">Stock</label>
                <input type="number" name="stock" id="articleStock" required class="mt-1 block w-full border px-3 py-2 rounded-md shadow-sm">
            </div>

            <!-- Marque -->
            <div>
                <label for="articleBrand" class="block text-sm font-medium text-gray-700">Marque</label>
                <input type="text" name="brand" id="articleBrand" class="mt-1 block w-full border px-3 py-2 rounded-md shadow-sm">
            </div>

            <!-- Genre -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Genre</label>
                <div class="flex space-x-4 mt-1">
                    <label class="inline-flex items-center">
                        <input type="radio" name="gender" value="femmes" class="form-radio" required>
                        <span class="ml-2">Femmes</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="gender" value="enfants" class="form-radio" required>
                        <span class="ml-2">Enfants</span>
                    </label>
                </div>
            </div>

            <!-- Code ENA -->
            <div>
                <label for="articleCode" class="block text-sm font-medium text-gray-700">Code EAN</label>
                <input type="text" name="code_ena" id="articleCode" class="mt-1 block w-full border px-3 py-2 rounded-md shadow-sm">
            </div>

            <!-- Taille -->
            <div>
                <label for="articleSize" class="block text-sm font-medium text-gray-700">Taille</label>
                <input type="text" name="size" id="articleSize" class="mt-1 block w-full border px-3 py-2 rounded-md shadow-sm">
            </div>

            <!-- Image -->
            <div>
                <label for="articleImage" class="block text-sm font-medium text-gray-700">Image</label>
                <input type="file" name="image" id="articleImage" class="mt-1 block w-full border px-3 py-2 rounded-md shadow-sm">
                <img id="previewImage" class="hidden mt-2 w-32 h-32 object-cover rounded-md">
            </div>

            <!-- Louable ? -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Est-ce louable ?</label>
                <select name="is_rentable" id="isRentable" class="mt-1 block w-full border px-3 py-2 rounded-md shadow-sm">
                    <option value="oui">Oui</option>
                    <option value="non">Non</option>
                </select>
            </div>

            <!-- Catégories (dépend de gender) -->
            <div>
                <label for="articleCategory" class="block text-sm font-medium text-gray-700">Catégorie</label>
                <select name="id_categories" id="articleCategory" class="mt-1 block w-full border px-3 py-2 rounded-md shadow-sm" required>
                    <!-- Options injectées dynamiquement en JS -->
                </select>
            </div>

            <!-- Sous-catégories (dépend de catégorie) -->
            <div>
                <label for="articleSubCategory" class="block text-sm font-medium text-gray-700">Sous-catégorie</label>
                <select name="id_ss_categories" id="articleSubCategory" class="mt-1 block w-full border px-3 py-2 rounded-md shadow-sm" required>
                    <!-- Options injectées dynamiquement en JS -->
                </select>
            </div>

            <!-- Boutons -->
            <div class="flex justify-between pt-4">
                <button type="button" id="cancelModal" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Annuler</button>
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-md hover:scale-105 transition">Enregistrer</button>
            </div>
        </form>

        <!-- script pr les hidden etc -->
        <script>
document.addEventListener("DOMContentLoaded", function() {
    const modal = document.getElementById("articleModal");
    const modalTitle = document.getElementById("modalTitle");

    const articleForm = document.getElementById("articleForm");
    const articleId = document.getElementById("articleId");
    const articleName = document.getElementById("articleName");
    const articlePrice = document.getElementById("articlePrice");
    const articleDiscountPrice = document.getElementById("articleDiscountPrice");
    const articleStock = document.getElementById("articleStock");
    const articleDescription = document.getElementById("articleDescription");
    const articleBrand = document.getElementById("articleBrand");
    const articleCode = document.getElementById("articleCode");
    const articleSize = document.getElementById("articleSize");
    const articleImage = document.getElementById("articleImage");
    const previewImage = document.getElementById("previewImage");
    const isRentable = document.getElementById("isRentable");
    const genderRadios = document.querySelectorAll('input[name="gender"]');
    const categorySelect = document.getElementById("articleCategory");
    const subCategorySelect = document.getElementById("articleSubCategory");
    const cancelModal = document.getElementById("cancelModal");
    const actionInput = document.querySelector('input[name="action"]');

    // 🎯 Ouvrir la modale d’ajout
    document.getElementById("addArticleBtn").addEventListener("click", function() {
        modalTitle.textContent = "Ajouter un Article";
        articleForm.reset();
        previewImage.classList.add("hidden");
        categorySelect.innerHTML = '<option value="">Choisir une catégorie</option>';
        subCategorySelect.innerHTML = '<option value="">Choisir une sous-catégorie</option>';
        modal.classList.remove("hidden");

        actionInput.value = "add";       // ✅ Indiquer que c’est un ajout
        articleId.value = "";            // ✅ Aucune ID pour un nouvel article
    });

    // 🎯 Fermer la modale
    cancelModal.addEventListener("click", function() {
        modal.classList.add("hidden");
    });

    // 🎯 Aperçu de l'image
    articleImage.addEventListener("change", function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewImage.classList.remove("hidden");
            };
            reader.readAsDataURL(file);
        }
    });

    // ✅ Charger les catégories selon le genre
    function loadCategories(gender, selectedCat = null, selectedSubCat = null) {
        fetch(`admin/getCategoriesByGender?gender=${gender}`)
            .then(response => response.json())
            .then(data => {
                categorySelect.innerHTML = '<option value="">Choisir une catégorie</option>';
                data.categories.forEach(cat => {
                    const option = document.createElement("option");
                    option.value = cat.id_categories;
                    option.textContent = cat.name_categories;
                    if (selectedCat && cat.id_categories == selectedCat) {
                        option.selected = true;
                    }
                    categorySelect.appendChild(option);
                });

                if (selectedCat) {
                    loadSubCategories(selectedCat, selectedSubCat);
                } else {
                    subCategorySelect.innerHTML = '<option value="">Choisir une sous-catégorie</option>';
                }
            });
    }

    // ✅ Charger les sous-catégories selon la catégorie
    function loadSubCategories(idCat, selectedSubCat = null) {
        fetch(`admin/getSubCategories?id_categories=${idCat}`)
            .then(response => response.json())
            .then(data => {
                subCategorySelect.innerHTML = '<option value="">Choisir une sous-catégorie</option>';
                data.subCategories.forEach(ss => {
                    const option = document.createElement("option");
                    option.value = ss.id_ss_categories;
                    option.textContent = ss.name_ss_categories;
                    if (selectedSubCat && ss.id_ss_categories == selectedSubCat) {
                        option.selected = true;
                    }
                    subCategorySelect.appendChild(option);
                });
            });
    }

    // 🎯 Catégorie -> Sous-catégorie
    categorySelect.addEventListener("change", function() {
        const catId = this.value;
        if (catId) {
            loadSubCategories(catId);
        }
    });

    // 🎯 Genre -> Catégories
    genderRadios.forEach(radio => {
        radio.addEventListener("change", function() {
            if (this.checked) {
                loadCategories(this.value);
            }
        });
    });

    // 🛠 Préremplir les champs lors de la modification
    document.querySelectorAll(".editArticleBtn").forEach(button => {
        button.addEventListener("click", function() {
            modalTitle.textContent = "Modifier un Article";
            modal.classList.remove("hidden");

            // ✅ Champs
            actionInput.value = "update";
            articleId.value = this.dataset.id;
            articleName.value = this.dataset.name;
            articlePrice.value = this.dataset.price;
            document.getElementById("articleDiscountPrice").value = this.dataset.discount_price || '';
            articleStock.value = this.dataset.stock;
            articleDescription.value = this.dataset.description || '';
            articleBrand.value = this.dataset.brand || '';
            articleCode.value = this.dataset.code || '';
            articleSize.value = this.dataset.size || '';
            isRentable.value = this.dataset.rentable === "oui" ? "oui" : "non";

            // Genre
            genderRadios.forEach(radio => {
                radio.checked = radio.value === this.dataset.gender;
            });

            // Catégories & sous-catégories
            if (this.dataset.gender) {
                loadCategories(this.dataset.gender, this.dataset.category, this.dataset.subcategory);
            }

            // Aperçu image
            if (this.dataset.image) {
                previewImage.src = `uploads/${this.dataset.image}`;
                previewImage.classList.remove("hidden");
            } else {
                previewImage.classList.add("hidden");
            }
        });
    });

});
</script>