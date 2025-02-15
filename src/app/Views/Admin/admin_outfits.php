<?php 
include('src/app/Views/includes/admin_head.php');
include('src/app/Views/includes/admin_header.php'); 
?>

<div class="min-h-screen flex flex-col">
    <div class="container mx-auto px-4 py-12 flex-grow">
        <h2 class="text-4xl font-bold text-center mb-8 p-12 bg-black text-white">👗 Gestion des Idées de Tenues</h2>

        <!-- Formulaire d'ajout -->
        <form action="admin/outfits/ajouter" method="post" class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-md">
            <h3 class="text-xl font-bold text-gray-800 mb-4">➕ Ajouter une nouvelle idée de tenue</h3>

            <label for="title" class="block text-lg font-semibold text-gray-800 mt-4">Nom de la tenue :</label>
            <input type="text" name="title" id="title" required class="w-full p-3 border border-gray-300 rounded-md">

            <label for="description" class="block text-lg font-semibold text-gray-800 mt-4">Description :</label>
            <textarea name="description" id="description" rows="3" class="w-full p-3 border border-gray-300 rounded-md"></textarea>

            <label for="image" class="block text-lg font-semibold text-gray-800 mt-4">Image :</label>
            <input type="text" name="image" id="image" class="w-full p-3 border border-gray-300 rounded-md" placeholder="Lien vers l'image">

            <label for="products" class="block text-lg font-semibold text-gray-800 mt-4">Articles inclus :</label>
            <select name="products[]" multiple class="w-full p-3 border border-gray-300 rounded-md">
                <?php if (!empty($products)) : ?>
                    <?php foreach ($products as $product) : ?>
                        <option value="<?= htmlspecialchars($product['id']); ?>"><?= htmlspecialchars($product['name']); ?></option>
                    <?php endforeach; ?>
                <?php else : ?>
                    <option disabled>Aucun produit disponible</option>
                <?php endif; ?>
            </select>

            <button type="submit" class="mt-6 bg-[#8B5A2B] text-white px-6 py-3 rounded-md w-full text-lg font-semibold transition duration-300 hover:scale-105 hover:shadow-lg">
                ✅ Ajouter la tenue
            </button>
        </form>

        <!-- Liste des tenues -->
        <div class="mt-12">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">📌 Liste des idées de tenues</h3>
            <?php if (!empty($outfits)) : ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($outfits as $outfit) : ?>
                        <div class="bg-white p-6 rounded-lg shadow-md flex flex-col">
                            <h4 class="text-xl font-semibold"><?= htmlspecialchars($outfit['outfit_name']); ?></h4>
                            <p class="text-gray-600"><?= htmlspecialchars($outfit['accessories']); ?></p>
                            <img src="<?= htmlspecialchars($outfit['image'] ?: 'assets/images/events/placeholder.jpg'); ?>" alt="Image tenue" class="mt-3 rounded-md w-full h-40 object-cover">
                            <a href="admin/outfits/supprimer/<?= $outfit['id'] ?>" class="text-red-600 mt-3 text-center font-semibold hover:underline">🗑️ Supprimer</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p class="text-gray-500 text-center">Aucune idée de tenue enregistrée.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include('src/app/Views/includes/admin_footer.php'); ?>
</div>
