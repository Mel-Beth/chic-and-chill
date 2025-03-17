<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- <base href="http://localhost/projets/projetsExo/chic-and-chill/"> -->
  <!-- <base href="http://localhost/site_stage/chic-and-chill/"> -->
  <link href="src/css/output.css" rel="stylesheet">
  <link href="src/css/style.css" rel="stylesheet">
  <link rel="stylesheet" href="node_modules/swiper/swiper-bundle.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:wght@400;500&display=swap" media="print" onload="this.media='all'">

  <!-- Préchargement des ressources critiques -->
  <link rel="preload" href="assets/images/facadeMagasin.webp" as="image">
  <link rel="preload" href="node_modules/swiper/swiper-bundle.min.js" as="script">

  <!-- Scripts différés et essentiels uniquement -->
  <script src="node_modules/swiper/swiper-bundle.min.js" defer></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" defer></script>
  <!-- Suppression de Phosphor Icons si non utilisé dans cette page -->

  <!-- Matomo en bas -->
  <script defer>
    var _paq = window._paq = window._paq || [];
    _paq.push(['trackPageView']);
    _paq.push(['enableLinkTracking']);
    (function() {
      var u = "https://chicandchill.matomo.cloud/";
      _paq.push(['setTrackerUrl', u + 'matomo.php']);
      _paq.push(['setSiteId', '1']);
      var d = document,
        g = d.createElement('script'),
        s = d.getElementsByTagName('script')[0];
      g.async = true;
      g.src = 'https://cdn.matomo.cloud/chicandchill.matomo.cloud/matomo.js';
      s.parentNode.insertBefore(g, s);
    })();
  </script>
</head>

<body>