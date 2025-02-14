<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-4">📜 Gestion des Réservations</h2>

    <table class="w-full border-collapse border">
        <thead>
            <tr class="bg-gray-200">
                <th class="border p-3">Nom</th>
                <th class="border p-3">Email</th>
                <th class="border p-3">Événement</th>
                <th class="border p-3">Statut</th>
                <th class="border p-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservations as $reservation) : ?>
                <tr>
                    <td class="border p-3"><?= htmlspecialchars($reservation['name']) ?></td>
                    <td class="border p-3"><?= htmlspecialchars($reservation['email']) ?></td>
                    <td class="border p-3"><?= htmlspecialchars($reservation['event_id']) ?></td>
                    <td class="border p-3"><?= htmlspecialchars($reservation['status']) ?></td>
                    <td class="border p-3">
                        <a href="<?= BASE_URL ?>admin/reservations/modifier/<?= $reservation['id'] ?>/confirmed" class="text-green-600">Confirmer</a>
                        <a href="<?= BASE_URL ?>admin/reservations/modifier/<?= $reservation['id'] ?>/cancelled" class="text-red-600">Annuler</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
