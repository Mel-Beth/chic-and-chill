<!DOCTYPE html>
<html lang="fr">

<?php include("src/app/Views/includes/head.php"); ?>
<header class="fixed top-0 left-0 w-full bg-[#EFE7DD] shadow-md z-50 flex justify-between items-center px-10 py-4">
    <!-- Logo + Texte -->
    <div class="flex items-center space-x-4">
        <!-- Logo -->
        <img src="<?= BASE_URL ?>assets/images/logo.png" alt="Chic & Chill Logo" class="w-20 h-20 object-contain">

        <!-- Texte CHIC AND CHILL -->
        <div class="text-[#8B5A2B] font-bold text-3xl tracking-wide font-family: 'Cormorant Garamond', serif;" style="font-family: 'Cormorant Garamond', serif;">
            CHIC <span class="text-gray-800">AND</span> CHILL
        </div>
    </div>

    <!-- Menu -->
    <nav class="hidden md:flex space-x-8 text-lg text-[#8B5A2B] font-semibold">
        <a href="accueil" class="hover:text-gray-800 transition">Accueil</a>
        <a href="evenements" class="hover:text-gray-800 transition">Événements</a>
        <a href="location" class="hover:text-gray-800 transition">Location</a>
        <a href="magasin" class="hover:text-gray-800 transition">Magasin</a>
        <a href="contact" class="hover:text-gray-800 transition">Contact</a>
    </nav>

    <!-- Menu mobile -->
    <div class="md:hidden">
        <button id="menu-toggle" class="text-[#8B5A2B] focus:outline-none">☰</button>
    </div>
</header>

<!-- Conteneur principal contenant l'image de fond et les cercles interactifs -->
<div id="hero-container" class="relative w-full h-screen flex items-center justify-center overflow-hidden pt-[80px]">

    <!-- Image de fond qui couvre toute la page -->
    <img src="assets/images/image_accueil.png" alt="Chic And Chill" class="absolute top-0 left-0 w-full h-full object-cover z-0">

    <!-- Cercle central avec texte décalé menant à l'accueil -->
    <a href="accueil" id="circle-main" class="absolute flex justify-center items-center z-10 rounded-full border-[3px] border-[#8B5A2B] bg-white/20 backdrop-blur-md shadow-lg">
        <!-- Texte dans le cercle principal avec positionnement personnalisé -->
        <span id="chic" class="absolute text-[#8B5A2B] font-bold tracking-wide" style="font-family: 'Cormorant Garamond', serif;">CHIC</span>
        <span id="and" class="absolute text-gray-800 font-bold tracking-wide" style="font-family: 'Cormorant Garamond', serif;">&</span>
        <span id="chill" class="absolute text-[#8B5A2B] font-bold tracking-wide" style="font-family: 'Cormorant Garamond', serif;">CHILL</span>
    </a>


    <!-- Cercles cliquables menant aux différentes pages -->
    <a href="evenements" id="circle-even" class="circle-link"></a>
    <a href="location" id="circle-location" class="circle-link"></a>
    <a href="magasin" id="circle-magasin" class="circle-link"></a>

    <!-- Labels interactifs associés aux cercles -->
    <a href="evenements">
        <span id="label-even" class="absolute text-gray-800 font-bold tracking-wide z-10 label-fade" style="animation-delay: 0.6s;">
            EVENEMENTS
        </span>
    </a>

    <a href="location">
        <span id="label-location" class="absolute text-gray-800 font-bold tracking-wide z-10 label-fade" style="animation-delay: 0.8s;">
            LOCATION
        </span>
    </a>

    <a href="magasin">
        <span id="label-magasin" class="absolute text-gray-800 font-bold tracking-wide z-10 label-fade" style="animation-delay: 1s;">
            MAGASIN
        </span>
    </a>

</div>

<footer class="bg-[#EFE7DD] text-[#8B5A2B] text-center py-4 left-0 w-full shadow-md relative mt-8" style="font-family: 'Cormorant Garamond', serif;">
    © 2025 Chic And Chill - Tous droits réservés.
</footer>

