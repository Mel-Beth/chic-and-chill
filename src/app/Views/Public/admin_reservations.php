<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php?page=login");
    exit;
}
require_once 'models/VetementModel.php';
$model = new VetementModel();
$reservations = $model->getReservations(); // À ajouter
$rentals = $model->getRentals(); // À ajouter
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Réservations & Locations</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">
    <h1 class="text-4xl font-bold text-center mb-8">Gestion des Réservations & Locations</h1>

    <section class="mb-12">
        <h2 class="text-2xl font-semibold mb-4">Réservations Showroom</h2>
        <table class="w-full bg-white shadow-lg rounded-lg">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-4">ID</th>
                    <th class="p-4">Client</th>
                    <th class="p-4">Date</th>
                    <th class="p-4">Heure</th>
                    <th class="p-4">Statut</th>
                    <th class="p-4">Actions</th>
                </tr>
            </thead>
            <tbody id="reservationsTable">
                <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td class="p-4"><?php echo $reservation['id']; ?></td>
                        <td class="p-4"><?php echo htmlspecialchars($reservation['client_nom']); ?></td>
                        <td class="p-4"><?php echo $reservation['date_reservation']; ?></td>
                        <td class="p-4"><?php echo $reservation['heure_reservation']; ?></td>
                        <td class="p-4"><?php echo $reservation['statut']; ?></td>
                        <td class="p-4">
                            <button class="updateStatusBtn" data-id="<?php echo $reservation['id']; ?>">Modifier Statut</button>
                            <button class="deleteBtn" data-id="<?php echo $reservation['id']; ?>">Supprimer</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section>
        <h2 class="text-2xl font-semibold mb-4">Locations de Produits</h2>
        <table class="w-full bg-white shadow-lg rounded-lg">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-4">ID</th>
                    <th class="p-4">Produit</th>
                    <th class="p-4">Début</th>
                    <th class="p-4">Fin</th>
                    <th class="p-4">Statut</th>
                    <th class="p-4">Actions</th>
                </tr>
            </thead>
            <tbody id="rentalsTable">
                <?php foreach ($rentals as $rental): ?>
                    <tr>
                        <td class="p-4"><?php echo $rental['id']; ?></td>
                        <td class="p-4"><?php echo $rental['product_id']; ?></td>
                        <td class="p-4"><?php echo $rental['start_date']; ?></td>
                        <td class="p-4"><?php echo $rental['end_date']; ?></td>
                        <td class="p-4"><?php echo $rental['status']; ?></td>
                        <td class="p-4">
                            <button class="updateStatusBtn" data-id="<?php echo $rental['id']; ?>">Modifier Statut</button>
                            <button class="deleteBtn" data-id="<?php echo $rental['id']; ?>">Supprimer</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <script>
    // Réutilise ton JS existant ici si besoin (pagination, suppression, etc.)
    </script>
</body>
</html>