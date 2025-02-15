<?php 
include('src/app/Views/includes/admin_head.php');
include('src/app/Views/includes/admin_header.php'); 
?>

<div class="min-h-screen flex flex-col">
    <div class="container mx-auto px-4 py-12 flex-grow">
        <h1 class="text-4xl font-bold text-center mb-8 p-12 bg-black text-white">📊 Tableau de Bord</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
            <!-- Messages récents -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold text-gray-700 mb-2">📩 Nouveaux Messages</h2>
                <ul>
                    <?php if (!empty($dashboardData['messages'])) : ?>
                        <?php foreach ($dashboardData['messages'] as $msg) : ?>
                            <li class="border-b py-2">
                                <strong><?= htmlspecialchars($msg['name']); ?></strong> (<em><?= $msg['email']; ?></em>) :
                                <?= htmlspecialchars(substr($msg['message'], 0, 50)) . '...'; ?>
                                <span class="text-sm text-gray-500">[<?= $msg['created_at']; ?>]</span>
                            </li>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p class="text-gray-500">Aucun message pour le moment.</p>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Réservations événements -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold text-gray-700 mb-2">🎟️ Nouvelles Réservations d'Événements</h2>
                <ul>
                    <?php if (!empty($dashboardData['event_reservations'])) : ?>
                        <?php foreach ($dashboardData['event_reservations'] as $res) : ?>
                            <li class="border-b py-2">
                                <strong><?= htmlspecialchars($res['customer_name']); ?></strong> a réservé pour 
                                <strong><?= $res['participants']; ?> personnes</strong> à 
                                <em><?= htmlspecialchars($res['event_type']); ?></em>.
                                <span class="text-sm text-gray-500">[<?= $res['created_at']; ?>]</span>
                            </li>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p class="text-gray-500">Aucune réservation récente.</p>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Réservations de packs -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold text-gray-700 mb-2">🎁 Nouvelles Réservations de Packs</h2>
                <ul>
                    <?php if (!empty($dashboardData['pack_reservations'])) : ?>
                        <?php foreach ($dashboardData['pack_reservations'] as $pack) : ?>
                            <li class="border-b py-2">
                                <strong><?= htmlspecialchars($pack['customer_name']); ?></strong> a réservé 
                                le pack <em><?= htmlspecialchars($pack['pack_id']); ?></em>.
                                <span class="text-sm text-gray-500">[<?= $pack['created_at']; ?>]</span>
                            </li>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p class="text-gray-500">Aucune réservation de pack récente.</p>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Commandes récentes -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold text-gray-700 mb-2">🛒 Nouvelles Commandes</h2>
                <ul>
                    <?php if (!empty($dashboardData['orders'])) : ?>
                        <?php foreach ($dashboardData['orders'] as $order) : ?>
                            <li class="border-b py-2">
                                <strong>Commande #<?= $order['id']; ?></strong> de 
                                <strong><?= $order['total_price']; ?>€</strong> 
                                (Status: <span class="text-sm text-gray-600"><?= $order['status']; ?></span>).
                                <span class="text-sm text-gray-500">[<?= $order['created_at']; ?>]</span>
                            </li>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p class="text-gray-500">Aucune commande récente.</p>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <?php include('src/app/Views/includes/admin_footer.php'); ?>
</div>