<style>
    /* 🔵 Styles pour les cercles interactifs */
    .circle-link {
        position: absolute;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid #8B5A2B;
        /* Bordure marron */
        background: rgba(255, 255, 255, 0.2);
        /* Fond semi-transparent */
        backdrop-filter: blur(3px);
        /* Effet de flou sur le fond */
        border-radius: 50%;
        /* Cercle */
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
        /* Ombre légère */
        transition: transform 0.4s ease, box-shadow 0.4s ease, background 0.4s ease;
        /* Effet animé */
        z-index: 10;
    }

    /* 🎯 Effet au survol plus dynamique */
    .circle-link:hover {
        transform: scale(1.2);
        /* Zoom */
        box-shadow: 0px 8px 18px rgba(0, 0, 0, 0.5);
        /* Ombre plus marquée */
        background: rgba(255, 255, 255, 0.3);
        /* Augmentation de la brillance */
    }

    /* 🔹 Rendre le site plus responsive sur tablettes */
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

    /* 🔸 Ajustements spécifiques pour les mobiles */
    @media (max-width: 768px) {
        #circle-main {
            width: 18vw !important;
            height: 18vw !important;
        }

        /* Réduire la taille des cercles interactifs */
        .circle-link {
            width: 9vw !important;
            height: 9vw !important;
        }

        /* Ajustement du texte pour éviter les superpositions */
        #chic {
            font-size: 3vw !important;
            left: 10% !important;
        }

        #and {
            font-size: 3vw !important;
            left: 50% !important;
        }

        #chill {
            font-size: 3vw !important;
            right: 10% !important;
        }
    }

    /* 🎯 Animation d'apparition des labels */
    @keyframes fadeIn {
        0% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }

    /* 🔥 Appliquer l'animation avec un délai différent pour chaque élément */
    .label-fade {
        animation: fadeIn 1s ease-in-out forwards;
        opacity: 0;
        /* Commence invisible */
    }

    /* 📱 Gestion spécifique pour les très petits écrans (smartphones) */
    @media (max-width: 480px) {
        #circle-main {
            width: 30vw !important;
            height: 30vw !important;
        }

        .circle-link {
            width: 14vw !important;
            height: 14vw !important;
        }

        /* Réduction des espacements pour éviter les débordements */
        #chic,
        #and,
        #chill {
            font-size: 5vw !important;
        }
    }
</style>

<script>
    function adjustElements() {
        let container = document.getElementById('hero-container');
        let width = container.offsetWidth;
        let height = container.offsetHeight;

        // 🔵 Ajustement dynamique du cercle principal
        let mainCircle = document.getElementById('circle-main');
        mainCircle.style.width = width * 0.25 + "px";
        mainCircle.style.height = width * 0.25 + "px";
        mainCircle.style.left = "50%";
        mainCircle.style.top = "48%";
        mainCircle.style.transform = "translate(-50%, -50%)";

        // 🟠 Ajustement du texte dans le cercle central
        let chic = document.getElementById('chic');
        let and = document.getElementById('and');
        let chill = document.getElementById('chill');

        let fontSize = width * 0.035 + "px"; /* Taille relative au viewport */
        chic.style.fontSize = fontSize;
        and.style.fontSize = fontSize;
        chill.style.fontSize = fontSize;

        /* Décalage du texte pour un positionnement asymétrique */
        chic.style.top = "30%";
        chic.style.left = "15%";
        chic.style.transform = "translate(-20%, -10%)";

        and.style.top = "50%";
        and.style.left = "50%";
        and.style.transform = "translate(-50%, -50%)";

        chill.style.top = "70%";
        chill.style.right = "15%";
        chill.style.transform = "translate(8%, -80%)";

        // 🔵 Positionnement dynamique des cercles interactifs
        let evenCircle = document.getElementById('circle-even');
        let locationCircle = document.getElementById('circle-location');
        let magasinCircle = document.getElementById('circle-magasin');

        evenCircle.style.width = width * 0.13 + "px";
        evenCircle.style.height = width * 0.13 + "px";
        evenCircle.style.left = "9%";
        evenCircle.style.top = "12%";
        evenCircle.style.transform = "translate(139%, 20%)";

        locationCircle.style.width = width * 0.13 + "px";
        locationCircle.style.height = width * 0.13 + "px";
        locationCircle.style.right = "9%";
        locationCircle.style.top = "12%";
        locationCircle.style.transform = "translate(-155%, 60px)";

        magasinCircle.style.width = width * 0.11 + "px";
        magasinCircle.style.height = width * 0.11 + "px";
        magasinCircle.style.left = "10%";
        magasinCircle.style.bottom = "14%";
        magasinCircle.style.transform = "translate(195%, -45px)";

        // 🎯 Labels repositionnés pour suivre les cercles
        let evenLabel = document.getElementById('label-even');
        let locationLabel = document.getElementById('label-location');
        let magasinLabel = document.getElementById('label-magasin');

        evenLabel.style.left = "9%";
        evenLabel.style.top = "12%";
        evenLabel.style.transform = "translate(155%, 290%)";
        evenLabel.style.fontSize = width * 0.018 + "px";

        locationLabel.style.right = "9%";
        locationLabel.style.top = "12%";
        locationLabel.style.transform = "translate(-230%, 145px)";
        locationLabel.style.fontSize = width * 0.018 + "px";

        magasinLabel.style.left = "10%";
        magasinLabel.style.bottom = "14%";
        magasinLabel.style.transform = "translate(260%, -115px)";
        magasinLabel.style.fontSize = width * 0.018 + "px";
    }

    // ⚙️ Écouteurs d'événements pour ajuster les tailles lors du chargement et du redimensionnement
    window.addEventListener('resize', adjustElements);
    window.addEventListener('load', adjustElements);
</script>

</body>

</html>