<?php include('src/app/Views/includes/headEvents.php'); ?>
<?php include('src/app/Views/includes/headerEvents.php'); ?>

<!-- HERO SECTION -->
<div class="relative w-full h-96 bg-cover bg-center flex justify-center items-center">
    <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center text-center px-4">
        <h1 class="text-white text-5xl font-bold">Réserver un évènement</h1>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <h2 class="text-4xl font-bold text-center mb-8 p-12 bg-black text-white">📅 Réserver un événement pour votre entreprise</h2>

    <!-- Vérification s'il y a des événements -->
    <?php if (!empty($events)) : ?>
        <form action="<?= BASE_URL ?>reservation_process.php" method="post" class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-md">

            <!-- Informations de l'entreprise -->
            <h3 class="text-xl font-bold text-gray-800 mt-6">🏢 Informations de l’entreprise</h3>

            <label for="company_name" class="block text-lg font-semibold text-gray-800 mt-4">Nom de l'entreprise :</label>
            <input type="text" name="company_name" id="company_name" required class="w-full p-3 border border-gray-300 rounded-md">

            <label for="siret" class="block text-lg font-semibold text-gray-800 mt-4">SIRET (si applicable) :</label>
            <input type="text" name="siret" id="siret" class="w-full p-3 border border-gray-300 rounded-md" placeholder="Ex: 123 456 789 00012">

            <label for="address" class="block text-lg font-semibold text-gray-800 mt-4">Adresse de facturation :</label>
            <input type="text" name="address" id="address" required class="w-full p-3 border border-gray-300 rounded-md">

            <!-- Coordonnées du contact -->
            <h3 class="text-xl font-bold text-gray-800 mt-6">📞 Coordonnées du responsable</h3>

            <label for="name" class="block text-lg font-semibold text-gray-800 mt-4">Nom du responsable :</label>
            <input type="text" name="name" id="name" required class="w-full p-3 border border-gray-300 rounded-md">

            <label for="email" class="block text-lg font-semibold text-gray-800 mt-4">Email :</label>
            <input type="email" name="email" id="email" required class="w-full p-3 border border-gray-300 rounded-md">

            <label for="phone" class="block text-lg font-semibold text-gray-800 mt-4">Téléphone :</label>
            <input type="tel" name="phone" id="phone" required class="w-full p-3 border border-gray-300 rounded-md">

            <!-- Détails de l'événement -->
            <h3 class="text-xl font-bold text-gray-800 mt-6">🎭 Détails de l’événement</h3>

            <label for="event_type" class="block text-lg font-semibold text-gray-800 mt-4">Type d’événement :</label>
            <select name="event_type" id="event_type" required class="w-full p-3 border border-gray-300 rounded-md">
                <option value="Séminaire">Séminaire</option>
                <option value="Lancement de produit">Lancement de produit</option>
                <option value="Gala">Gala</option>
                <option value="Réception privée">Réception privée</option>
                <option value="Autre">Autre (préciser en commentaire)</option>
            </select>

            <label for="participants" class="block text-lg font-semibold text-gray-800 mt-4">Nombre de participants :</label>
            <input type="number" name="participants" id="participants" min="1" required class="w-full p-3 border border-gray-300 rounded-md">

            <label for="services" class="block text-lg font-semibold text-gray-800 mt-4">Prestations supplémentaires :</label>
            <div class="flex flex-wrap gap-4">
                <label class="flex items-center">
                    <input type="checkbox" name="services[]" value="Catering" class="mr-2"> 🍽️ Service traiteur
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

            <label for="comments" class="block text-lg font-semibold text-gray-800 mt-4">Commentaires / Demandes spécifiques :</label>
            <textarea name="comments" id="comments" rows="4" class="w-full p-3 border border-gray-300 rounded-md"></textarea>

            <button type="submit" class="mt-6 bg-[#8B5A2B] text-white px-6 py-3 rounded-md w-full text-lg font-semibold transition duration-300 hover:scale-105 hover:shadow-lg">
                ✅ Envoyer la demande de réservation
            </button>
        </form>
    <?php else : ?>
        <p class="text-center text-red-600 font-semibold">Aucun événement disponible à la réservation pour le moment.</p>
    <?php endif; ?>
</div>

<?php include('src/app/Views/includes/footerEvents.php'); ?>