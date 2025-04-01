<?php include __DIR__ . '/../includes/header_loc.php'; ?>

<main class="container mx-auto px-4 py-12 max-w-xl">
    <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Réserver cette tenue</h1>

    <?php if (isset($product)) : ?>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4"><?= htmlspecialchars($product['name']) ?></h2>

            <form method="POST" action="/location/reserve">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

                <div class="mb-4">
                    <label for="client_nom" class="block font-medium text-gray-700">Nom</label>
                    <input type="text" name="client_nom" required class="mt-1 block w-full border rounded px-4 py-2">
                </div>

                <div class="mb-4">
                    <label for="email" class="block font-medium text-gray-700">Email</label>
                    <input type="email" name="email" required class="mt-1 block w-full border rounded px-4 py-2">
                </div>

                <div class="mb-4">
                    <label for="date_debut" class="block font-medium text-gray-700">Date de début</label>
                    <input type="date" name="date_debut" required class="mt-1 block w-full border rounded px-4 py-2">
                </div>

                <div class="mb-4">
                    <label for="date_fin" class="block font-medium text-gray-700">Date de fin</label>
                    <input type="date" name="date_fin" required class="mt-1 block w-full border rounded px-4 py-2">
                </div>

                <button type="submit" class="w-full bg-[#8B5A2B] text-white py-2 rounded hover:bg-[#B2AC88]">
                    Valider la réservation
                </button>
            </form>
        </div>
    <?php else : ?>
        <p class="text-center text-red-500">Produit introuvable.</p>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/../includes/footer_loc.php'; ?>
