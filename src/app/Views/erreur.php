<?php
// Vérification et définition des variables d'erreur avec des valeurs par défaut
// Si $code_erreur n'est pas défini, utilise "Erreur inconnue"
$code_erreur = isset($code_erreur) ? $code_erreur : 'Erreur inconnue';
// Si $description_erreur n'est pas défini, utilise "Aucune description fournie"
$description_erreur = isset($description_erreur) ? $description_erreur : 'Aucune description fournie';
?>

<!DOCTYPE html>
<html lang="fr">
<!-- Balise <html> ouverte une seule fois (la seconde est redondante dans le code original mais conservée ici) -->
<html>

<head>
    <!-- Définition du codage des caractères en UTF-8 -->
    <meta charset="UTF-8">
    <!-- Configuration pour la responsivité sur différents appareils -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- URL de base pour tous les liens relatifs dans le projet -->
    <base href="http://localhost/site_stage/chic-and-chill/">
    <!-- Inclusion du fichier CSS compilé (probablement Tailwind CSS) -->
    <link href="src/css/output.css" rel="stylesheet">
    <!-- Inclusion d'un fichier CSS personnalisé pour des styles supplémentaires -->
    <link href="src/css/style.css" rel="stylesheet">
    <!-- Inclusion de Swiper pour les carrousels/sliders (bien que non utilisé ici) -->
    <link rel="stylesheet" href="node_modules/swiper/swiper-bundle.min.css">
    <!-- Inclusion du script Swiper (bien que non utilisé dans cette page) -->
    <script src="node_modules/swiper/swiper-bundle.min.js"></script>
    <!-- Titre de la page -->
    <title>Chic and Chill</title>

    <style>
        /* Style personnalisé pour le cercle d'erreur */
        #circle-error {
            width: 40vw;
            /* Largeur relative à la taille de la fenêtre */
            height: 40vw;
            /* Hauteur relative pour garder un cercle parfait */
            max-width: 320px;
            /* Largeur maximale pour limiter sur grands écrans */
            max-height: 320px;
            /* Hauteur maximale correspondante */
            text-align: center;
            /* Centrage du texte à l'intérieur */
        }

        /* Media query pour écrans plus petits (responsivité) */
        @media (max-width: 768px) {
            #circle-error {
                width: 40vw;
                height: 40vw;
                max-width: 240px;
                /* Réduction de la taille maximale sur mobile */
                max-height: 240px;
            }
        }

        /* Positionnement du bouton de retour */
        #return-button {
            bottom: 18vh;
            /* Positionné à 18% de la hauteur de la fenêtre depuis le bas */
        }

        /* Ajustement pour écrans mobiles */
        @media (max-width: 768px) {
            #return-button {
                bottom: 20vh;
                /* Légèrement plus haut sur mobile pour meilleure ergonomie */
            }
        }
    </style>
</head>

<!-- En-tête fixe en haut de la page -->
<header class="fixed top-0 left-0 w-full bg-[#EFE7DD] shadow-md z-50 flex justify-between items-center px-10 py-4">
    <!-- Section logo et texte -->
    <div class="flex items-center space-x-4">
        <!-- Logo du magasin -->
        <img src="assets/images/logo_magasin-chic.png" alt="Chic & Chill Logo" class="w-20 h-20 object-contain">
        <!-- Texte de la marque avec style personnalisé -->
        <div class="text-[#8B5A2B] font-bold text-3xl tracking-wide font-family: 'Cormorant Garamond', serif;" style="font-family: 'Cormorant Garamond', serif;">
            CHIC <span class="text-gray-800">AND</span> CHILL
        </div>
    </div>

    <!-- Navigation pour desktop -->
    <nav class="hidden md:flex space-x-8 text-lg text-[#8B5A2B] font-semibold">
        <a href="accueil" class="hover:text-gray-800 transition">Accueil</a>
        <a href="evenements" class="hover:text-gray-800 transition">Événements</a>
        <a href="location" class="hover:text-gray-800 transition">Location</a>
        <a href="magasin" class="hover:text-gray-800 transition">Magasin</a>
        <a href="contact" class="hover:text-gray-800 transition">Contact</a>
    </nav>

    <!-- Bouton pour menu mobile (visible uniquement sur petits écrans) -->
    <div class="md:hidden">
        <button id="menu-toggle" class="text-[#8B5A2B] focus:outline-none">☰</button>
    </div>
</header>

<!-- Conteneur principal pour la page d'erreur -->
<div id="error-container" class="relative w-full h-screen flex flex-col items-center justify-center overflow-hidden">
    <!-- Image de fond couvrant toute la page -->
    <img src="assets/images/image_accueil.png" alt="Chic And Chill" class="absolute top-0 left-0 w-full h-full object-cover z-0">

    <!-- Cercle central affichant le message d'erreur -->
    <div id="circle-error" class="absolute flex flex-col justify-center items-center z-10 rounded-full border-[3px] border-[#8B5A2B] bg-white/20 backdrop-blur-md shadow-lg">
        <!-- Code d'erreur affiché dynamiquement -->
        <span class="text-[#8B5A2B] font-bold tracking-wide text-4xl md:text-6xl" style="font-family: 'Cormorant Garamond', serif;">
            Erreur <?= $code_erreur ?>
        </span>
        <!-- Description de l'erreur -->
        <p class="text-gray-800 font-semibold text-lg md:text-xl text-center mt-2 px-4">
            <?= $description_erreur ?>
        </p>
    </div>

    <!-- Bouton de retour à l'accueil -->
    <a href="accueil"
        class="absolute px-6 py-3 text-lg font-semibold text-[#8B5A2B] border-2 border-[#8B5A2B] rounded-full 
              bg-white/20 backdrop-blur-md transition duration-300 hover:bg-[#8B5A2B] hover:text-white hover:shadow-lg"
        id="return-button"
        style="font-family: 'Cormorant Garamond', serif;">
        Retour à l'accueil
    </a>
</div>

<!-- Pied de page -->
<footer class="bg-[#EFE7DD] text-[#8B5A2B] text-center py-4 left-0 w-full shadow-md relative" style="font-family: 'Cormorant Garamond', serif;">
    © 2025 Chic And Chill - Tous droits réservés.
</footer>

</body>

</html>