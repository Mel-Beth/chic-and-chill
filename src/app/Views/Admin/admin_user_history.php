<?php
include('src/app/Views/includes/Admin/admin_head.php');
include('src/app/Views/includes/Admin/admin_header.php');
include('src/app/Views/includes/Admin/admin_sidebar.php');
?>

<div class="min-h-screen flex flex-col lg:pl-64 mt-12">
    <div class="container mx-auto px-6 py-8 flex-grow">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">📜 Historique de <?= htmlspecialchars($user['name']) ?></h2>
            <a href="admin/users" class="bg-gray-500 text-white px-5 py-2 rounded-lg hover:bg-gray-700 transition">Retour</a>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <?php if (empty($history)) : ?>
                <p class="text-gray-500 text-center">Aucune action enregistrée pour cet utilisateur.</p>
            <?php else : ?>
                <table class="w-full border-collapse border">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border p-3">Type</th>
                            <th class="border p-3">Description</th>
                            <th class="border p-3">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($history as $log) : ?>
                            <tr class="hover:bg-gray-100">
                                <td class="border p-3">
                                    <?php
                                    $type = [
                                        'historique' => '📜 Action',
                                        'commande' => '🛒 Commande',
                                        'reservation_evenement' => '🎟️ Réservation Événement',
                                        'reservation_pack' => '🎁 Réservation Pack',
                                        'paiement' => '💳 Paiement'
                                    ][$log['type']] ?? '🔍 Autre';
                                    echo $type;
                                    ?>
                                </td>
                                <td class="border p-3"><?= htmlspecialchars($log['description']) ?></td>
                                <td class="border p-3"><?= htmlspecialchars($log['date']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
