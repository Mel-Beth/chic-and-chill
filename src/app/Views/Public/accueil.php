<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="src/css/output.css" rel="stylesheet">
    <link href="src/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="node_modules/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="node_modules/swiper/swiper-bundle.min.js"></script>
    <title>Chic and Chill</title>

    <style>
        /* Appliquer Cormorant Garamond pour tous les textes marron */
        .text-marron,
        .border-marron {
            font-family: 'Cormorant Garamond', serif !important;
            color: #8B5A2B;
            border-color: #8B5A2B;
        }

        /* Appliquer Cormorant Garamond pour tous les textes gris */
        .text-gray {
            font-family: 'Cormorant Garamond', serif !important;
            color: #4A4A4A;
        }

        /* Styles pour les cercles interactifs (desktop uniquement) */
        .circle-link {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid #8B5A2B;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(3px);
            border-radius: 50%;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            transition: transform 0.4s ease, box-shadow 0.4s ease, background 0.4s ease;
            z-index: 10;
        }

        .circle-link:hover {
            transform: scale(1.2);
            box-shadow: 0px 8px 18px rgba(0, 0, 0, 0.5);
            background: #8B5A2B;
            /* Fond marron au survol */
        }

        /* Ajouter une classe pour le fond marron quand activée par JS */
        .circle-link.active {
            background: #8B5A2B;
            transform: scale(1.2);
            box-shadow: 0px 8px 18px rgba(0, 0, 0, 0.5);
        }

        /* Transition pour les labels */
        #label-even,
        #label-location,
        #label-magasin {
            transition: color 0.4s ease;
        }

        /* Classe pour le texte blanc quand activée par JS */
        .label-active {
            color: white;
        }

        /* Animation d'apparition des labels */
        @keyframes fadeIn {
            0% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        .label-fade {
            animation: fadeIn 1s ease-in-out forwards;
            opacity: 0;
        }

        /* Styles pour le menu mobile */
        #mobile-menu {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #EFE7DD;
            z-index: 40;
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }

        #mobile-menu.open {
            transform: translateX(0);
        }

        #mobile-menu .close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 2rem;
            color: #8B5A2B;
        }

        #mobile-menu ul {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
            gap: 2rem;
        }

        #mobile-menu ul li a {
            font-size: 1.5rem;
            color: #8B5A2B;
            font-family: 'Cormorant Garamond', serif;
            font-weight: bold;
        }

        #mobile-menu ul li a:hover {
            color: #4A4A4A;
        }

        /* Styles pour la section hero sur mobile */
        .hero-content-mobile {
            display: none;
            /* Masqué par défaut, affiché sur mobile */
            flex-direction: column;
            align-items: center;
            text-align: center;
            z-index: 10;
            padding: 1rem;
        }

        .hero-content-mobile .title-container {
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(3px);
            border: 3px solid #8B5A2B;
            border-radius: 1rem;
            padding: 1rem 2rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            margin-bottom: 1.5rem;
        }

        .hero-content-mobile h1 {
            font-size: 2rem;
            color: #8B5A2B;
            font-family: 'Cormorant Garamond', serif;
            font-weight: bold;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .hero-content-mobile p {
            font-size: 1.2rem;
            color: #4A4A4A;
            font-family: 'Cormorant Garamond', serif;
            margin: 0.5rem 0 1.5rem;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .hero-content-mobile .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            margin: 0.5rem 0;
            background-color: #8B5A2B;
            color: white;
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.1rem;
            font-weight: bold;
            border-radius: 0.375rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .hero-content-mobile .btn:hover {
            transform: scale(1.05);
            background-color: #6F4622;
        }

        /* Masquer les cercles et labels par défaut avec une spécificité élevée */
        #hero-container #circle-main,
        #hero-container .circle-link,
        #hero-container #label-even,
        #hero-container #label-location,
        #hero-container #label-magasin {
            display: none !important;
        }

        /* Afficher les cercles et labels uniquement sur desktop */
        @media (min-width: 769px) {

            #hero-container #circle-main,
            #hero-container .circle-link,
            #hero-container #label-even,
            #hero-container #label-location,
            #hero-container #label-magasin {
                display: flex !important;
            }
        }

        /* Responsive : Tablettes */
        @media (max-width: 1024px) {
            #circle-main {
                width: 22vw !important;
                height: 22vw !important;
            }

            .circle-link {
                width: 10vw !important;
                height: 10vw !important;
            }
        }

        /* Responsive : Mobiles */
        @media (max-width: 768px) {

            /* Réduire la taille du logo et du titre dans le header */
            header .logo {
                width: 3rem !important;
                height: 3rem !important;
            }

            header .title {
                font-size: 1.5rem !important;
            }

            /* Masquer les cercles interactifs et leurs labels sur mobile (redondance pour plus de sécurité) */
            #hero-container #circle-main,
            #hero-container .circle-link,
            #hero-container #label-even,
            #hero-container #label-location,
            #hero-container #label-magasin {
                display: none !important;
            }

            /* Afficher la section hero mobile */
            .hero-content-mobile {
                display: flex !important;
            }

            /* Ajustement de l'image de fond */
            #hero-container {
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }

        /* Très petits écrans (smartphones) */
        @media (max-width: 480px) {
            header .logo {
                width: 3rem !important;
                height: 3rem !important;
            }

            header .title {
                font-size: 1.25rem !important;
            }

            .hero-content-mobile .title-container {
                padding: 0.75rem 1.5rem;
            }

            .hero-content-mobile h1 {
                font-size: 1.5rem;
            }

            .hero-content-mobile p {
                font-size: 1rem;
            }

            .hero-content-mobile .btn {
                font-size: 1rem;
                padding: 0.5rem 1rem;
            }
        }
    </style>
