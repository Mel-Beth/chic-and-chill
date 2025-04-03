<?php include 'src/app/Views/includes/header_admin.php'; ?>
<?php include 'src/app/Views/includes/admin_sidebar.php'; ?>

<div class="ml-64 p-10">
    <h1 class="text-3xl font-bold mb-6">Gestion des Locations</h1>

    <?php if (!empty($reservations)): ?>
        <table class="w-full table-auto bg-white shadow-md rounded-lg">
            <thead>
                <tr class="bg-gray-200 text-gray-700">
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Nom du client</th>
                    <th class="px-4 py-2">Produit</th>
                    <th class="px-4 py-2">Date de début</th>
                    <th class="px-4 py-2">Date de fin</th>
                    <th class="px-4 py-2">Statut</th>
                    <th class="px-4 py-2">Date de création</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $reservation): ?>
                    <tr class="border-b border-gray-300">
                        <td class="px-4 py-2"><?= $reservation['id']; ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($reservation['client_nom']); ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($reservation['product_name']); ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($reservation['date_debut']); ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($reservation['date_fin']); ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($reservation['statut']); ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($reservation['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucune réservation trouvée.</p>
    <?php endif; ?>
</div>

<?php include 'src/app/Views/includes/footer_admin.php'; ?>