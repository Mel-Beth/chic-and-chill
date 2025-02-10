<!DOCTYPE html>
<html lang="fr">

<!doctype html>
<html lang="fr">
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?= BASE_URL ?>src/css/output.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>src/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="node_modules/swiper/swiper-bundle.min.css">
    <script src="node_modules/swiper/swiper-bundle.min.js"></script>
    <title>Chic and Chill</title>
</head>

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

<!-- Conteneur principal pour l'erreur 404 -->
<div id="error-container" class="relative w-full h-screen flex flex-col items-center justify-center overflow-hidden">

    <!-- Image de fond (identique à celle de l'accueil) -->
    <img src="assets/images/image_accueil.png" alt="Chic And Chill" class="absolute top-0 left-0 w-full h-full object-cover z-0">

    <!-- Cercle central indiquant l'erreur -->
    <div id="circle-error" class="absolute flex flex-col justify-center items-center z-10 rounded-full border-[3px] border-[#8B5A2B] bg-white/20 backdrop-blur-md shadow-lg">
        <span class="text-[#8B5A2B] font-bold tracking-wide text-4xl md:text-6xl" style="font-family: 'Cormorant Garamond', serif;">
            Erreur 404
        </span>
        <p class="text-gray-800 font-semibold text-lg md:text-xl text-center mt-2 px-4">
            Oups... La page que vous cherchez n'existe pas.
        </p>
    </div>

    <!-- Bouton retour à l'accueil, repositionné plus haut -->
    <a href="accueil"
        class="absolute px-6 py-3 text-lg font-semibold text-[#8B5A2B] border-2 border-[#8B5A2B] rounded-full 
              bg-white/20 backdrop-blur-md transition duration-300 hover:bg-[#8B5A2B] hover:text-white hover:shadow-lg"
        id="return-button"
        style="font-family: 'Cormorant Garamond', serif;">
        Retour à l'accueil
    </a>

</div>


<footer class="bg-[#EFE7DD] text-[#8B5A2B] text-center py-4 left-0 w-full shadow-md relative" style="font-family: 'Cormorant Garamond', serif;">
    © 2025 Chic And Chill - Tous droits réservés.
</footer>

<style>
    /* Style du cercle d'erreur */
    #circle-error {
        width: 28vw;
        height: 28vw;
        max-width: 280px;
        max-height: 280px;
        text-align: center;
    }

    /* Effet responsive */
    @media (max-width: 768px) {
        #circle-error {
            width: 40vw;
            height: 40vw;
            max-width: 240px;
            max-height: 240px;
        }
    }

    /* Ajustement du bouton pour le remonter */
    #return-button {
        bottom: 18vh;
        /* Au lieu de 10vh pour le remonter */
    }

    @media (max-width: 768px) {
        #return-button {
            bottom: 20vh;
            /* Ajustement spécifique pour les petits écrans */
        }
    }
</style>

</body>

</html>