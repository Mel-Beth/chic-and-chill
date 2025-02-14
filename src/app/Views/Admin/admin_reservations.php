<?php
include('src/app/Views/includes/admin_head.php');
include('src/app/Views/includes/admin_header.php');
?>

<div class="bg-white p-6 rounded-lg shadow-md">
    <h2>🎟️ Réservations</h2>

    <?php if (empty($reservations)) : ?>
        <p>Aucune réservation en attente.</p>
    <?php else : ?>
        <table>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Type</th>
                <th>ID</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
            <?php foreach ($reservations as $res) : ?>
                <tr>
                    <td><?= htmlspecialchars($res['customer_name']) ?></td>
                    <td><?= htmlspecialchars($res['email']) ?></td>
                    <td><?= htmlspecialchars($res['phone']) ?></td>
                    <td><?= $res['type'] === 'event' ? 'Événement' : 'Pack' ?></td>
                    <td><?= htmlspecialchars($res['id']) ?></td>
                    <td><?= htmlspecialchars($res['status']) ?></td>
                    <td>
                        <a href="/admin/reservations/modifier/<?= $res['id'] ?>?status=confirmed">✅ Confirmer</a>
                        <a href="/admin/reservations/modifier/<?= $res['id'] ?>?status=cancelled">❌ Annuler</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>
<?php include('src/app/Views/includes/admin_footer.php'); ?>