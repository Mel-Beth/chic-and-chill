<?php include __DIR__ . '/../includes/header_loc.php'; ?>

<br><br><br><br>

<section class="bg-gradient-to-r from-[#B2AC88] to-[#8B5A2B] text-white text-center py-12">
    <h1 class="text-4xl font-bold uppercase tracking-wide">Location de Robes</h1>
    <p class="text-lg mt-2">Explorez notre collection et louez la tenue parfaite pour vos occasions spéciales.</p>
</section>

<section class="bg-gray-100 py-16">
    <div class="text-center mb-10">
        <h2 class="text-2xl font-bold text-gray-800">Nos robes disponibles</h2>
        <p class="text-gray-600">Choisissez votre favorite et découvrez-la en détail.</p>
    </div>

    <!-- Filtre par couleur -->
    <div class="text-center mb-8">
        <form method="GET" action="">
            <label for="color" class="text-gray-700 font-semibold">Filtrer par couleur :</label>
            <select name="color" id="color" class="border rounded p-2">
                <option value="">Toutes les couleurs</option>
                <?php foreach ($colors as $c): ?>
                    <option value="<?= htmlspecialchars($c['color']) ?>" <?= (isset($_GET['color']) && $_GET['color'] == $c['color']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['color']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="ml-2 bg-[#8B5A2B] text-white py-1 px-4 rounded">Filtrer</button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 px-6">
        <?php foreach ($products as $p): ?>
            <div class="relative group cursor-pointer rounded-xl overflow-hidden shadow-md bg-white">
                <div
                    class="absolute -inset-1 bg-gradient-to-r from-red-600 to-violet-600 rounded-xl blur opacity-25 group-hover:opacity-100 transition duration-1000 group-hover:duration-200">
                </div>
                <div class="relative rounded-xl overflow-hidden">
                    <img src="/site_stage/chic-and-chill/assets/images/products/<?= htmlspecialchars($p['image']); ?>"
                         alt="<?= htmlspecialchars($p['name']); ?>"
                         class="w-full h-72 object-cover">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($p['name']); ?></h3>
                        <p class="text-gray-600 mb-3"><?= number_format($p['price'], 2); ?> € / jour</p>
                        <div class="flex space-x-2">
                            <button
                                class="px-4 py-2 bg-[#8B5A2B] text-white rounded hover:bg-[#B2AC88] transition open-details"
                                data-product='<?= json_encode($p) ?>'>
                                Détails & Réservation
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer_loc.php'; ?>
