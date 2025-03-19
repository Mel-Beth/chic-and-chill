<?php include('src/app/Views/includes/events/headEvents.php'); ?>
<?php include('src/app/Views/includes/events/headerEvents.php'); ?>

<!-- HERO SECTION -->
<div class="relative w-full h-96 bg-cover bg-center flex justify-center items-center">
    <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center text-center px-4">
        <h1 class="text-white text-5xl font-bold">Réserver un pack</h1>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
<div class="bg-black text-white p-6 rounded-lg shadow-lg mb-12 max-w-4xl mx-auto text-center">
    <h2 class="text-2xl font-bold mb-4">🎟️ Réserver le Pack <?= htmlspecialchars($pack['title']); ?></h2>
</div>

    <?php if (!empty($pack)) : ?>
        <div class="bg-white p-6 rounded-lg shadow-md max-w-3xl mx-auto">
            <h2 class="text-2xl font-semibold text-gray-900 mb-4"><?= htmlspecialchars($pack['title']); ?></h2>
            <p class="text-gray-700"><?= nl2br(htmlspecialchars($pack['description'])); ?></p>

            <ul class="list-disc space-y-2 pl-5 text-gray-700 mt-4">
                <li><strong>Prix :</strong> <?= htmlspecialchars($pack['price']); ?> €</li>
                <li><strong>Durée :</strong> <?= isset($pack['duration']) ? htmlspecialchars($pack['duration']) . ' heures' : 'Non spécifiée'; ?></li>
                <li><strong>Ce qui est inclus :</strong> <?= isset($pack['included']) ? nl2br(htmlspecialchars($pack['included'])) : 'Non spécifié'; ?></li>
            </ul>

            <!-- FORMULAIRE DE RÉSERVATION -->
            <form action="reservation_process" method="post" class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-md">

                <!-- Champ caché pour l'ID du pack -->
                <input type="hidden" name="pack_id" value="<?= $pack['id']; ?>">

                <!-- Sélection du type de client -->
                <h3 class="text-xl font-bold text-gray-800">👤 Type de réservation</h3>
                <label class="block text-lg font-semibold text-gray-800 mt-4">Vous êtes :</label>
                <select name="customer_type" id="customer_type" class="w-full p-3 border border-gray-300 rounded-md" onchange="toggleClientFields()">
                    <option value="particulier">Particulier</option>
                    <option value="entreprise">Entreprise</option>
                </select>

                <!-- Champs pour les entreprises (affiché uniquement si entreprise sélectionnée) -->
                <div id="entreprise_fields" style="display: none;">
                    <h3 class="text-xl font-bold text-gray-800 mt-6">🏢 Informations de l’entreprise</h3>

                    <label for="company_name" class="block text-lg font-semibold text-gray-800 mt-4">Nom de l'entreprise :</label>
                    <input type="text" name="company_name" id="company_name" class="w-full p-3 border border-gray-300 rounded-md">

                    <label for="siret" class="block text-lg font-semibold text-gray-800 mt-4">SIRET (si applicable) :</label>
                    <input type="text" name="siret" id="siret" class="w-full p-3 border border-gray-300 rounded-md" placeholder="Ex: 123 456 789 00012">

                    <label for="address" class="block text-lg font-semibold text-gray-800 mt-4">Adresse de facturation :</label>
                    <input type="text" name="address" id="address" class="w-full p-3 border border-gray-300 rounded-md">
                </div>

                <!-- Coordonnées du client -->
                <h3 class="text-xl font-bold text-gray-800 mt-6">📞 Coordonnées</h3>

                <label for="name" class="block text-lg font-semibold text-gray-800 mt-4">Nom :</label>
                <input type="text" name="name" id="name" required class="w-full p-3 border border-gray-300 rounded-md">

                <label for="email" class="block text-lg font-semibold text-gray-800 mt-4">Email :</label>
                <input type="email" name="email" id="email" required class="w-full p-3 border border-gray-300 rounded-md">

                <label for="phone" class="block text-lg font-semibold text-gray-800 mt-4">Téléphone :</label>
                <input type="tel" name="phone" id="phone" required class="w-full p-3 border border-gray-300 rounded-md">

                <!-- Services supplémentaires -->
                <label for="services" class="block text-lg font-semibold text-gray-800 mt-4">Prestations supplémentaires :</label>
                <div class="flex flex-wrap gap-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="services[]" value="Restauration" class="mr-2"> 🍽️ Service traiteur
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="services[]" value="Animation" class="mr-2"> 🎤 Animation
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="services[]" value="Décoration" class="mr-2"> 🎨 Décoration
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="services[]" value="Photographe" class="mr-2"> 📸 Photographe
                    </label>
                </div>

                <label for="comments" class="block text-lg font-semibold text-gray-800 mt-4">Commentaires :</label>
                <textarea name="comments" id="comments" rows="4" class="w-full p-3 border border-gray-300 rounded-md"></textarea>

                <button type="submit" class="mt-6 bg-[#8B5A2B] text-white px-6 py-3 rounded-md w-full text-lg font-semibold transition duration-300 hover:scale-105 hover:shadow-lg">
                    ✅ Envoyer la demande de réservation
                </button>
            </form>
        </div>
    <?php else : ?>
        <p class="text-center text-red-600 font-semibold">Ce pack n'existe pas ou a été supprimé.</p>
    <?php endif; ?>
</div>

<?php include('src/app/Views/includes/events/footerEvents.php'); ?>
