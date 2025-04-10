<!DOCTYPE html>
<html lang="fr">

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
  <!-- Inclusion de Swiper pour les carrousels/sliders -->
  <link rel="stylesheet" href="node_modules/swiper/swiper-bundle.min.css">
  <!-- Chargement asynchrone de la police Material Symbols Rounded, initialement pour l'impression puis pour tous les médias -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:wght@400;500&display=swap" media="print" onload="this.media='all'">

  <!-- Préchargement des ressources critiques pour améliorer les performances -->
  <link rel="preload" href="assets/images/facadeMagasin.webp" as="image"> <!-- Précharge l'image de la façade -->
  <link rel="preload" href="node_modules/swiper/swiper-bundle.min.js" as="script"> <!-- Précharge le script Swiper -->

  <!-- Scripts chargés en différé pour ne pas bloquer le rendu initial -->
  <script src="src/libs/swiper-bundle.min.js" defer></script> <!-- Swiper pour les sliders -->
  <script src="src/libs/all.min.js" defer></script> <!-- Font Awesome pour les icônes -->
  <!-- Note : Phosphor Icons est mentionné mais non inclus ici, probablement supprimé si inutile -->

  <!-- Code de suivi Matomo pour l'analyse des statistiques -->
  <script>
    var _paq = window._paq = window._paq || []; // Initialisation du tableau de suivi Matomo
    /* Les méthodes comme "setCustomDimension" doivent être appelées avant "trackPageView" */
    _paq.push(['trackPageView']); // Suivi des vues de page
    _paq.push(['enableLinkTracking']); // Activation du suivi des clics sur les liens
    (function() {
      var u = "//localhost/matomo-latest/matomo/"; // URL du serveur Matomo
      _paq.push(['setTrackerUrl', u + 'matomo.php']); // Définition de l'URL de suivi
      _paq.push(['setSiteId', '3']); // ID du site dans Matomo
      var d = document,
        g = d.createElement('script'), // Création d'un élément script
        s = d.getElementsByTagName('script')[0]; // Récupération du premier script existant
      g.async = true; // Chargement asynchrone pour ne pas bloquer
      g.src = u + 'matomo.js'; // Source du script Matomo
      s.parentNode.insertBefore(g, s); // Insertion avant le premier script
    })();
  </script>
  <!-- Fin du code Matomo -->
</head>

<body>