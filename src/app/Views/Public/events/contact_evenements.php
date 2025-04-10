<?php 
// Inclusion du fichier d'en-tête contenant les métadonnées et ressources
include('src/app/Views/includes/events/headEvents.php'); 
// Inclusion du fichier d'en-tête contenant la navigation
include('src/app/Views/includes/events/headerEvents.php'); 
?>

<!-- Section héroïque avec image de fond -->
<div class="relative w-full h-96 bg-cover bg-center flex justify-center items-center">
    <!-- Couche sombre semi-transparente pour améliorer la lisibilité du texte -->
    <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center text-center px-4">
        <!-- Titre principal en blanc, grand et en gras -->
        <h1 class="text-white text-5xl font-bold">Nous contacter</h1>
    </div>
</div>

<!-- Contenu principal -->
<div class="container mx-auto px-4 py-12">
    <!-- Bandeau noir pour le titre du formulaire -->
    <div class="bg-black text-white p-6 rounded-lg shadow-lg mb-12 max-w-4xl mx-auto text-center">
        <h2 class="text-2xl font-bold mb-4">📩 Contact - Magasin</h2>
    </div>
    <?php if (isset($_GET['success']) && $_GET['success'] == 1) : ?>
        <!-- Message de succès conditionnel si le paramètre URL 'success' est présent -->
        <p class="text-green-600 text-center font-semibold">Votre message a bien été envoyé !</p>
    <?php endif; ?>

    <!-- Formulaire de contact -->
    <form action="contact_process" method="post" class="max-w-2xl mx-auto bg-gray-100 p-6 rounded-lg shadow-md">
        <!-- Champ caché pour identifier la source du formulaire -->
        <input type="hidden" name="source" value="evenements">

        <!-- Champ pour le nom -->
        <label for="name" class="block text-gray-700 font-semibold">Nom :</label>
        <input type="text" name="name" id="name" required class="w-full p-2 border border-gray-300 rounded-md mb-4">

        <!-- Champ pour l'email -->
        <label for="email" class="block text-gray-700 font-semibold">Email :</label>
        <input type="email" name="email" id="email" required class="w-full p-2 border border-gray-300 rounded-md mb-4">

        <!-- Champ pour le sujet -->
        <label for="subject" class="block text-gray-700 font-semibold">Sujet :</label>
        <input type="text" name="subject" id="subject" required class="w-full p-2 border border-gray-300 rounded-md mb-4">

        <!-- Champ pour le message -->
        <label for="message" class="block text-gray-700 font-semibold">Message :</label>
        <textarea name="message" id="message" rows="5" required class="w-full p-2 border border-gray-300 rounded-md mb-4"></textarea>

        <!-- Bouton d'envoi -->
        <button type="submit" class="bg-[#8B5A2B] text-white px-6 py-2 rounded-md w-full">Envoyer</button>
    </form>
</div>

<?php 
// Inclusion du pied de page avec informations de contact et scripts
include('src/app/Views/includes/events/footerEvents.php'); 
?>