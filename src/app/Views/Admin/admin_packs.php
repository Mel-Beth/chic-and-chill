<?php 
include('src/app/Views/includes/admin_head.php');
include('src/app/Views/includes/admin_header.php'); 
?>

<div class="min-h-screen flex flex-col">
    <div class="container mx-auto px-4 py-12 flex-grow">
        <h2 class="text-4xl font-bold text-center mb-8 p-12 bg-black text-white">🎁 Gestion des Packs</h2>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <table class="w-full border-collapse border">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-3">Nom</th>
                        <th class="border p-3">Prix</th>
                        <th class="border p-3">Stock</th>
                        <th class="border p-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($packs as $pack) : ?>
                        <tr>
                            <td class="border p-3"><?= htmlspecialchars($pack['title']) ?></td>
                            <td class="border p-3"><?= htmlspecialchars($pack['price']) ?>€</td>
                            <td class="border p-3"><?= htmlspecialchars($pack['stock']) ?></td>
                            <td class="border p-3">
                                <a href="admin/packs/supprimer/<?= $pack['id'] ?>" class="text-red-600">🗑️ Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include('src/app/Views/includes/admin_footer.php'); ?>
</div>
