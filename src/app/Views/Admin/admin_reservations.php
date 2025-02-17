<?php 
include('src/app/Views/includes/admin_head.php');
include('src/app/Views/includes/admin_header.php'); 
include('src/app/Views/includes/admin_sidebar.php');
?>

<div class="min-h-screen flex flex-col lg:pl-64 mt-12">
    <div class="container mx-auto px-6 py-8 flex-grow">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">🎟️ Gestion des Réservations</h2>
        </div>

        <!-- Onglets de navigation -->
        <div class="flex border-b mb-4">
            <button class="tab-link px-4 py-2 text-lg font-semibold focus:outline-none active-tab" data-tab="pending">En attente 🟡</button>
            <button class="tab-link px-4 py-2 text-lg font-semibold focus:outline-none" data-tab="history">Historique 📜</button>
        </div>

        <!-- Recherche et filtres -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex justify-between mb-4">
                <input id="search" type="text" placeholder="Rechercher une réservation..." class="border px-4 py-2 rounded-md w-1/3 focus:ring focus:ring-[#8B5A2B]">
                <div class="flex space-x-4">
                    <button id="exportBtn" class="border px-4 py-2 rounded-md hover:bg-gray-100">Exporter</button>
                    <select id="sort" class="border px-4 py-2 rounded-md">
                        <option value="name">Trier par Nom</option>
                        <option value="date">Trier par Date</option>
                        <option value="status">Trier par Statut</option>
                    </select>
                </div>
            </div>

            <!-- Tableau des réservations -->
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
                    <?php foreach ($reservations as $res) : ?>
                        <tr class="hover:bg-gray-100 reservation-row" data-status="<?= $res['status'] ?>">
                            <td class="border p-3"> <?= htmlspecialchars($res['customer_name']) ?> </td>
                            <td class="border p-3"> <?= htmlspecialchars($res['email']) ?> </td>
                            <td class="border p-3"> <?= htmlspecialchars($res['phone']) ?> </td>
                            <td class="border p-3"> <?= $res['type'] === 'event' ? 'Événement' : 'Pack' ?> </td>
                            <td class="border p-3"> <?= htmlspecialchars($res['id']) ?> </td>
                            <td class="border p-3">
                                <span class="px-2 py-1 rounded-md text-white <?= $res['status'] === 'confirmed' ? 'bg-green-500' : ($res['status'] === 'cancelled' ? 'bg-red-500' : 'bg-yellow-500') ?>">
                                    <?= htmlspecialchars($res['status']) ?>
                                </span>
                            </td>
                            <td class="border p-3 flex space-x-4">
                                <?php if ($res['status'] === 'pending') : ?>
                                    <a href="admin/reservations/modifier/<?= $res['id'] ?>?status=confirmed" class="text-green-600 font-semibold hover:underline">✅ Accepter</a>
                                    <a href="admin/reservations/modifier/<?= $res['id'] ?>?status=cancelled" class="text-red-600 font-semibold hover:underline">❌ Refuser</a>
                                <?php elseif ($res['status'] === 'confirmed') : ?>
                                    <a href="admin/reservations/facture/<?= $res['id'] ?>" class="text-blue-600 font-semibold hover:underline">📄 Télécharger la facture</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="mt-4 flex justify-center space-x-2" id="pagination"></div>
        </div>
    </div>
</div>

<script>
    // Gestion des onglets
    document.querySelectorAll('.tab-link').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('.tab-link').forEach(btn => btn.classList.remove('active-tab'));
            this.classList.add('active-tab');
            
            let status = this.dataset.tab === 'pending' ? 'pending' : 'history';
            document.querySelectorAll('.reservation-row').forEach(row => {
                row.style.display = (status === 'pending' && row.dataset.status === 'pending') || (status === 'history' && row.dataset.status !== 'pending') ? '' : 'none';
            });
        });
    });

    // Recherche dynamique
    document.getElementById('search').addEventListener('input', function() {
        let filter = this.value.toLowerCase();
        document.querySelectorAll('#reservationTable tr').forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });

    // Pagination
    function paginateTable(rowsPerPage = 10) {
        let rows = document.querySelectorAll('#reservationTable tr');
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