</head>

<body>
    <header class="fixed top-0 left-0 w-full bg-[#EFE7DD] shadow-md z-50 flex justify-between items-center px-10 py-4">
        <!-- Logo + Texte -->
        <div class="flex items-center space-x-4">
            <img src="assets/images/logo_magasin-chic.png" alt="Chic & Chill Logo" class="w-20 h-20 object-contain logo">
            <div class="text-[#8B5A2B] font-bold text-3xl tracking-wide title" style="font-family: 'Cormorant Garamond', serif;">
                CHIC <span class="text-gray-800">AND</span> CHILL
            </div>
        </div>

        <!-- Menu desktop -->
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

    <!-- Conteneur principal -->
    <div id="hero-container" class="relative w-full h-screen flex items-center justify-center overflow-hidden pt-[70px] mt-10">
        <!-- Image de fond -->
        <img src="assets/images/image_accueil.png" alt="Chic And Chill" class="absolute top-0 left-0 w-full h-full object-cover z-0">

        <!-- Section hero pour mobile -->
        <div class="hero-content-mobile">
            <div class="title-container">
                <h1>CHIC & CHILL</h1>
                <p>Mode responsable et accessible</p>
            </div>
            <a href="evenements" class="btn">Événements</a>
            <a href="accueil_loc_show" class="btn">Location & Showroom</a>
            <a href="accueil_shop" class="btn">Magasin</a>
        </div>

        <!-- Cercle central (desktop uniquement) -->
        <a href="accueil" id="circle-main" class="absolute flex justify-center items-center z-10 rounded-full border-[3px] border-[#8B5A2B] bg-white/20 backdrop-blur-md shadow-lg">
            <span id="chic" class="absolute text-[#8B5A2B] font-bold tracking-wide" style="font-family: 'Cormorant Garamond', serif;">CHIC</span>
            <span id="and" class="absolute text-gray-800 font-bold tracking-wide" style="font-family: 'Cormorant Garamond', serif;">&</span>
            <span id="chill" class="absolute text-[#8B5A2B] font-bold tracking-wide" style="font-family: 'Cormorant Garamond', serif;">CHILL</span>
        </a>

        <!-- Cercles cliquables (desktop uniquement) -->
        <a href="evenements" id="circle-even" class="circle-link"></a>
        <a href="accueil_loc_show" id="circle-location" class="circle-link"></a>
        <a href="accueil_shop" id="circle-magasin" class="circle-link"></a>

        <!-- Labels interactifs (desktop uniquement) -->
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

    <!-- Footer principal -->
    <footer class="bg-[#EFE7DD] text-[#8B5A2B] py-12" style="font-family: 'Cormorant Garamond', serif;">
        <div class="container max-w-6xl mx-auto px-8 md:px-16 grid grid-cols-1 md:grid-cols-3 gap-12 text-center md:text-left text-lg">
            <div class="flex flex-col items-center md:items-start">
                <img src="assets/images/logo_magasin-chic.png" alt="Chic & Chill Logo" class="w-24 h-24" loading="lazy">
                <p class="mt-4 text-[#4A4A4A] leading-relaxed text-center md:text-left max-w-sm" style="font-family: 'Cormorant Garamond', serif;">
                    <span class="font-semibold">Chic & Chill</span> est une boutique et service de location de vêtements proposant une mode responsable et accessible à tous.
                </p>
            </div>
            <div class="flex flex-col items-center md:items-start">
                <h3 class="font-semibold text-xl mb-4 text-[#8B5A2B]" style="font-family: 'Cormorant Garamond', serif;">Informations légales</h3>
                <ul class="space-y-3">
                    <li><a href="conditions_generales" class="text-[#4A4A4A] hover:text-[#8B5A2B] transition" style="font-family: 'Cormorant Garamond', serif;">Conditions générales de ventes</a></li>
                    <li><a href="#" class="text-[#4A4A4A] hover:text-[#8B5A2B] transition" style="font-family: 'Cormorant Garamond', serif;">Protection de la vie privée et des cookies</a></li>
                    <li><a href="mentions_legales" class="text-[#4A4A4A] hover:text-[#8B5A2B] transition" style="font-family: 'Cormorant Garamond', serif;">Mentions légales</a></li>
                    <li><a href="modes_paiement_shop" class="text-[#4A4A4A] hover:text-[#8B5A2B] transition" style="font-family: 'Cormorant Garamond', serif;">Les moyens de paiement</a></li>
                </ul>
            </div>
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
    </footer>

    <!-- Bouton "Retour en haut" -->
    <button id="scrollToTop" class="fixed bottom-8 right-8 bg-[#8B5A2B] text-white p-4 rounded-full shadow-lg hidden transition duration-300 hover:scale-110" aria-label="Retour en haut">
        <i class="fa-solid fa-arrow-up text-xl"></i>
    </button>

    <script>
        // Ajustement des éléments (cercle principal uniquement sur desktop)
        function adjustElements() {
            let container = document.getElementById('hero-container');
            let width = container.offsetWidth;
            let height = container.offsetHeight;

            // Masquer explicitement les éléments sur mobile
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
                return; // Arrêter l'exécution si on est sur mobile
            }

            // Ajustement des cercles interactifs (uniquement sur desktop)
            circleMain.style.display = 'flex'; // S'assurer que l'élément est visible sur desktop
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

        // Gestion du menu burger
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
            }, 300);
        });

        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('open');
                setTimeout(() => {
                    mobileMenu.classList.add('hidden');
                }, 300);
            });
        });

        // Gestion du bouton "Retour en haut"
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

        // Écouteurs d'événements pour ajuster les tailles
        window.addEventListener('resize', adjustElements);
        window.addEventListener('load', adjustElements);

        // Appeler la fonction immédiatement pour s'assurer que les éléments sont masqués au chargement
        adjustElements();

        // Gestion du hover des cercles et labels
        const circleLabelPairs = {
            'circle-even': 'label-even',
            'circle-location': 'label-location',
            'circle-magasin': 'label-magasin'
        };

        Object.entries(circleLabelPairs).forEach(([circleId, labelId]) => {
            const circle = document.getElementById(circleId);
            const label = document.getElementById(labelId);

            // Survol du cercle
            circle.addEventListener('mouseover', () => {
                circle.classList.add('active');
                label.classList.add('label-active');
            });
            circle.addEventListener('mouseout', () => {
                circle.classList.remove('active');
                label.classList.remove('label-active');
            });

            // Survol du label
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