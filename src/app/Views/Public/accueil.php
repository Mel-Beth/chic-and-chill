<!DOCTYPE html>
<html lang="fr">
<!-- Déclaration du document HTML avec langue française -->

<head>
    <!-- Encodage UTF-8 pour supporter les caractères spéciaux -->
    <meta charset="UTF-8">
    <!-- Configuration pour responsivité sur tous les appareils -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Inclusion du CSS compilé (probablement Tailwind CSS) -->
    <link href="src/css/output.css" rel="stylesheet">
    <!-- Inclusion d'un fichier CSS personnalisé -->
    <link href="src/css/style.css" rel="stylesheet">
    <!-- Inclusion de Swiper pour les carrousels (non utilisé ici mais chargé) -->
    <link rel="stylesheet" href="node_modules/swiper/swiper-bundle.min.css">
    <!-- Inclusion de Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Script Swiper pour les carrousels (non utilisé ici mais chargé) -->
    <script src="node_modules/swiper/swiper-bundle.min.js"></script>
    <!-- Titre de la page -->
    <title>Chic and Chill</title>

    <style>
        /* Styles personnalisés pour la police et les couleurs */
        .text-marron,
        .border-marron {
            font-family: 'Cormorant Garamond', serif !important;
            /* Police spécifique */
            color: #8B5A2B;
            /* Couleur marron */
            border-color: #8B5A2B;
            /* Bordure marron */
        }

        .text-gray {
            font-family: 'Cormorant Garamond', serif !important;
            /* Police spécifique */
            color: #4A4A4A;
            /* Couleur gris foncé */
        }

        /* Styles pour les cercles interactifs (desktop uniquement) */
        .circle-link {
            position: absolute;
            /* Positionnement absolu dans le conteneur */
            display: flex;
            /* Centrage du contenu */
            align-items: center;
            justify-content: center;
            border: 3px solid #8B5A2B;
            /* Bordure marron */
            background: rgba(255, 255, 255, 0.2);
            /* Fond blanc semi-transparent */
            backdrop-filter: blur(3px);
            /* Effet de flou derrière */
            border-radius: 50%;
            /* Forme circulaire */
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            /* Ombre portée */
            transition: transform 0.4s ease, box-shadow 0.4s ease, background 0.4s ease;
            /* Transitions fluides */
            z-index: 10;
            /* Au-dessus de l'image de fond */
        }

        .circle-link:hover {
            transform: scale(1.2);
            /* Agrandissement au survol */
            box-shadow: 0px 8px 18px rgba(0, 0, 0, 0.5);
            /* Ombre plus prononcée */
            background: #8B5A2B;
            /* Fond marron au survol */
        }

        .circle-link.active {
            background: #8B5A2B;
            /* Fond marron quand activé via JS */
            transform: scale(1.2);
            /* Agrandissement */
            box-shadow: 0px 8px 18px rgba(0, 0, 0, 0.5);
            /* Ombre forte */
        }

        /* Transition pour les étiquettes */
        #label-even,
        #label-location,
        #label-magasin {
            transition: color 0.4s ease;
            /* Changement de couleur fluide */
        }

        .label-active {
            color: white;
            /* Texte blanc quand activé via JS */
        }

        /* Animation d'apparition des étiquettes */
        @keyframes fadeIn {
            0% {
                opacity: 0;
            }

            /* Début invisible */
            100% {
                opacity: 1;
            }

            /* Fin visible */
        }

        .label-fade {
            animation: fadeIn 1s ease-in-out forwards;
            /* Animation d'apparition */
            opacity: 0;
            /* État initial */
        }

        /* Styles pour le menu mobile */
        #mobile-menu {
            position: fixed;
            /* Fixe sur l'écran */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #EFE7DD;
            /* Fond beige clair */
            z-index: 40;
            /* Sous l'en-tête mais au-dessus du contenu */
            transform: translateX(-100%);
            /* Caché à gauche par défaut */
            transition: transform 0.3s ease-in-out;
            /* Animation de glissement */
        }

        #mobile-menu.open {
            transform: translateX(0);
            /* Visible quand ouvert */
        }

        #mobile-menu .close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 2rem;
            color: #8B5A2B;
            /* Couleur marron */
        }

        #mobile-menu ul {
            display: flex;
            flex-direction: column;
            /* Liens en colonne */
            justify-content: center;
            align-items: center;
            height: 100%;
            gap: 2rem;
            /* Espacement entre les liens */
        }

        #mobile-menu ul li a {
            font-size: 1.5rem;
            color: #8B5A2B;
            /* Couleur marron */
            font-family: 'Cormorant Garamond', serif;
            font-weight: bold;
        }

        #mobile-menu ul li a:hover {
            color: #4A4A4A;
            /* Gris au survol */
        }

        /* Styles pour la section hero sur mobile */
        .hero-content-mobile {
            display: none;
            /* Masqué par défaut (affiché sur mobile via media query) */
            flex-direction: column;
            align-items: center;
            text-align: center;
            z-index: 10;
            /* Au-dessus de l'image de fond */
            padding: 1rem;
        }

        .hero-content-mobile .title-container {
            background: rgba(255, 255, 255, 0.5);
            /* Fond semi-transparent */
            backdrop-filter: blur(3px);
            /* Effet de flou */
            border: 3px solid #8B5A2B;
            /* Bordure marron */
            border-radius: 1rem;
            /* Coins arrondis */
            padding: 1rem 2rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            /* Ombre portée */
            margin-bottom: 1.5rem;
        }

        .hero-content-mobile h1 {
            font-size: 2rem;
            color: #8B5A2B;
            /* Couleur marron */
            font-family: 'Cormorant Garamond', serif;
            font-weight: bold;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            /* Ombre texte */
        }

        .hero-content-mobile p {
            font-size: 1.2rem;
            color: #4A4A4A;
            /* Gris foncé */
            font-family: 'Cormorant Garamond', serif;
            margin: 0.5rem 0 1.5rem;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            /* Ombre légère */
        }

        .hero-content-mobile .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            margin: 0.5rem 0;
            background-color: #8B5A2B;
            /* Fond marron */
            color: white;
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.1rem;
            font-weight: bold;
            border-radius: 0.375rem;
            /* Coins arrondis */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            /* Ombre */
            transition: transform 0.3s ease, background-color 0.3s ease;
            /* Transitions */
        }

        .hero-content-mobile .btn:hover {
            transform: scale(1.05);
            /* Agrandissement au survol */
            background-color: #6F4622;
            /* Marron plus foncé */
        }

        /* Masquer les cercles et labels par défaut avec haute spécificité */
        #hero-container #circle-main,
        #hero-container .circle-link,
        #hero-container #label-even,
        #hero-container #label-location,
        #hero-container #label-magasin {
            display: none !important;
            /* Masqué par défaut */
        }

        /* Afficher uniquement sur desktop */
        @media (min-width: 769px) {

            #hero-container #circle-main,
            #hero-container .circle-link,
            #hero-container #label-even,
            #hero-container #label-location,
            #hero-container #label-magasin {
                display: flex !important;
                /* Affiché sur desktop */
            }
        }

        /* Responsive : Tablettes */
        @media (max-width: 1024px) {
            #circle-main {
                width: 22vw !important;
                /* Réduction taille cercle principal */
                height: 22vw !important;
            }

            .circle-link {
                width: 10vw !important;
                /* Réduction taille cercles secondaires */
                height: 10vw !important;
            }
        }

        /* Responsive : Mobiles */
        @media (max-width: 768px) {
            header .logo {
                width: 3rem !important;
                /* Logo plus petit */
                height: 3rem !important;
            }

            header .title {
                font-size: 1.5rem !important;
                /* Titre plus petit */
            }

            /* Masquer cercles et labels (redondance pour sécurité) */
            #hero-container #circle-main,
            #hero-container .circle-link,
            #hero-container #label-even,
            #hero-container #label-location,
            #hero-container #label-magasin {
                display: none !important;
            }

            .hero-content-mobile {
                display: flex !important;
                /* Afficher section mobile */
            }

            #hero-container {
                display: flex;
                align-items: center;
                justify-content: center;
                /* Centrage contenu */
            }
        }

        /* Très petits écrans */
        @media (max-width: 480px) {
            header .logo {
                width: 3rem !important;
                height: 3rem !important;
            }

            header .title {
                font-size: 1.25rem !important;
                /* Titre encore plus petit */
            }

            .hero-content-mobile .title-container {
                padding: 0.75rem 1.5rem;
                /* Réduction padding */
            }

            .hero-content-mobile h1 {
                font-size: 1.5rem;
                /* Titre réduit */
            }

            .hero-content-mobile p {
                font-size: 1rem;
                /* Texte réduit */
            }

            .hero-content-mobile .btn {
                font-size: 1rem;
                padding: 0.5rem 1rem;
                /* Boutons plus petits */
            }
        }
    </style>
