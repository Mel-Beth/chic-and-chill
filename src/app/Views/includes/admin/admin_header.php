<?php
// Définition d'un nom par défaut pour l'administrateur si non spécifié
$adminName = $adminName ?? 'Administrateur';
// Image de profil par défaut si aucune n'est fournie
$adminProfileImage = $adminProfileImage ?? 'assets/images/admin/default-avatar.png';
?>

<body>

    <!-- En-tête fixe avec une ombre légère, positionnée en haut de la page -->
    <header class="bg-white shadow-md fixed top-0 left-0 right-0 z-10 flex items-center justify-between px-4 py-3 md:px-6 md:py-4">
        <div class="flex items-center">
            <!-- Bouton hamburger pour mobile, masqué sur desktop -->
            <button id="menuToggle" class="md:hidden text-gray-800 focus:outline-none mr-2">
                <span class="material-icons">menu</span>
            </button>
        </div>
        <!-- Section profil avec lien de retour, image et nom -->
        <div class="flex items-center space-x-2 md:space-x-4">
            <!-- Lien pour revenir au site principal -->
            <a href="accueil_shop">Retour au site</a>
            <!-- Image de profil sécurisée avec htmlspecialchars pour éviter les injections XSS -->
            <img src="<?= htmlspecialchars($adminProfileImage) ?>" alt="Admin" class="w-8 h-8 rounded-full border md:w-10 md:h-10">
            <!-- Nom de l'admin, masqué sur mobile pour économiser de l'espace -->
            <span class="text-gray-700 text-sm md:text-base hidden md:inline"><?= htmlspecialchars($adminName) ?></span>
        </div>
    </header>

    <style>
        /* Ajustement responsive pour l'en-tête */
        @media (min-width: 768px) {
            .header {
                left: 256px;
                /* Décalage à gauche pour aligner avec la sidebar (w-64 = 256px) */
            }
        }
    </style>