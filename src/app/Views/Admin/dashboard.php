<?php 
include('src/app/Views/includes/admin_head.php');
include('src/app/Views/includes/admin_header.php'); 
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">🔔 Notifications & Activités Récentes</h1>

    <!-- Section Messages -->
    <div class="bg-white shadow-lg rounded-lg p-4 mb-4">
        <h2 class="text-xl font-bold text-gray-700 mb-2">📩 Nouveaux Messages</h2>
        <ul>
            <?php if (isset($dashboardData['messages']) && is_array($dashboardData['messages'])): ?>
                <?php foreach ($dashboardData['messages'] as $msg): ?>
                    <li class="border-b py-2">
                        <strong><?= htmlspecialchars($msg['name']); ?></strong> (<em><?= $msg['email']; ?></em>) :
                        <?= htmlspecialchars(substr($msg['message'], 0, 50)) . '...'; ?>
                        <span class="text-sm text-gray-500">[<?= $msg['created_at']; ?>]</span>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-500">Aucun message pour le moment.</p>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Section Réservations d'Événements -->
    <div class="bg-white shadow-lg rounded-lg p-4 mb-4">
        <h2 class="text-xl font-bold text-gray-700 mb-2">🎟️ Nouvelles Réservations d'Événements</h2>
        <ul>
            <?php if (isset($dashboardData['event_reservations']) && is_array($dashboardData['event_reservations'])): ?>
                <?php foreach ($dashboardData['event_reservations'] as $res): ?>
                    <li class="border-b py-2">
                        <strong><?= htmlspecialchars($res['customer_name']); ?></strong> a réservé
                        pour <strong><?= $res['participants']; ?> personnes</strong> à 
                        <em><?= htmlspecialchars($res['event_type']); ?></em>.
                        <span class="text-sm text-gray-500">[<?= $res['created_at']; ?>]</span>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-500">Aucune réservation récente.</p>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Section Réservations de Packs -->
    <div class="bg-white shadow-lg rounded-lg p-4 mb-4">
        <h2 class="text-xl font-bold text-gray-700 mb-2">🎁 Nouvelles Réservations de Packs</h2>
        <ul>
            <?php if (isset($dashboardData['pack_reservations']) && is_array($dashboardData['pack_reservations'])): ?>
                <?php foreach ($dashboardData['pack_reservations'] as $pack): ?>
                    <li class="border-b py-2">
                        <strong><?= htmlspecialchars($pack['customer_name']); ?></strong> a réservé
                        le pack <em><?= htmlspecialchars($pack['pack_id']); ?></em>.
                        <span class="text-sm text-gray-500">[<?= $pack['created_at']; ?>]</span>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-500">Aucune réservation de pack récente.</p>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Section Commandes -->
    <div class="bg-white shadow-lg rounded-lg p-4 mb-4">
        <h2 class="text-xl font-bold text-gray-700 mb-2">🛒 Nouvelles Commandes</h2>
        <ul>
            <?php if (isset($dashboardData['orders']) && is_array($dashboardData['orders'])): ?>
                <?php foreach ($dashboardData['orders'] as $order): ?>
                    <li class="border-b py-2">
                        <strong>Commande #<?= $order['id']; ?></strong> de <strong><?= $order['total_price']; ?>€</strong>
                        (Status: <span class="text-sm text-gray-600"><?= $order['status']; ?></span>).
                        <span class="text-sm text-gray-500">[<?= $order['created_at']; ?>]</span>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-500">Aucune commande récente.</p>
            <?php endif; ?>
        </ul>
    </div>
</div>

<?php include('src/app/Views/includes/admin_footer.php'); ?>