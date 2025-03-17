<?php
// Inclure le header spécifique (avec Tailwind, base href corrigé, etc.)
include __DIR__ . '/../includes/header_loc.php';
?>

<main class="container mx-auto px-4 py-10">
    <h1 class="text-4xl font-bold text-center mb-8 text-gray-800">Louer un produit</h1>
    
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md mx-auto">
        <form id="rentalForm" class="grid grid-cols-1 gap-6">
            <div>
                <label class="block text-gray-700 mb-2">Produit</label>
                <select name="product_id" class="w-full border-gray-300 rounded-md p-2" required>
                    <!-- Remplir articles -->
                </select>
            </div>
            <div>
                <label class="block text-gray-700 mb-2">Date de début</label>
                <input type="date" name="start_date" class="w-full border-gray-300 rounded-md p-2" required>
            </div>
            <div>
                <label class="block text-gray-700 mb-2">Date de fin</label>
                <input type="date" name="end_date" class="w-full border-gray-300 rounded-md p-2" required>
            </div>
            <button type="submit" class="w-full bg-black text-white py-3 rounded hover:bg-gray-800">
                Confirmer
            </button>
        </form>
    </div>
</main>

<script>
document.getElementById('rentalForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    // L’URL dépend de la façon dont ton routeur appelle RentalController->process()
    fetch('index.php?page=rental_process', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.success) {
            this.reset();
        }
    })
    .catch(error => console.error('Erreur:', error));
});
</script>

<?php
// Inclure le footer spécifique (avec fermetures du body/html)
include __DIR__ . '/../includes/footer_loc.php';
