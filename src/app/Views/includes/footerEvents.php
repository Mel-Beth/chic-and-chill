<footer class="bg-black text-white py-12">
    <div class="container max-w-6xl mx-auto px-8 md:px-16 grid grid-cols-1 md:grid-cols-3 gap-12 text-center md:text-left text-lg">
        
        <!-- Bloc À propos (centré correctement) -->
        <div class="flex flex-col items-center md:items-start">
            <img src="<?= BASE_URL ?>assets/images/logo_magasin-chic.png" alt="Chic & Chill Logo" class="w-24 h-24">
            <p class="mt-4 text-gray-300 leading-relaxed text-center md:text-left max-w-sm">
                <span class="font-semibold">Chic & Chill</span> est une boutique et service de location de vêtements 
                proposant une mode responsable et accessible à tous.
            </p>
        </div>

        <!-- Bloc Notre actualité -->
        <div class="flex flex-col items-center md:items-start">
            <h4 class="font-semibold text-xl mb-4">Notre actualité</h4>
            <ul class="space-y-3">
                <li><a href="#" class="text-gray-300 hover:text-[#8B5A2B] transition">Nouvelle collection Printemps</a></li>
                <li><a href="#" class="text-gray-300 hover:text-[#8B5A2B] transition">Événements à venir</a></li>
                <li><a href="#" class="text-gray-300 hover:text-[#8B5A2B] transition">Nos engagements éco-responsables</a></li>
            </ul>
        </div>

        <!-- Bloc Contact -->
        <div class="flex flex-col items-center md:items-start">
            <h4 class="font-semibold text-xl mb-4">Contact</h4>
            <p class="text-gray-300">10 Rue Irénée Carré, Charleville-Mézières</p>
            <p class="text-gray-300">+33 7 81 26 64 56</p>
            <p class="text-gray-300">contact@chicandchill.com</p>
            
            <!-- Icônes Réseaux Sociaux -->
            <div class="flex justify-center md:justify-start mt-4 space-x-6">
                <a href="#" class="text-gray-300 hover:text-[#8B5A2B] transition">
                    <i class="fa-brands fa-facebook-f text-3xl"></i>
                </a>
                <a href="#" class="text-gray-300 hover:text-[#8B5A2B] transition">
                    <i class="fa-brands fa-instagram text-3xl"></i>
                </a>
                <a href="#" class="text-gray-300 hover:text-[#8B5A2B] transition">
                    <i class="fa-brands fa-pinterest text-3xl"></i>
                </a>
            </div>
        </div>
    </div>
</footer>

<!-- Mentions légales avec un espace doux et sans bordure -->
<footer class="bg-white text-gray-500 text-center text-lg py-5 mt-0">
    © 2025 Chic & Chill - Tous droits réservés | <a href="#" class="hover:text-[#8B5A2B] transition">Mentions Légales</a>
</footer>

<!-- Bouton Retour en Haut -->
<button id="scrollToTop" class="fixed bottom-8 right-8 bg-[#8B5A2B] text-white p-4 rounded-full shadow-lg hidden transition duration-300 hover:scale-110">
    <i class="fa-solid fa-arrow-up text-xl"></i>
</button>

<!-- FontAwesome pour les icônes sociales -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" crossorigin="anonymous"></script> 

<!-- Script pour le retour en haut -->
<script>
    // Sélectionne le bouton
    const scrollToTopButton = document.getElementById("scrollToTop");

    // Affiche/Masque le bouton selon la position du scroll
    window.addEventListener("scroll", () => {
        if (window.scrollY > 200) { // S'affiche après un défilement de 200px
            scrollToTopButton.classList.remove("hidden");
        } else {
            scrollToTopButton.classList.add("hidden");
        }
    });

    // Ajoute l'effet de retour en haut
    scrollToTopButton.addEventListener("click", () => {
        window.scrollTo({ top: 0, behavior: "smooth" });
    });
</script>
