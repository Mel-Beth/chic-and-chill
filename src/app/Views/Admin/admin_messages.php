<?php
include('src/app/Views/includes/admin_head.php');
include('src/app/Views/includes/admin_header.php');
?>

<div class="container mx-auto mt-6">
    <h2>📩 Messages reçus</h2>

    <?php if (empty($messages)) : ?>
        <p>Aucun message reçu.</p>
    <?php else : ?>
        <table>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Message</th>
                <th>Source</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            <?php foreach ($messages as $message) : ?>
                <tr>
                    <td><?= htmlspecialchars($message['name']) ?></td>
                    <td><?= htmlspecialchars($message['email']) ?></td>
                    <td><?= htmlspecialchars($message['message']) ?></td>
                    <td><?= htmlspecialchars($message['source']) ?></td>
                    <td><?= htmlspecialchars($message['created_at']) ?></td>
                    <td>
                        <a href="/admin/messages/supprimer/<?= $message['id'] ?>" onclick="return confirm('Supprimer ce message ?')">🗑️</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>
<?php include('src/app/Views/includes/admin_footer.php'); ?>