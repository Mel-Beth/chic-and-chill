<?php include __DIR__ . '/../includes/header_showroom.php'; ?>
<br><br><br>

<main class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-7xl mx-auto px-4">

        <!-- Bandeau Promo -->
        <div style="background: linear-gradient(to right, #ff416c, #ff4b2b); color: white; padding: 1rem 0; text-align: center;">
            🎉 -20% sur les séances maquillage • 👗 Essayage VIP à -30% cette semaine • 🎁 Showroom privatisé offert pour tout achat d'une robe •
        </div>

        <!-- Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-10">
            <!-- Informations Showroom -->
            <div class="bg-white p-6 shadow-lg rounded-lg">
                <h2 class="text-2xl font-bold text-center mb-4">Notre Showroom</h2>
                <img src="/site_stage/chic-and-chill/assets/images/ShowroomMagasin.webp" alt="Showroom" class="w-full h-64 object-cover rounded-lg mb-6">

                <div class="space-y-4 text-center">
                    <div>
                        <div class="font-bold">Localisation</div>
                        <p>10 rue Irénée Carré, 08000 Charleville-Mézières</p>
                    </div>
                    <div>
                        <div class="font-bold">Horaires</div>
                        <p>Mardi - Samedi <br> 9h - 19h</p>
                    </div>
                    <div>
                        <div class="font-bold">Services</div>
                        <p>Essayage, Showroom, Maquillage</p>
                    </div>
                </div>
            </div>

            <!-- Formulaire de Réservation -->
            <div class="bg-white p-6 shadow-lg rounded-lg">
                <h2 class="text-2xl font-bold text-center mb-6">Prenez un rendez-vous</h2>

                <form method="POST" action="/site_stage/chic-and-chill/showroom">
                    <div class="mb-4">
                        <label for="client_nom" class="block text-gray-700">Nom :</label>
                        <input type="text" name="client_nom" required class="w-full border rounded px-4 py-2">
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700">Email :</label>
                        <input type="email" name="email" required class="w-full border rounded px-4 py-2">
                    </div>
                    <div class="mb-4">
                        <label for="telephone" class="block text-gray-700">Téléphone :</label>
                        <input type="tel" name="telephone" required class="w-full border rounded px-4 py-2">
                    </div>
                    <div class="mb-4">
                        <label for="date_reservation" class="block text-gray-700">Date :</label>
                        <input type="date" name="date_reservation" required class="w-full border rounded px-4 py-2">
                    </div>
                    <div class="mb-4">
                        <label for="heure_reservation" class="block text-gray-700">Heure :</label>
                        <input type="time" name="heure_reservation" required class="w-full border rounded px-4 py-2">
                    </div>
                    <div class="mb-4">
                        <label for="service" class="block text-gray-700">Service :</label>
                        <select name="service" required class="w-full border rounded px-4 py-2">
                            <option value="">Choisir un service</option>
                            <option value="Showroom">Showroom</option>
                            <option value="Maquillage">Maquillage</option>
                            <option value="Essayage">Essayage</option>
                        </select>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="bg-[#ca1f1f] text-white py-2 px-4 rounded hover:bg-[#B2AC88]">Réserver</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer_showroom.php'; ?>
