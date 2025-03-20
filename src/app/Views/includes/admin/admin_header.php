<?php
$adminName = $adminName ?? 'Administrateur'; // Définit un nom par défaut si $adminName est null
$adminProfileImage = $adminProfileImage ?? 'assets/images/admin/default-avatar.png'; // Image par défaut
?>

<header class="bg-white shadow-md fixed top-0 left-0 right-0 z-10 flex items-center justify-between px-4 py-3 md:px-6 md:py-4">
    <div class="flex items-center">
        <!-- Bouton Hamburger (visible sur mobile uniquement) -->
        <button id="menuToggle" class="md:hidden text-gray-800 focus:outline-none mr-2">
            <span class="material-icons">menu</span>
        </button>
        <h1 class="text-lg font-semibold text-gray-800 md:text-xl"><?= htmlspecialchars($adminName) ?></h1>
    </div>
    <div class="flex items-center space-x-2 md:space-x-4">
        <img src="<?= htmlspecialchars($adminProfileImage) ?>" alt="Admin" class="w-8 h-8 rounded-full border md:w-10 md:h-10">
        <span class="text-gray-700 text-sm md:text-base hidden md:inline"><?= htmlspecialchars($adminName) ?></span>
    </div>
</header>

<style>
    /* Ajustements responsive pour header */
    @media (min-width: 768px) {
        .header {
            left: 256px; /* Correspond à w-64 de la sidebar */
        }
    }
</style>