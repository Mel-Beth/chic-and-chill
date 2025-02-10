<?php
include('src/app/Views/includes/head.php');
include('src/app/Views/includes/header.php');

if (!isset($event) || !$event) {
    echo "<p class='text-center text-red-500'>Événement introuvable.</p>";
    include('src/app/Views/includes/footer.php');
    exit();
}
?>

<!-- BANNIÈRE IMMERSIVE -->
<div class="relative w-full h-[70vh] bg-cover bg-center" style="background-image: url('<?= BASE_URL ?>assets/images/events/<?= htmlspecialchars($event['image']) ?>');">
    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <h1 class="text-white text-5xl font-bold"> <?= htmlspecialchars($event['title']) ?> </h1>
    </div>
</div>

<!-- CONTENU DE L'ÉVÉNEMENT -->
<div class="container mx-auto my-8 px-4">
    <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-8 border border-gray-200">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Détails de l'événement</h2>
        <p class="text-gray-700 mb-6"> <?= htmlspecialchars($event['description']) ?> </p>

        <p class="text-gray-700 mb-4"><strong>Date :</strong> <?= htmlspecialchars($event['date']) ?></p>
        <p class="text-gray-700 mb-4"><strong>Lieu :</strong> <?= htmlspecialchars($event['location']) ?></p>

        <a href="contact.php" class="block text-center bg-red-600 text-white px-6 py-3 rounded-md text-lg font-semibold hover:bg-red-800 transition">Réserver</a>
    </div>
</div>

<?php include('src/app/Views/includes/footer.php'); ?>
