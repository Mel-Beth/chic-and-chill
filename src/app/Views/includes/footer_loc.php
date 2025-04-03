<!-- Footer Loc -->
<footer class="bg-[#1a1a1a] text-white py-10 mt-16">
    <div class="container mx-auto px-6">
        <!-- Lignes principales -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-10">
            <!-- Logo + Description -->
            <div class="flex flex-col items-center md:items-start">
                <img src="/site_stage/chic-and-chill/assets/images/logo_magasin-chic-mel.webp" alt="Chic & Chill Logo" class="h-20 w-auto mb-4">
                <p class="text-gray-400 text-sm text-center md:text-left max-w-sm">
                    Chic & Chill - Location de robes élégantes et showroom privatisé. Découvrez nos collections pour toutes vos occasions.
                </p>
            </div>

            <!-- Liens de navigation -->
            <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-12 mt-6 md:mt-0">
                <a href="/accueil_shop" class="text-gray-400 hover:text-white transition">Collection</a>
                <a href="/showroom" class="text-gray-400 hover:text-white transition">Showroom</a>
                <a href="/location" class="text-gray-400 hover:text-white transition">Location</a>
                <a href="/evenements" class="text-gray-400 hover:text-white transition">Événements</a>
                <a href="/contact_location" class="text-gray-400 hover:text-white transition">Contact</a>
            </div>
        </div>

        <!-- Réseaux sociaux -->
        <div class="flex justify-center space-x-6 mb-10">
            <a href="https://www.facebook.com/ChicAndChill" target="_blank" class="text-gray-400 hover:text-white transition">
                <i class="fab fa-facebook-f text-xl"></i>
            </a>
            <a href="https://www.instagram.com/ChicAndChill" target="_blank" class="text-gray-400 hover:text-white transition">
                <i class="fab fa-instagram text-xl"></i>
            </a>
            <a href="https://www.tiktok.com/@ChicAndChill" target="_blank" class="text-gray-400 hover:text-white transition">
                <i class="fab fa-tiktok text-xl"></i>
            </a>
        </div>

        <!-- Ligne de séparation -->
        <div class="border-t border-gray-700 mt-10 mb-6"></div>

        <!-- Copyright -->
        <div class="text-center text-gray-400 text-sm">
            &copy; <?= date('Y'); ?> Chic & Chill. Tous droits réservés.
        </div>
    </div>
</footer>

<!-- FontAwesome Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">