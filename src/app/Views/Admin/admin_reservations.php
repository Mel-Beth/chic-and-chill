<?php 
include('src/app/Views/includes/admin_head.php');
include('src/app/Views/includes/admin_header.php'); 
?>

<div class="min-h-screen flex flex-col">
    <div class="container mx-auto px-4 py-12 flex-grow">
        <h2 class="text-4xl font-bold text-center mb-8 p-12 bg-black text-white">🎟️ Gestion des Réservations</h2>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <?php if (empty($reservations)) : ?>
                <p class="text-gray-500 text-center">Aucune réservation en attente.</p>
            <?php else : ?>
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
                    <tbody>
                        <?php foreach ($reservations as $res) : ?>
                            <tr class="hover:bg-gray-100">
                                <td class="border p-3"><?= htmlspecialchars($res['customer_name']) ?></td>
                                <td class="border p-3"><?= htmlspecialchars($res['email']) ?></td>
                                <td class="border p-3"><?= htmlspecialchars($res['phone']) ?></td>
                                <td class="border p-3"><?= $res['type'] === 'event' ? 'Événement' : 'Pack' ?></td>
                                <td class="border p-3"><?= htmlspecialchars($res['id']) ?></td>
                                <td class="border p-3">
                                    <span class="px-2 py-1 rounded-md text-white <?= $res['status'] === 'confirmed' ? 'bg-green-500' : 'bg-yellow-500' ?>">
                                        <?= htmlspecialchars($res['status']) ?>
                                    </span>
                                </td>
                                <td class="border p-3 flex space-x-4">
                                    <a href="admin/reservations/modifier/<?= $res['id'] ?>?status=confirmed" class="text-green-600 font-semibold hover:underline">✅ Confirmer</a>
                                    <a href="admin/reservations/modifier/<?= $res['id'] ?>?status=cancelled" class="text-red-600 font-semibold hover:underline">❌ Annuler</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <?php include('src/app/Views/includes/admin_footer.php'); ?>
</div>
