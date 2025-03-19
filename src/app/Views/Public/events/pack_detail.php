<?php include('src/app/Views/includes/events/headEvents.php'); ?>
<?php include('src/app/Views/includes/events/headerEvents.php'); ?>

<!-- Vérifie si un pack est défini -->
<?php if (!empty($pack)) : ?>

    <!-- HERO SECTION -->
    <div class="relative w-full h-96 bg-cover bg-center flex justify-center items-center"
        style="background-image: url('assets/images/packs/<?= htmlspecialchars($pack['image']); ?>');">
        <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center text-center px-4">
            <h1 class="text-white text-5xl font-bold"><?= htmlspecialchars($pack['title']); ?></h1>
        </div>
    </div>

    <!-- CONTENU PRINCIPAL -->
    <div class="container mx-auto px-4 py-12">
        <!-- DESCRIPTION -->
        <div class="bg-black text-white p-6 rounded-lg shadow-lg mb-12 max-w-4xl mx-auto text-center">
            <h2 class="text-2xl font-bold mb-4">📖 Description du pack</h2>
            <p class="text-lg leading-relaxed"><?= nl2br(htmlspecialchars($pack['description'])); ?></p>
        </div>

        <!-- INFORMATIONS DU PACK -->
        <div class="bg-white p-6 rounded-lg shadow-md max-w-3xl mx-auto">
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">📋 Détails du Pack</h2>
            <ul class="list-disc space-y-2 pl-5 text-gray-700">
                <li><strong>Prix :</strong> <?= htmlspecialchars($pack['price']); ?> €</li>
                <li><strong>Durée :</strong> <?= isset($pack['duration']) ? htmlspecialchars($pack['duration']) . ' heures' : 'Non spécifiée'; ?></li>
                <li><strong>Ce qui est inclus :</strong> <?= isset($pack['included']) ? nl2br(htmlspecialchars($pack['included'])) : 'Non spécifié'; ?></li>
            </ul>
        </div>

        <!-- BOUTONS ACTION -->
        <div class="flex justify-center mt-8 space-x-4">
            <a href="reservation_pack?pack_id=<?= htmlspecialchars($pack['id']); ?>"
                class="bg-[#8B5A2B] text-white px-6 py-3 rounded-md text-lg font-semibold transition duration-300 hover:scale-105 hover:shadow-lg">
                📅 Réserver ce pack
            </a>

            <a href="evenements"
                class="border-2 border-[#8B5A2B] text-[#8B5A2B] px-6 py-3 rounded-md text-lg font-semibold transition duration-300 hover:scale-105 hover:shadow-lg">
                🔙 Retour aux packs
            </a>
        </div>
    </div>

<?php else : ?>
    <!-- Si le pack est introuvable -->
    <div class="container mx-auto text-center py-12">
        <h2 class="text-3xl text-red-600">Pack introuvable</h2>
        <p>Ce pack n'existe pas ou a été supprimé.</p>
    </div>
<?php endif; ?>

<?php include('src/app/Views/includes/events/footerEvents.php'); ?>