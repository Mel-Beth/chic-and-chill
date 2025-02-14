<?php 
include('src/app/Views/includes/admin_head.php');
include('src/app/Views/includes/admin_header.php'); 
?>

<div class="container mx-auto mt-6">
    <h2 class="text-2xl font-bold mb-4">Gestion des Packs</h2>
    <?php if (!empty($packs)): ?>
        <table class="w-full border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-4 py-2">Nom</th>
                    <th class="border border-gray-300 px-4 py-2">Prix</th>
                    <th class="border border-gray-300 px-4 py-2">Stock</th>
                    <th class="border border-gray-300 px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($packs as $pack): ?>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($pack['title']) ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?= number_format($pack['price'], 2) ?> €</td>
                        <td class="border border-gray-300 px-4 py-2"><?= $pack['stock'] ?></td>
                        <td class="border border-gray-300 px-4 py-2">
                            <a href="admin/packs/edit/<?= $pack['id'] ?>" class="text-blue-500">Modifier</a> |
                            <a href="admin/packs/delete/<?= $pack['id'] ?>" class="text-red-500">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucun pack disponible.</p>
    <?php endif; ?>
</div>


<?php include('src/app/Views/includes/admin_footer.php'); ?>