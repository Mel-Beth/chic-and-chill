<?php include('src/app/Views/includes/headEvents.php'); ?>
<?php include('src/app/Views/includes/headerEvents.php'); ?>

<!-- HERO SECTION -->
<div class="relative w-full h-96 bg-cover bg-center flex justify-center items-center">
    <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center text-center px-4">
        <h1 class="text-white text-5xl font-bold">Réserver un pack</h1>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <h2 class="text-4xl font-bold text-center mb-8 p-12 bg-black text-white">🎟️ Réserver un Pack Événementiel</h2>

    <?php if (!empty($pack)) : ?>
        <div class="bg-white p-6 rounded-lg shadow-md max-w-3xl mx-auto">
            <h2 class="text-2xl font-semibold text-gray-900 mb-4"><?= htmlspecialchars($pack['title']); ?></h2>
            <p class="text-gray-700"><?= nl2br(htmlspecialchars($pack['description'])); ?></p>

            <ul class="list-disc space-y-2 pl-5 text-gray-700 mt-4">
                <li><strong>Prix :</strong> <?= htmlspecialchars($pack['price']); ?> €</li>
                <li><strong>Durée :</strong> <?= isset($pack['duration']) ? htmlspecialchars($pack['duration']) . ' heures' : 'Non spécifiée'; ?></li>
                <li><strong>Ce qui est inclus :</strong> <?= isset($pack['included']) ? nl2br(htmlspecialchars($pack['included'])) : 'Non spécifié'; ?></li>
            </ul>

            <form action="<?= BASE_URL ?>reservation_process.php" method="post" class="mt-6">
                <input type="hidden" name="pack_id" value="<?= htmlspecialchars($pack['id']); ?>">

                <label for="name" class="block text-lg font-semibold text-gray-800 mt-4">Votre Nom :</label>
                <input type="text" name="name" id="name" required class="w-full p-3 border border-gray-300 rounded-md">

                <label for="email" class="block text-lg font-semibold text-gray-800 mt-4">Votre Email :</label>
                <input type="email" name="email" id="email" required class="w-full p-3 border border-gray-300 rounded-md">

                <label for="phone" class="block text-lg font-semibold text-gray-800 mt-4">Téléphone :</label>
                <input type="tel" name="phone" id="phone" required class="w-full p-3 border border-gray-300 rounded-md">

                <button type="submit" class="mt-6 bg-[#8B5A2B] text-white px-6 py-3 rounded-md w-full text-lg font-semibold transition duration-300 hover:scale-105 hover:shadow-lg">
                    🎟️ Valider la réservation
                </button>
            </form>
        </div>
    <?php else : ?>
        <p class="text-center text-red-600 font-semibold">Ce pack n'existe pas ou a été supprimé.</p>
    <?php endif; ?>
</div>

<?php include('src/app/Views/includes/footerEvents.php'); ?>
