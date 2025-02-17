<?php 
include('src/app/Views/includes/admin_head.php');
include('src/app/Views/includes/admin_header.php'); 
include('src/app/Views/includes/admin_sidebar.php');
?>

<div class="min-h-screen flex flex-col lg:pl-64 mt-12">
    <div class="container mx-auto px-6 py-8 flex-grow">
        <!-- En-tête -->
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">🎁 Gestion des Packs</h2>
            <button id="openModal" class="bg-black text-white px-5 py-2 rounded-lg flex items-center space-x-2 hover:bg-gray-800 transition">
                <span class="material-icons">add</span>
                <span>Ajouter un pack</span>
            </button>
        </div>

        <!-- Recherche et Export -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex justify-between mb-4">
                <input id="search" type="text" placeholder="Rechercher un pack..." class="border px-4 py-2 rounded-md w-1/3 focus:ring focus:ring-[#8B5A2B]">
                <div class="flex space-x-4">
                    <button id="exportBtn" class="border px-4 py-2 rounded-md hover:bg-gray-100">Exporter</button>
                    <select id="sort" class="border px-4 py-2 rounded-md">
                        <option value="title">Trier par Nom</option>
                        <option value="price">Trier par Prix</option>
                        <option value="stock">Trier par Stock</option>
                    </select>
                </div>
            </div>

            <!-- Liste des packs -->
            <table class="w-full border-collapse border">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-3">Nom</th>
                        <th class="border p-3">Prix</th>
                        <th class="border p-3">Stock</th>
                        <th class="border p-3">Actions</th>
                    </tr>
                </thead>
                <tbody id="packTable">
                    <?php foreach ($packs as $pack) : ?>
                        <tr class="hover:bg-gray-100">
                            <td class="border p-3"> <?= htmlspecialchars($pack['title']) ?> </td>
                            <td class="border p-3"> <?= htmlspecialchars($pack['price']) ?>€ </td>
                            <td class="border p-3"> <?= htmlspecialchars($pack['stock']) ?> </td>
                            <td class="border p-3">
                                <a href="admin/packs/modifier/<?= $pack['id'] ?>" class="text-blue-600 hover:underline">Modifier</a>
                                <a href="admin/packs/supprimer/<?= $pack['id'] ?>" class="text-red-600 hover:underline ml-4">🗑️ Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modale d'ajout -->
    <div id="packModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-lg w-full relative">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <span class="material-icons text-purple-600 mr-2">add_circle</span> Ajouter un pack
            </h3>
            <form action="admin/packs/ajouter" method="POST">
                <input type="text" name="title" placeholder="Nom du pack" required class="w-full p-3 border border-gray-300 rounded-md mb-2 focus:ring focus:ring-[#8B5A2B]">
                <input type="number" name="price" placeholder="Prix" required class="w-full p-3 border border-gray-300 rounded-md mb-2 focus:ring focus:ring-[#8B5A2B]">
                <input type="number" name="stock" placeholder="Stock" required class="w-full p-3 border border-gray-300 rounded-md mb-2 focus:ring focus:ring-[#8B5A2B]">
                <div class="flex justify-between mt-4">
                    <button type="button" id="closeModal" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 flex items-center">
                        ❌ Annuler
                    </button>
                    <button type="submit" class="bg-[#8B5A2B] text-white px-6 py-3 rounded-md hover:scale-105 transition flex items-center">
                        ✅ Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('search').addEventListener('input', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#packTable tr');
        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });

    document.getElementById('sort').addEventListener('change', function() {
        let rows = Array.from(document.querySelectorAll('#packTable tr'));
        let sortType = this.value;
        rows.sort((a, b) => {
            let valA = a.cells[sortType === 'title' ? 0 : sortType === 'price' ? 1 : 2].textContent.toLowerCase();
            let valB = b.cells[sortType === 'title' ? 0 : sortType === 'price' ? 1 : 2].textContent.toLowerCase();
            return valA.localeCompare(valB);
        });
        document.getElementById('packTable').append(...rows);
    });

    document.getElementById('exportBtn').addEventListener('click', function() {
        alert("Exportation en cours...");
    });

    const openModal = document.getElementById("openModal");
    const closeModal = document.getElementById("closeModal");
    const packModal = document.getElementById("packModal");

    openModal.addEventListener("click", () => {
        packModal.classList.remove("hidden");
    });

    closeModal.addEventListener("click", () => {
        packModal.classList.add("hidden");
    });
</script>
