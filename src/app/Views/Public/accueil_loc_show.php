<?php include __DIR__ . '/../includes/header_loc.php';
?> <br><br><br><br>
<!-- Bannière principale -->
<section class="relative bg-[url('/site_stage/chic-and-chill/assets/images/banniere_accueil.webp')] bg-cover bg-center h-[30vh] flex items-center justify-center">
    <div class="bg-black bg-opacity-50 w-full h-full absolute top-0 left-0 z-0"></div>
    <div class="relative z-10 text-center text-white">
        <h1 class="text-5xl font-bold uppercase tracking-wider">Bienvenue chez Chic & Chill</h1>
        <p class="text-lg mt-4 max-w-2xl mx-auto">Explorez notre univers chic et bohème : location de tenues élégantes & showroom sur rendez-vous.</p>
    </div>
</section>

<!-- Section 2 encarts -->
<section class="py-16 px-6 bg-gray-100">
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12">
        <!-- Encart Location -->
        <a href="location" class="group block overflow-hidden rounded-xl shadow-lg hover:shadow-2xl bg-white transition transform hover:-translate-y-1">
            <img src="/site_stage/chic-and-chill/assets/images/robe_location.webp" alt="Louer une robe" class="w-full h-80 object-cover group-hover:scale-105 transition-transform duration-500">
            <div class="p-6 text-center">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Louer une robe</h2>
                <p class="text-gray-600 mb-4">Des tenues stylées pour toutes vos occasions, à petit prix.</p>
                <span class="inline-block px-5 py-2 bg-[#8B5A2B] text-white rounded hover:bg-[#B2AC88] transition">Découvrir</span>
            </div>
        </a>

        <!-- Encart Showroom -->
        <a href="showroom" class="group block overflow-hidden rounded-xl shadow-lg hover:shadow-2xl bg-white transition transform hover:-translate-y-1">
            <img src="/site_stage/chic-and-chill/assets/images/ShowroomMagasin.webp" alt="Réserver le showroom" class="w-full h-80 object-cover group-hover:scale-105 transition-transform duration-500">
            <div class="p-6 text-center">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Réserver le showroom</h2>
                <p class="text-gray-600 mb-4">Essayez vos robes en boutique, dans un espace privé et élégant.</p>
                <span class="inline-block px-5 py-2 bg-[#8B5A2B] text-white rounded hover:bg-[#B2AC88] transition">Prendre rendez-vous</span>
            </div>
        </a>
    </div>
</section>

<!-- Promo -->
<section class="bg-gradient-to-r from-[#B2AC88] to-[#8B5A2B] text-white py-12 text-center">
    <h2 class="text-2xl font-bold mb-4">-20% sur votre première commande avec le code <span class="underline">CHIC20</span></h2>
    <p>Offre valable sur la location et les rendez-vous showroom jusqu’au 30/06.</p>
</section>

<?php include __DIR__ . '/../includes/footer_loc.php';
?>