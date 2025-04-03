<?php
include('src/app/Views/includes/head_loc.php');
?>

<body class="bg-gray-100">
<nav class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-md shadow-md">
    <div class="mx-auto max-w-7xl px-4 py-4 flex items-center justify-between">
        <a href="index.php?page=home" class="flex items-center space-x-2">
            <img src="/site_stage/chic-and-chill/assets/images/logo_magasin-chic-mel.webp" alt="Chic & Chill Logo" class="h-20 w-auto object-contain">
        </a>
        <div class="md:hidden">
            <button id="menuButton" class="p-2 focus:outline-none">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </div>
        <div id="navLinks" class="hidden md:flex md:space-x-10 font-['Inter'] text-sm uppercase tracking-wider">
            <a href="accueil_shop" class="text-gray-800 hover:text-gray-600 transition-colors">Collection</a>
            <a href="showroom" class="text-gray-800 hover:text-gray-600 transition-colors">Showroom</a>
            <a href="location" class="text-gray-800 hover:text-gray-600 transition-colors">Location</a>
            <a href="evenements" class="text-gray-800 hover:text-gray-600 transition-colors">Événements</a>
            <a href="panier_loc" class="text-gray-800 hover:text-gray-600 transition-colors relative">
                Panier
                <span id="cartCount" class="absolute -top-2 -right-4 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
            </a>
            <div class="ml-4">
                <?php if (isset($_SESSION['username'])): ?>
                    <span class="text-gray-800">Bienvenue, <?= htmlspecialchars($_SESSION['username']); ?> | </span>
                    <a href="deconnexion_shop" class="text-gray-800 hover:text-gray-600 transition-colors">Déconnexion</a>
                <?php else: ?>
                    <a href="connexion_shop" class="text-gray-800 hover:text-gray-600 transition-colors">Connexion</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div id="mobileMenu" class="hidden md:hidden bg-white/90 absolute top-full left-0 right-0 shadow-md">
        <a href="accueil_shop" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Collection</a>
        <a href="showroom" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Showroom</a>
        <a href="location" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Location</a>
        <a href="evenements" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Événements</a>
        <a href="panier_loc" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Panier</a>
        <?php if (isset($_SESSION['username'])): ?>
            <span class="block px-4 py-2 text-gray-800">Bienvenue, <?= htmlspecialchars($_SESSION['username']); ?> | </span>
            <a href="deconnexion_shop" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Déconnexion</a>
        <?php else: ?>
            <a href="connexion_shop" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Connexion</a>
        <?php endif; ?>
    </div>
</nav>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const menuButton = document.getElementById("menuButton");
        const mobileMenu = document.getElementById("mobileMenu");
        menuButton.addEventListener("click", function () {
            mobileMenu.classList.toggle("hidden");
        });

        function updateCartCount() {
            const cart = JSON.parse(localStorage.getItem("cart")) || [];
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            document.getElementById("cartCount").textContent = totalItems;
        }
        updateCartCount();
        window.addEventListener("storage", updateCartCount);
    });
</script>