</head>

<body>
    <!-- En-tête fixe -->
    <header class="fixed top-0 left-0 w-full bg-[#EFE7DD] shadow-md z-50 flex justify-between items-center px-10 py-4">
        <div class="flex items-center space-x-4">
            <!-- Logo -->
            <img src="assets/images/logo_magasin-chic.png" alt="Chic & Chill Logo" class="w-20 h-20 object-contain logo">
            <!-- Titre -->
            <div class="text-[#8B5A2B] font-bold text-3xl tracking-wide title" style="font-family: 'Cormorant Garamond', serif;">
                CHIC <span class="text-gray-800">AND</span> CHILL
            </div>
        </div>

        <!-- Navigation desktop -->
        <nav class="hidden md:flex space-x-8 text-lg text-[#8B5A2B] font-semibold">
            <a href="accueil" class="hover:text-gray-800 transition">Accueil</a>
            <a href="evenements" class="hover:text-gray-800 transition">Événements</a>
            <a href="accueil_loc_show" class="hover:text-gray-800 transition">Location & Showroom</a>
            <a href="accueil_shop" class="hover:text-gray-800 transition">Magasin</a>
            <a href="connexion_shop" class="hover:text-gray-800 transition">Connexion</a>
        </nav>

        <!-- Bouton menu mobile -->
        <div class="md:hidden">
            <button id="menu-toggle" class="text-[#8B5A2B] text-2xl focus:outline-none">☰</button>
        </div>

        <!-- Menu mobile -->
        <div id="mobile-menu" class="hidden">
            <button id="close-menu" class="close-btn">✕</button>
            <ul>
                <li><a href="accueil">Accueil</a></li>
                <li><a href="evenements">Événements</a></li>
                <li><a href="accueil_loc_show">Location & Showroom</a></li>
                <li><a href="accueil_shop">Magasin</a></li>
                <li><a href="contact">Contact</a></li>
            </ul>
        </div>
    </header>

    <!-- Conteneur principal (hero) -->
    <div id="hero-container" class="relative w-full h-screen flex items-center justify-center overflow-hidden pt-[70px] mt-10">
        <!-- Image de fond -->
        <img src="assets/images/image_accueil.png" alt="Chic And Chill" class="absolute top-0 left-0 w-full h-full object-cover z-0">

        <!-- Section hero mobile -->
        <div class="hero-content-mobile">
            <div class="title-container">
                <h1>CHIC & CHILL</h1>
                <p>Mode responsable et accessible</p>
            </div>
            <a href="evenements" class="btn">Événements</a>
            <a href="accueil_loc_show" class="btn">Location & Showroom</a>
            <a href="accueil_shop" class="btn">Magasin</a>
        </div>

        <!-- Cercle central (desktop) -->
        <a href="accueil" id="circle-main" class="absolute flex justify-center items-center z-10 rounded-full border-[3px] border-[#8B5A2B] bg-white/20 backdrop-blur-md shadow-lg">
            <span id="chic" class="absolute text-[#8B5A2B] font-bold tracking-wide" style="font-family: 'Cormorant Garamond', serif;">CHIC</span>
            <span id="and" class="absolute text-gray-800 font-bold tracking-wide" style="font-family: 'Cormorant Garamond', serif;">&</span>
            <span id="chill" class="absolute text-[#8B5A2B] font-bold tracking-wide" style="font-family: 'Cormorant Garamond', serif;">CHILL</span>
        </a>

        <!-- Cercles cliquables (desktop) -->
        <a href="evenements" id="circle-even" class="circle-link"></a>
        <a href="accueil_loc_show" id="circle-location" class="circle-link"></a>
        <a href="accueil_shop" id="circle-magasin" class="circle-link"></a>

        <!-- Étiquettes interactives (desktop) -->
        <a href="evenements">
            <span id="label-even" class="absolute text-gray-800 font-bold tracking-wide z-10 label-fade" style="animation-delay: 0.6s;">
                EVENEMENTS
            </span>
        </a>
        <a href="accueil_loc_show">
            <span id="label-location" class="absolute text-gray-800 font-bold tracking-wide z-10 label-fade" style="animation-delay: 0.8s;">
                LOCATION & <br> SHOWROOM
            </span>
        </a>
        <a href="accueil_shop">
            <span id="label-magasin" class="absolute text-gray-800 font-bold tracking-wide z-10 label-fade" style="animation-delay: 1s;">
                MAGASIN
            </span>
        </a>
    </div>

    <!-- Pied de page principal -->
    <footer class="bg-[#EFE7DD] text-[#8B5A2B] py-12" style="font-family: 'Cormorant Garamond', serif;">
        <div class="container max-w-6xl mx-auto px-8 md:px-16 grid grid-cols-1 md:grid-cols-3 gap-12 text-center md:text-left text-lg">
            <!-- À propos -->
            <div class="flex flex-col items-center md:items-start">
                <img src="assets/images/logo_magasin-chic.png" alt="Chic & Chill Logo" class="w-24 h-24" loading="lazy">
                <p class="mt-4 text-[#4A4A4A] leading-relaxed text-center md:text-left max-w-sm" style="font-family: 'Cormorant Garamond', serif;">
                    <span class="font-semibold">Chic & Chill</span> est une boutique et service de location de vêtements proposant une mode responsable et accessible à tous.
                </p>
            </div>
            <!-- Informations légales -->
            <div class="flex flex-col items-center md:items-start">
                <h3 class="font-semibold text-xl mb-4 text-[#8B5A2B]" style="font-family: 'Cormorant Garamond', serif;">Informations légales</h3>
                <ul class="space-y-3">
                    <li><a href="conditions_generales" class="text-[#4A4A4A] hover:text-[#8B5A2B] transition" style="font-family: 'Cormorant Garamond', serif;">Conditions générales de ventes</a></li>
                    <li><a href="#" class="text-[#4A4A4A] hover:text-[#8B5A2B] transition" style="font-family: 'Cormorant Garamond', serif;">Protection de la vie privée et des cookies</a></li>
                    <li><a href="mentions_legales" class="text-[#4A4A4A] hover:text-[#8B5A2B] transition" style="font-family: 'Cormorant Garamond', serif;">Mentions légales</a></li>
                    <li><a href="modes_paiement_shop" class="text-[#4A4A4A] hover:text-[#8B5A2B] transition" style="font-family: 'Cormorant Garamond', serif;">Les moyens de paiement</a></li>
                </ul>
            </div>
            <!-- Contact -->
            <div class="flex flex-col items-center md:items-start">
                <h3 class="font-semibold text-xl mb-4 text-[#8B5A2B]" style="font-family: 'Cormorant Garamond', serif;">Contact</h3>
                <p class="text-[#4A4A4A]" style="font-family: 'Cormorant Garamond', serif;">10 Rue Irénée Carré, Charleville-Mézières</p>
                <p class="text-[#4A4A4A]" style="font-family: 'Cormorant Garamond', serif;">+33 7 81 26 64 56</p>
                <p class="text-[#4A4A4A]" style="font-family: 'Cormorant Garamond', serif;">contact@chicandchill.fr</p>
                <div class="flex justify-center md:justify-start mt-4 space-x-6">
                    <a href="https://www.facebook.com/chicandchill.emi" class="text-[#4A4A4A] hover:text-[#8B5A2B] transition" aria-label="Facebook"><i class="fa-brands fa-facebook-f text-3xl"></i></a>
                    <a href="https://www.instagram.com/chic_and_chill08/" class="text-[#4A4A4A] hover:text-[#8B5A2B] transition" aria-label="Instagram"><i class="fa-brands fa-instagram text-3xl"></i></a>
                    <a href="https://www.tiktok.com/@chicandchill08" class="text-[#4A4A4A] hover:text-[#8B5A2B] transition" aria-label="TikTok"><i class="fa-brands fa-tiktok text-3xl"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Footer secondaire (copyright) -->
    <footer class="bg-[#EFE7DD] text-[#8B5A2B] text-center text-lg py-5 mt-0 shadow-md" style="font-family: 'Cormorant Garamond', serif;">
        © <?php echo date('Y'); ?> Chic & Chill - Tous droits réservés
        <!-- Année dynamique avec PHP -->
    </footer>

    <!-- Bouton retour en haut -->
    <button id="scrollToTop" class="fixed bottom-8 right-8 bg-[#8B5A2B] text-white p-4 rounded-full shadow-lg hidden transition duration-300 hover:scale-110" aria-label="Retour en haut">
        <i class="fa-solid fa-arrow-up text-xl"></i>
    </button>

    <script>
        // Ajustement dynamique des éléments (desktop uniquement)
        function adjustElements() {
            let container = document.getElementById('hero-container');
            let width = container.offsetWidth;
            let height = container.offsetHeight;

            // Masquer éléments sur mobile
            let circleMain = document.getElementById('circle-main');
            let circleEven = document.getElementById('circle-even');
            let circleLocation = document.getElementById('circle-location');
            let circleMagasin = document.getElementById('circle-magasin');
            let labelEven = document.getElementById('label-even');
            let labelLocation = document.getElementById('label-location');
            let labelMagasin = document.getElementById('label-magasin');

            if (window.innerWidth < 768) {
                circleMain.style.display = 'none';
                circleEven.style.display = 'none';
                circleLocation.style.display = 'none';
                circleMagasin.style.display = 'none';
                labelEven.style.display = 'none';
                labelLocation.style.display = 'none';
                labelMagasin.style.display = 'none';
                return; // Arrête si mobile
            }

            // Ajustement cercle principal
            circleMain.style.display = 'flex';
            circleMain.style.width = width * 0.25 + "px";
            circleMain.style.height = width * 0.25 + "px";
            circleMain.style.left = "50%";
            circleMain.style.top = "48%";
            circleMain.style.transform = "translate(-50%, -50%)";

            let chic = document.getElementById('chic');
            let and = document.getElementById('and');
            let chill = document.getElementById('chill');

            let fontSize = width * 0.035 + "px";
            chic.style.fontSize = fontSize;
            and.style.fontSize = fontSize;
            chill.style.fontSize = fontSize;

            chic.style.top = "30%";
            chic.style.left = "15%";
            chic.style.transform = "translate(-20%, -10%)";

            and.style.top = "50%";
            and.style.left = "50%";
            and.style.transform = "translate(-50%, -50%)";

            chill.style.top = "70%";
            chill.style.right = "15%";
            chill.style.transform = "translate(8%, -80%)";

            // Ajustement cercles secondaires
            circleEven.style.display = 'flex';
            circleEven.style.width = width * 0.15 + "px";
            circleEven.style.height = width * 0.15 + "px";
            circleEven.style.left = "9%";
            circleEven.style.top = "12%";
            circleEven.style.transform = "translate(105%, 10%)";

            circleLocation.style.display = 'flex';
            circleLocation.style.width = width * 0.15 + "px";
            circleLocation.style.height = width * 0.15 + "px";
            circleLocation.style.right = "9%";
            circleLocation.style.top = "12%";
            circleLocation.style.transform = "translate(-135%, 60px)";

            circleMagasin.style.display = 'flex';
            circleMagasin.style.width = width * 0.11 + "px";
            circleMagasin.style.height = width * 0.11 + "px";
            circleMagasin.style.left = "10%";
            circleMagasin.style.bottom = "14%";
            circleMagasin.style.transform = "translate(195%, -45px)";

            // Ajustement étiquettes
            labelEven.style.display = 'block';
            labelEven.style.left = "9%";
            labelEven.style.top = "12%";
            labelEven.style.transform = "translate(142%, 290%)";
            labelEven.style.fontSize = width * 0.018 + "px";

            labelLocation.style.display = 'block';
            labelLocation.style.right = "9%";
            labelLocation.style.top = "12%";
            labelLocation.style.transform = "translate(-190%, 140px)";
            labelLocation.style.fontSize = width * 0.018 + "px";

            labelMagasin.style.display = 'block';
            labelMagasin.style.left = "10%";
            labelMagasin.style.bottom = "14%";
            labelMagasin.style.transform = "translate(255%, -113px)";
            labelMagasin.style.fontSize = width * 0.018 + "px";
        }

        // Gestion menu burger
        const menuToggle = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        const closeMenu = document.getElementById('close-menu');

        menuToggle.addEventListener('click', () => {
            mobileMenu.classList.remove('hidden');
            mobileMenu.classList.add('open');
        });

        closeMenu.addEventListener('click', () => {
            mobileMenu.classList.remove('open');
            setTimeout(() => {
                mobileMenu.classList.add('hidden');
            }, 300); // Délai pour animation
        });

        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('open');
                setTimeout(() => {
                    mobileMenu.classList.add('hidden');
                }, 300);
            });
        });

        // Gestion bouton "Retour en haut"
        const scrollToTopButton = document.getElementById("scrollToTop");
        window.addEventListener("scroll", () => {
            scrollToTopButton.classList.toggle("hidden", window.scrollY <= 200); // Visible après 200px de défilement
        });
        scrollToTopButton.addEventListener("click", () => {
            window.scrollTo({
                top: 0,
                behavior: "smooth" // Défilement fluide
            });
        });

        // Écouteurs pour ajustement dynamique
        window.addEventListener('resize', adjustElements);
        window.addEventListener('load', adjustElements);
        adjustElements(); // Appel immédiat

        // Gestion hover cercles et étiquettes
        const circleLabelPairs = {
            'circle-even': 'label-even',
            'circle-location': 'label-location',
            'circle-magasin': 'label-magasin'
        };

        Object.entries(circleLabelPairs).forEach(([circleId, labelId]) => {
            const circle = document.getElementById(circleId);
            const label = document.getElementById(labelId);

            circle.addEventListener('mouseover', () => {
                circle.classList.add('active');
                label.classList.add('label-active');
            });
            circle.addEventListener('mouseout', () => {
                circle.classList.remove('active');
                label.classList.remove('label-active');
            });

            label.parentElement.addEventListener('mouseover', () => {
                circle.classList.add('active');
                label.classList.add('label-active');
            });
            label.parentElement.addEventListener('mouseout', () => {
                circle.classList.remove('active');
                label.classList.remove('label-active');
            });
        });
    </script>
</body>

</html>