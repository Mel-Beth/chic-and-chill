<?php
include('src/app/Views/includes/admin/admin_head.php');
include('src/app/Views/includes/admin/admin_header.php');
include('src/app/Views/includes/admin/admin_sidebar.php');
?>

<div class="min-h-screen flex flex-col lg:pl-64 mt-12">
    <div class="container mx-auto px-6 py-8 flex-grow">
        <h2 class="text-3xl font-bold text-gray-800 mb-8">Résultat de l'opération</h2>

        <?php if (isset($_SESSION['message'])) : ?>
            <div class="p-4 rounded-md <?php echo $_SESSION['message']['type'] === 'success' ? 'bg-green-100 text-green-800' : ($_SESSION['message']['type'] === 'warning' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'); ?>">
                <p class="font-semibold"><?php echo $_SESSION['message']['text']; ?></p>
            </div>
            <p class="mt-4 text-gray-600">Vous serez redirigé vers la liste des réservations dans quelques secondes...</p>
            <a href="admin/reservations" class="mt-2 inline-block text-blue-600 hover:underline">Retourner maintenant</a>
        <?php else : ?>
            <p class="text-gray-600">Aucun message disponible.</p>
            <a href="admin/reservations" class="mt-2 inline-block text-blue-600 hover:underline">Retourner à la liste des réservations</a>
        <?php endif; ?>
    </div>
</div>