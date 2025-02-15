<?php 
include('src/app/Views/includes/admin_head.php');
include('src/app/Views/includes/admin_header.php'); 
?>

<div class="min-h-screen flex flex-col">
    <div class="container mx-auto px-4 py-12 flex-grow">
        <h2 class="text-4xl font-bold text-center mb-8 p-12 bg-black text-white">📅 Gestion des Événements</h2>

        <!-- Formulaire d'ajout -->
        <form action="admin/evenements/ajouter" method="POST" class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-md">
            <h3 class="text-xl font-bold text-gray-800 mb-4">➕ Ajouter un événement</h3>

            <input type="text" name="title" placeholder="Titre de l'événement" required class="w-full p-3 border border-gray-300 rounded-md">
            <textarea name="description" placeholder="Description" required class="w-full p-3 border border-gray-300 rounded-md"></textarea>
            <input type="date" name="date_event" required class="w-full p-3 border border-gray-300 rounded-md">
            <select name="status" class="w-full p-3 border border-gray-300 rounded-md">
                <option value="active">Actif</option>
                <option value="inactive">Inactif</option>
            </select>

            <button type="submit" class="mt-6 bg-[#8B5A2B] text-white px-6 py-3 rounded-md w-full hover:scale-105 transition">
                ✅ Ajouter
            </button>
        </form>

        <!-- Liste des événements -->
        <div class="mt-12">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">📅 Liste des événements</h3>
            <div class="bg-white p-6 rounded-lg shadow-md">
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
                                    <a href="admin/evenements/modifier/<?= $event['id'] ?>" class="text-blue-600">Modifier</a>
                                    <a href="admin/evenements/supprimer/<?= $event['id'] ?>" class="text-red-600">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include('src/app/Views/includes/admin_footer.php'); ?>
</div>
