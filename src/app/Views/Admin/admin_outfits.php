<?php 
include('src/app/Views/includes/admin_head.php');
include('src/app/Views/includes/admin_header.php'); 
?>

<div class="container mx-auto px-4 py-12">
    <h2 class="text-4xl font-bold text-center mb-8 p-12 bg-black text-white">👗 Gestion des Idées de Tenues</h2>

    <!-- Notifications des articles en rupture de stock -->
    <?php $notifications = (new Controllers\AdminEventsController())->checkOutfitStock(); ?>
    <?php if (!empty($notifications)) : ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
            <p class="font-bold">⚠️ Articles en rupture de stock :</p>
            <ul>
                <?php foreach ($notifications as $notif) : ?>
                    <li><?= htmlspecialchars($notif) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Formulaire d'ajout -->
    <form action="admin/outfits/add" method="post" class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h3 class="text-xl font-bold text-gray-800 mb-4">➕ Ajouter une nouvelle idée de tenue</h3>

        <label for="title" class="block text-lg font-semibold text-gray-800 mt-4">Nom de la tenue :</label>
        <input type="text" name="title" id="title" required class="w-full p-3 border border-gray-300 rounded-md">

        <label for="description" class="block text-lg font-semibold text-gray-800 mt-4">Description :</label>
        <textarea name="description" id="description" rows="3" class="w-full p-3 border border-gray-300 rounded-md"></textarea>

        <label for="image" class="block text-lg font-semibold text-gray-800 mt-4">Image :</label>
        <input type="text" name="image" id="image" class="w-full p-3 border border-gray-300 rounded-md" placeholder="Lien vers l'image">

        <label for="products" class="block text-lg font-semibold text-gray-800 mt-4">Articles inclus :</label>
        <select name="products[]" multiple class="w-full p-3 border border-gray-300 rounded-md">
            <?php foreach ($products as $product) : ?>
                <option value="<?= $product['id'] ?>"><?= htmlspecialchars($product['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="mt-6 bg-[#8B5A2B] text-white px-6 py-3 rounded-md w-full text-lg font-semibold transition duration-300 hover:scale-105 hover:shadow-lg">
            ✅ Ajouter la tenue
        </button>
    </form>

    <!-- Liste des tenues -->
    <div class="mt-12">
        <h3 class="text-2xl font-bold text-gray-800 mb-4">Liste des idées de tenues</h3>
        <?php foreach ($outfits as $outfit) : ?>
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h4 class="text-xl font-semibold"><?= htmlspecialchars($outfit['title']) ?></h4>
                <p><?= htmlspecialchars($outfit['description']) ?></p>
                <a href="admin/outfits/delete/<?= $outfit['id'] ?>" class="text-red-600">🗑️ Supprimer</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include('src/app/Views/includes/admin_footer.php'); ?>
