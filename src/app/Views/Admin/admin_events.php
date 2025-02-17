<?php
include('src/app/Views/includes/admin_head.php');
include('src/app/Views/includes/admin_header.php');
include('src/app/Views/includes/admin_sidebar.php');
?>

<div class="min-h-screen flex flex-col lg:pl-64 mt-12">
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="bg-green-500 text-white p-3 rounded-md mb-4">
            ✅ Événement ajouté avec succès !
        </div>
    <?php elseif (isset($_GET['success']) && $_GET['success'] == 0): ?>
        <div class="bg-red-500 text-white p-3 rounded-md mb-4">
            ❌ Une erreur est survenue, veuillez réessayer.
        </div>
    <?php endif; ?>

    <div class="container mx-auto px-6 py-8 flex-grow">
        <!-- En-tête -->
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
                                <a href="admin/evenements/modifier/<?= $event['id'] ?>" class="text-blue-600 hover:underline">Modifier</a>
                                <a href="supprimer/<?= $event['id'] ?>" class="text-red-600 hover:underline ml-4">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="mt-4 flex justify-center space-x-2" id="pagination"></div>
        </div>
    </div>

    <!-- Modale -->
    <div id="eventModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-lg w-full relative">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <span class="material-icons text-purple-600 mr-2">add_circle</span> Ajouter un événement
            </h3>
            <form action="evenements/ajouter" method="POST">
                <input type="text" name="title" placeholder="Titre de l'événement" required class="w-full p-3 border border-gray-300 rounded-md mb-2 focus:ring focus:ring-[#8B5A2B]">
                <textarea name="description" placeholder="Description" required class="w-full p-3 border border-gray-300 rounded-md mb-2 focus:ring focus:ring-[#8B5A2B]"></textarea>
                <input type="date" name="date_event" required class="w-full p-3 border border-gray-300 rounded-md mb-2 focus:ring focus:ring-[#8B5A2B]">
                <select name="status" class="w-full p-3 border border-gray-300 rounded-md mb-2 focus:ring focus:ring-[#8B5A2B]">
                    <option value="active">Actif</option>
                    <option value="inactive">Inactif</option>
                </select>
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

    // Exportation
    document.getElementById('exportBtn').addEventListener('click', function() {
        document.getElementById('exportOptions').classList.toggle('hidden');
    });

    // Gestion de la modale
    const openModal = document.getElementById("openModal");
    const closeModal = document.getElementById("closeModal");
    const eventModal = document.getElementById("eventModal");

    openModal.addEventListener("click", () => {
        eventModal.classList.remove("hidden");
    });

    closeModal.addEventListener("click", () => {
        eventModal.classList.add("hidden");
    });
</script>