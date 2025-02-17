<?php
include('src/app/Views/includes/admin_head.php');
include('src/app/Views/includes/admin_header.php');
include('src/app/Views/includes/admin_sidebar.php');
?>

<div class="min-h-screen flex flex-col lg:pl-64 mt-12">
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="bg-green-500 text-white p-3 rounded-md mb-4">
            ✅ Tenue ajoutée/modifiée avec succès !
        </div>
    <?php elseif (isset($_GET['success']) && $_GET['success'] == 0): ?>
        <div class="bg-red-500 text-white p-3 rounded-md mb-4">
            ❌ Une erreur est survenue, veuillez réessayer.
        </div>
    <?php endif; ?>

    <div class="container mx-auto px-6 py-8 flex-grow">
        <!-- En-tête -->
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($outfits as $outfit) : ?>
                    <div class="bg-white p-6 rounded-lg shadow-md flex flex-col">
                        <h4 class="text-xl font-semibold"> <?= htmlspecialchars($outfit['outfit_name']); ?> </h4>
                        <p class="text-gray-600"> <?= htmlspecialchars($outfit['accessories']); ?> </p>
                        <img src="<?= htmlspecialchars($outfit['image'] ?: 'assets/images/events/placeholder.jpg'); ?>" alt="Image tenue" class="mt-3 rounded-md w-full h-40 object-cover">
                        <div class="flex justify-between mt-4">
                            <a href="admin/outfits/modifier/<?= $outfit['id'] ?>" class="text-blue-600 hover:underline">✏️ Modifier</a>
                            <a href="admin/outfits/supprimer/<?= $outfit['id'] ?>" class="text-red-600 hover:underline">🗑️ Supprimer</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <div class="mt-4 flex justify-center space-x-2" id="pagination"></div>
        </div>
    </div>

    <!-- Modale -->
    <div id="outfitModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-lg w-full relative">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <span class="material-icons text-purple-600 mr-2">add_circle</span> Ajouter une tenue
            </h3>
            <form action="admin/outfits/ajouter" method="POST">
                <input type="text" name="title" placeholder="Nom de la tenue" required class="w-full p-3 border border-gray-300 rounded-md mb-2 focus:ring focus:ring-[#8B5A2B]">
                <textarea name="description" placeholder="Description" required class="w-full p-3 border border-gray-300 rounded-md mb-2 focus:ring focus:ring-[#8B5A2B]"></textarea>
                <input type="text" name="image" placeholder="Lien vers l'image" class="w-full p-3 border border-gray-300 rounded-md mb-2 focus:ring focus:ring-[#8B5A2B]">
                <div class="flex justify-between mt-4">
                    <button type="button" id="closeModal" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">❌ Annuler</button>
                    <button type="submit" class="bg-[#8B5A2B] text-white px-6 py-3 rounded-md hover:scale-105 transition">✅ Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Recherche dynamique
    document.getElementById('search').addEventListener('input', function() {
        let filter = this.value.toLowerCase();
        let items = document.querySelectorAll('.grid div');
        items.forEach(item => {
            let text = item.textContent.toLowerCase();
            item.style.display = text.includes(filter) ? '' : 'none';
        });
    });

    // Tri des tenues
    document.getElementById('sort').addEventListener('change', function() {
        let items = Array.from(document.querySelectorAll('.grid div'));
        let sortType = this.value;
        items.sort((a, b) => {
            let valA = a.querySelector('h4').textContent.toLowerCase();
            let valB = b.querySelector('h4').textContent.toLowerCase();
            return valA.localeCompare(valB);
        });
        document.querySelector('.grid').append(...items);
    });

    // Gestion de la modale
    const openModal = document.getElementById("openModal");
    const closeModal = document.getElementById("closeModal");
    const outfitModal = document.getElementById("outfitModal");

    openModal.addEventListener("click", () => {
        outfitModal.classList.remove("hidden");
    });

    closeModal.addEventListener("click", () => {
        outfitModal.classList.add("hidden");
    });
</script>