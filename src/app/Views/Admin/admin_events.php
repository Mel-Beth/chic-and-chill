<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-4">📅 Gestion des Événements</h2>

    <!-- Formulaire d'ajout d'événement -->
    <form action="<?= BASE_URL ?>admin/evenements/ajouter" method="POST" class="mb-6">
        <input type="text" name="title" placeholder="Titre de l'événement" required class="w-full p-3 border rounded-md">
        <textarea name="description" placeholder="Description" required class="w-full p-3 border rounded-md"></textarea>
        <input type="date" name="date_event" required class="w-full p-3 border rounded-md">
        <select name="status" class="w-full p-3 border rounded-md">
            <option value="active">Actif</option>
            <option value="inactive">Inactif</option>
        </select>
        <button type="submit" class="mt-4 bg-[#8B5A2B] text-white px-6 py-3 rounded-md">Ajouter</button>
    </form>

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
        <tbody>
            <?php foreach ($events as $event) : ?>
                <tr>
                    <td class="border p-3"><?= htmlspecialchars($event['title']) ?></td>
                    <td class="border p-3"><?= htmlspecialchars($event['date_event']) ?></td>
                    <td class="border p-3"><?= htmlspecialchars($event['status']) ?></td>
                    <td class="border p-3">
                        <a href="<?= BASE_URL ?>admin/evenements/modifier/<?= $event['id'] ?>" class="text-blue-600">Modifier</a>
                        <a href="<?= BASE_URL ?>admin/evenements/supprimer/<?= $event['id'] ?>" class="text-red-600">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
