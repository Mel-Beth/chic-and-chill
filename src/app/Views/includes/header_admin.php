<?php
$adminName = $adminName ?? 'Administrateur'; // Définit un nom par défaut si $adminName est null
$adminProfileImage = $adminProfileImage ?? 'assets/images/admin/default-avatar.png'; // Image par défaut
?>

<header class="bg-white shadow-md fixed top-0 left-64 right-0 z-10 flex items-center justify-between px-6 py-4">
    <h1 class="text-xl font-semibold text-gray-800">Bienvenue, <?= htmlspecialchars($adminName) ?></h1>
    <div class="flex items-center space-x-4">
        <div class="flex items-center space-x-2">
            <img src="<?= htmlspecialchars($adminProfileImage) ?>" alt="Admin" class="w-10 h-10 rounded-full border">
            <span class="text-gray-700"><?= htmlspecialchars($adminName) ?></span>
<<<<<<< HEAD:src/app/Views/includes/header_admin.php
            <span class="material-icons text-gray-600">arrow_drop_down</span>
            <script src="https://cdn.tailwindcss.com"></script>
=======
>>>>>>> 4f127196db2d6c2b23190ff4db8f385bafc44514:src/app/Views/includes/admin_header.php
        </div>
    </div>
</header>
