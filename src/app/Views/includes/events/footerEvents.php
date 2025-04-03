<footer class="bg-black text-white py-12 w-full">
    <div class="container mx-auto px-8 md:px-16 grid grid-cols-1 md:grid-cols-3 gap-12 text-center md:text-left text-lg">
        <div class="flex flex-col items-center md:items-start">
            <img src="assets/images/logo_magasin-chic.png" alt="Chic & Chill Logo" class="w-24 h-24" loading="lazy">
            <p class="mt-4 text-gray-300 leading-relaxed text-center md:text-left max-w-sm">
                <span class="font-semibold">Chic & Chill</span> est une boutique et service de location de vêtements proposant une mode responsable et accessible à tous.
            </p>
        </div>
        <div class="flex flex-col items-center md:items-start">
            <h3 class="font-semibold text-xl mb-4">Informations légales</h3>
            <ul class="space-y-3">
                <li><a href="conditions_generales" class="text-gray-300 hover:text-[#8B5A2B] transition">Conditions générales de ventes</a></li>
                <li><a href="#" class="text-gray-300 hover:text-[#8B5A2B] transition">Protection de la vie privée et des cookies</a></li>
                <li><a href="mentions_legales" class="text-gray-300 hover:text-[#8B5A2B] transition">Mentions légales</a></li>
                <li><a href="modes_paiement_shop" class="text-gray-300 hover:text-[#8B5A2B] transition">Les moyens de paiement</a></li>
            </ul>
        </div>
        <div class="flex flex-col items-center md:items-start">
            <h3 class="font-semibold text-xl mb-4">Contact</h3>
            <p class="text-gray-300">10 Rue Irénée Carré, Charleville-Mézières</p>
            <p class="text-gray-300">+33 7 81 26 64 56</p>
            <p class="text-gray-300">contact@chicandchill.fr</p>
            <div class="flex justify-center md:justify-start mt-4 space-x-6">
                <a href="https://www.facebook.com/chicandchill.emi" class="text-gray-300 hover:text-[#8B5A2B] transition" aria-label="Facebook"><i class="fa-brands fa-facebook-f text-3xl"></i></a>
                <a href="https://www.instagram.com/chic_and_chill08/" class="text-gray-300 hover:text-[#8B5A2B] transition" aria-label="Instagram"><i class="fa-brands fa-instagram text-3xl"></i></a>
                <a href="https://www.tiktok.com/@chicandchill08" class="text-gray-300 hover:text-[#8B5A2B] transition" aria-label="TikTok"><i class="fa-brands fa-tiktok text-3xl"></i></a>
            </div>
        </div>
    </div>
</footer>
<footer class="bg-white text-gray-500 text-center text-lg py-5 mt-0 w-full">
     © <?php echo date('Y'); ?> Chic & Chill - Tous droits réservés
</footer>

<button id="scrollToTop" class="fixed bottom-8 right-8 bg-[#8B5A2B] text-white p-4 rounded-full shadow-lg hidden transition duration-300 hover:scale-110" aria-label="Retour en haut">
    <i class="fa-solid fa-arrow-up text-xl"></i>
</button>

<script>
    const scrollToTopButton = document.getElementById("scrollToTop");
    window.addEventListener("scroll", () => {
        scrollToTopButton.classList.toggle("hidden", window.scrollY <= 200);
    });
    scrollToTopButton.addEventListener("click", () => {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    });

    window.addEventListener("scroll", () => {
        const header = document.getElementById("main-header");
        header.classList.toggle("scrolled", window.scrollY > 50);
    });

    document.getElementById("menu-toggle")?.addEventListener("click", () => {
        const mobileMenu = document.getElementById("mobile-menu");
        if (mobileMenu) mobileMenu.classList.toggle("hidden");
    });
</script>
<script src="src/js/evenements.js" defer></script>

<style>
    footer {
        width: 100%;
        max-width: 100%;
    }

    @media (min-width: 1920px) {
        footer {
            width: 100%;
            max-width: 100%;
        }
    }
</style>

</body>

</html>