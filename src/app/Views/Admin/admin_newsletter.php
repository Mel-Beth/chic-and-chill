<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-4">📬 Gestion des Abonnés Newsletter</h2>

    <table class="w-full border-collapse border">
        <thead>
            <tr class="bg-gray-200">
                <th class="border p-3">Email</th>
                <th class="border p-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($subscribers as $subscriber) : ?>
                <tr>
                    <td class="border p-3"><?= htmlspecialchars($subscriber['email']) ?></td>
                    <td class="border p-3">
                        <a href="<?= BASE_URL ?>admin/newsletter/supprimer/<?= $subscriber['id'] ?>" class="text-red-600">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
