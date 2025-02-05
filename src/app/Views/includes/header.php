<header class="fixed top-0 left-0 w-full bg-[#EFE7DD] shadow-md z-50 flex justify-between items-center px-10 py-4">
    <!-- Logo + Texte -->
    <div class="flex items-center space-x-4">
        <!-- Logo -->
        <img src="assets/images/logo.png" alt="Chic & Chill Logo" class="w-20 h-20 object-contain">
        
        <!-- Texte CHIC AND CHILL -->
        <div class="text-[#8B5A2B] font-bold text-3xl tracking-wide">
            CHIC <span class="text-gray-800">AND</span> CHILL
        </div>
    </div>

    <!-- Menu -->
    <nav class="hidden md:flex space-x-8 text-lg text-[#8B5A2B] font-semibold">
        <a href="index.php" class="hover:text-gray-700 transition">Accueil</a>
        <a href="evenements.php" class="hover:text-gray-700 transition">Événements</a>
        <a href="location.php" class="hover:text-gray-700 transition">Location</a>
        <a href="magasin.php" class="hover:text-gray-700 transition">Magasin</a>
        <a href="contact.php" class="hover:text-gray-700 transition">Contact</a>
    </nav>

    <!-- Menu mobile -->
    <div class="md:hidden">
        <button id="menu-toggle" class="text-[#8B5A2B] focus:outline-none">☰</button>
    </div>
</header>

<!-- Menu mobile -->
<div id="mobile-menu" class="hidden md:hidden absolute top-16 left-0 w-full bg-[#EFE7DD] text-[#8B5A2B] text-center py-4 space-y-4">
    <a href="index.php" class="block hover:text-gray-700">Accueil</a>
    <a href="evenements.php" class="block hover:text-gray-700">Événements</a>
    <a href="location.php" class="block hover:text-gray-700">Location</a>
    <a href="magasin.php" class="block hover:text-gray-700">Magasin</a>
    <a href="contact.php" class="block hover:text-gray-700">Contact</a>
</div>

<script>
    document.getElementById('menu-toggle').addEventListener('click', function () {
        let menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });
</script>
