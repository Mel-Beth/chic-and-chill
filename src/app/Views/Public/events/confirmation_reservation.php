<?php include('src/app/Views/includes/events/headEvents.php'); ?>
<?php include('src/app/Views/includes/events/headerEvents.php'); ?>

<!-- HERO SECTION -->
<div class="relative w-full h-96 bg-cover bg-center flex justify-center items-center">
    <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center text-center px-4">
        <h1 class="text-white text-5xl font-bold">Réservation confirmé</h1>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <h2 class="text-4xl font-bold text-center mb-8 p-12 bg-black text-white">📅 Réservation confirmée</h2>
    <?php if (isset($_GET['success']) && $_GET['success'] == 1) : ?>
        <p class="text-green-600 text-center font-semibold">Votre message a bien été envoyé !</p>
    <?php endif; ?>

    <!-- BOUTON RETOUR -->
    <div class="text-center mt-12">
        <a href="evenements"
            class="inline-block bg-[#8B5A2B] text-white text-lg font-semibold px-8 py-4 rounded-md transition duration-300 hover:scale-105 hover:shadow-lg">
            Retour
        </a>
    </div>

</div>

<?php include('src/app/Views/includes/events/footerEvents.php'); ?>