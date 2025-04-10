<?php 
// Inclusion du fichier d'en-tête contenant les métadonnées et ressources
include('src/app/Views/includes/events/headEvents.php'); 
// Inclusion du fichier d'en-tête contenant la navigation
include('src/app/Views/includes/events/headerEvents.php'); 
?>

<!-- Section héroïque avec image de fond -->
<div class="relative w-full h-96 bg-cover bg-center flex justify-center items-center">
    <!-- Couche sombre semi-transparente pour améliorer la lisibilité -->
    <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center text-center px-4">
        <h1 class="text-white text-5xl font-bold">Localisation</h1> <!-- Titre principal -->
    </div>
</div>

<!-- Contenu principal -->
<div class="container mx-auto px-4 py-12">
    <!-- Bandeau noir pour le titre -->
    <div class="bg-black text-white p-6 rounded-lg shadow-lg mb-12 max-w-4xl mx-auto text-center">
        <h2 class="text-2xl font-bold mb-4">📍Notre Localisation</h2> <!-- Titre de la section -->
    </div>

    <!-- Carte Google Maps -->
    <div class="bg-gray-100 p-6 rounded-lg shadow-lg max-w-4xl mx-auto text-center">
        <!-- Intégration d'une iframe Google Maps pour afficher la localisation -->
        <iframe src="https://www.google.com/maps/embed?pb=!1m23!1m12!1m3!1d41195.92887387833!2d4.671580702290865!3d49.80965110862625!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m8!3e6!4m0!4m5!1s0x47ea0f66c211b29d%3A0xd2aba700dfbfb03a!2zMTAgUnVlIElyw6luw6llIENhcnLDqSwgMDgwMDAgQ2hhcmxldmlsbGUtTcOpemnDqHJlcw!3m2!1d49.7727663!2d4.7195123!5e0!3m2!1sfr!2sfr!4v1741007116348!5m2!1sfr!2sfr"
            width="850"
            height="450"
            style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <!-- Informations supplémentaires -->
    <div class="bg-gray-100 p-6 rounded-lg shadow-lg max-w-4xl mx-auto text-center mt-4">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">📅 Informations</h2> <!-- Sous-titre -->
        <div class="info flex justify-center gap-4 mt-6">
            <!-- Coordonnées -->
            <div>
                <p><i class="fas fa-map-marker-alt"></i>Adresse</p>
                <p><i></i> Adresse : 10 Rue Irénée Carré, Charleville-Mézières</p>
                <p><i></i> Téléphone : +33 7 81 26 64 56</p>
                <p><i></i> Email : contact@chicandchill.fr</p>
            </div>
            <!-- Horaires -->
            <div>
                <p><i class="fas fa-clock"></i> Horaires d'ouverture :</p>
                <p>Mardi - Vendredi : 10h-12h30 - 14h-19h</p>
                <p>Samedi : 10h - 19h</p>
            </div>
        </div>
    </div>
</div>

<?php 
// Inclusion du pied de page avec informations de contact et scripts
include('src/app/Views/includes/events/footerEvents.php'); 
?>