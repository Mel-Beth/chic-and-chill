<?php include __DIR__ . '/../includes/header_loc.php';
?>
 


<main class="relative min-h-screen flex flex-col items-center justify-start py-12">
    <div class="relative z-10 w-full max-w-6xl mx-auto pt-20">
        <h1 class="text-4xl font-bold text-center mb-12 tracking-wide text-gray-800">SHOWROOM & RÉSERVATION</h1>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div class="bg-white shadow-lg rounded-lg p-8">
                <h2 class="text-2xl font-semibold mb-6">Notre Showroom</h2>
                <img src="assets/images/ShowroomMagasin.webp" alt="Notre Showroom" class="w-full h-64 object-cover rounded-lg mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                    <div>
                        <span class="text-3xl">📍</span>
                        <h3 class="text-lg font-semibold mt-2">Localisation</h3>
                        <p class="text-gray-600">10 rue Irénée Carré, 08000 Charleville-Mézières</p>
                    </div>
                    <div>
                        <span class="text-3xl">⏰</span>
                        <h3 class="text-lg font-semibold mt-2">Horaires</h3>
                        <p class="text-gray-600">Mardi-Samedi<br>9h-19h</p>
                    </div>
                    <div>
                        <span class="text-3xl">📅</span>
                        <h3 class="text-lg font-semibold mt-2">Rendez-vous</h3>
                        <a href="#reservation" class="inline-block mt-2 px-4 py-2 bg-black text-white rounded hover:bg-gray-800">Réserver</a>
                    </div>
                </div>
            </div>

            <div id="reservation" class="bg-white shadow-lg rounded-lg p-8">
                <h2 class="text-2xl font-semibold mb-6">Réservez votre essayage</h2>
                <form id="reservationForm" class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-gray-700 mb-2">Choisissez une date</label>
                        <input type="date" id="datePicker" name="date" class="w-full border-gray-300 rounded-md p-2">
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">Votre nom</label>
                        <input type="text" id="clientNom" name="client_nom" class="w-full border-gray-300 rounded-md p-2" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">Votre email</label>
                        <input type="email" id="clientEmail" name="email" class="w-full border-gray-300 rounded-md p-2" required>
                    </div>
                    <button type="submit" id="confirmBtn" class="mt-6 w-full bg-black text-white py-3 rounded hover:bg-gray-800">Confirmer</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer_loc.php';
 ?>
