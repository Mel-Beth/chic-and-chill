<?php
include('src/app/Views/includes/admin/admin_head.php');
include('src/app/Views/includes/admin/admin_header.php');
include('src/app/Views/includes/admin/admin_sidebar.php');
?>

<div class="min-h-screen flex flex-col pl-0 md:pl-64 mt-12">
    <div class="container mx-auto px-4 py-6 md:px-6 md:py-8 flex-grow">
        <h2 class="text-xl md:text-3xl font-bold text-gray-800 mb-6 md:mb-8">Résultat de l'opération</h2>

        <?php if (isset($_SESSION['message'])) : ?>
            <div class="p-3 md:p-4 rounded-md text-sm md:text-base <?php echo $_SESSION['message']['type'] === 'success' ? 'bg-green-100 text-green-800' : ($_SESSION['message']['type'] === 'warning' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'); ?>">
                <p class="font-semibold"><?php echo $_SESSION['message']['text']; ?></p>
            </div>
            <p class="mt-3 md:mt-4 text-gray-600 text-sm md:text-base">Vous serez redirigé vers la liste des réservations dans quelques secondes...</p>
            <a href="admin/reservations" class="mt-2 inline-block text-blue-600 hover:underline text-sm md:text-base">⬅️ Retourner maintenant</a>
        <?php else : ?>
            <p class="text-gray-600 text-sm md:text-base">Aucun message disponible.</p>
            <a href="admin/reservations" class="mt-2 inline-block text-blue-600 hover:underline text-sm md:text-base">Retourner à la liste des réservations</a>
        <?php endif; ?>
    </div>
</div>

<script>
    // Redirection automatique après 3 secondes si un message est présent
    <?php if (isset($_SESSION['message'])) : ?>
        setTimeout(function() {
            window.location.href = 'admin/reservations';
        }, 3000); // 3000ms = 3 secondes
    <?php endif; ?>
</script>