<header id="main-header" class="fixed top-0 left-0 w-full z-50 transition-all duration-300 bg-transparent">
    <div class="container mx-auto flex justify-between items-center px-10 py-3">
        <div class="flex items-center space-x-4">
            <a href="accueil">
                <img src="assets/images/logo_magasin-chic.png" alt="Chic & Chill Logo" class="w-20 h-20 object-contain">
            </a>
            <div class="text-white font-bold text-3xl tracking-wide transition-all duration-300" id="brand-text" style="font-family: 'Cormorant Garamond', serif;">
                <span class="brand-chic">CHIC</span> <span class="text-[#8B5A2B]">AND</span> <span class="brand-chill">CHILL</span>
            </div>
        </div>
        <nav class="hidden md:flex space-x-8 text-lg font-semibold transition-all duration-300">
            <a href="evenements" class="menu-link relative" aria-label="Accueil"><i class="fas fa-calendar-alt"></i> Accueil</a>
            <a href="accueil_loc_show" class="menu-link relative" aria-label="Location"><i class="fas fa-tshirt"></i> Location & Showroom</a>
            <a href="accueil_shop" class="menu-link relative" aria-label="Magasin"><i class="fas fa-shopping-bag"></i> Magasin</a>
            <a href="localisation" class="menu-link relative" aria-label="Nous trouver"><i class="fas fa-map-marker-alt"></i> Nous trouver</a>
            <a href="contact_evenements" class="menu-link relative" aria-label="Nous contacter"><i class="fas fa-envelope"></i> Nous contacter</a>
        </nav>
        <div class="md:hidden">
            <button id="menu-toggle" class="text-white focus:outline-none text-2xl" aria-label="Ouvrir le menu mobile"><i class="fas fa-bars"></i></button>
        </div>

        <!-- Menu mobile -->
        <div id="mobile-menu" class="hidden">
            <button id="close-menu" class="close-btn">✕</button>
            <ul>
                <li><a href="evenements"><i class="fas fa-calendar-alt"></i> Accueil</a></li>
                <li><a href="location"><i class="fas fa-tshirt"></i> Location</a></li>
                <li><a href="accueil_shop"><i class="fas fa-shopping-bag"></i> Magasin</a></li>
                <li><a href="localisation"><i class="fas fa-map-marker-alt"></i> Nous trouver</a></li>
                <li><a href="contact_evenements"><i class="fas fa-envelope"></i> Nous contacter</a></li>
            </ul>
        </div>
    </div>
</header>

<script src="src/js/headerEvents.js"></script>