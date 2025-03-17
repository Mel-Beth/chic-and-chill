<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Chic & Chill - Public</title>
  <!-- Lien vers TON fichier Tailwind compilé (output.css) -->
  <!-- Ajuste le chemin selon ton arborescence réelle -->
  <link rel="stylesheet" href="/projets/chic-and-chill/src/css/output.css">
</head>
<body class="bg-gray-100">
  <!-- Barre de navigation (exemple) -->
  <nav class="bg-white shadow-md fixed w-full z-10">
    <div class="container mx-auto px-4 py-3 flex items-center justify-between">
      <!-- Logo -->
      <a href="/projets/chic-and-chill/" class="flex items-center">
        <img src="assets\images\logo_magasin-chic-mel.png"
             alt="Chic & Chill Logo"
             class="h-10 w-auto mr-2">
        <span class="text-lg font-bold text-gray-700">Chic & Chill</span>
      </a>
      <!-- Liens de navigation -->
      <div class="hidden md:flex space-x-6">
        <a href="/projets/chic-and-chill/showroom"
           class="text-gray-700 hover:text-gray-500 transition">
           Showroom
        </a>
        <a href="/projets/chic-and-chill/location"
           class="text-gray-700 hover:text-gray-500 transition">
           Location de Robes
        </a>
        <a href="/projets/chic-and-chill/events"
           class="text-gray-700 hover:text-gray-500 transition">
           Événements
        </a>
        <!-- etc. -->
      </div>
    </div>
  </nav>

  <!-- Pour laisser de la place sous la navbar fixée -->
  <div class="pt-16"></div>
