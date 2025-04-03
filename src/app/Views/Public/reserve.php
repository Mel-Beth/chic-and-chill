<?php include 'src/app/Views/includes/header_loc.php'; ?>
<br><br><br><br>

<main class="container mx-auto px-4 py-12 max-w-xl">
    <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Valider votre réservation</h1>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <form method="POST" action="/site_stage/chic-and-chill/location/reserve">
            <div class="mb-4">
                <label for="client_nom" class="block font-medium text-gray-700">Nom</label>
                <input type="text" name="client_nom" required class="mt-1 block w-full border rounded px-4 py-2">
            </div>

            <div class="mb-4">
                <label for="email" class="block font-medium text-gray-700">Email</label>
                <input type="email" name="email" required class="mt-1 block w-full border rounded px-4 py-2">
            </div>

            <div class="mb-4">
                <label for="start_date" class="block font-medium text-gray-700">Date de début</label>
                <input type="date" name="start_date" required class="mt-1 block w-full border rounded px-4 py-2">
            </div>

            <div class="mb-4">
                <label for="end_date" class="block font-medium text-gray-700">Date de fin</label>
                <input type="date" name="end_date" required class="mt-1 block w-full border rounded px-4 py-2">
            </div>

            <button type="submit" class="w-full bg-[#b71c1c] text-white py-2 rounded hover:bg-[#ff0000] transition">
                Confirmer la réservation
            </button>
        </form>
    </div>
</main>

<?php include 'src/app/Views/includes/footer_loc.php'; ?>