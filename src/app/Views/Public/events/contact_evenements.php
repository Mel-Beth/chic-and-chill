<?php include('src/app/Views/includes/events/headEvents.php'); ?>
<?php include('src/app/Views/includes/events/headerEvents.php'); ?>

<!-- HERO SECTION -->
<div class="relative w-full h-96 bg-cover bg-center flex justify-center items-center">
    <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center text-center px-4">
        <h1 class="text-white text-5xl font-bold">Nous contacter</h1>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <div class="bg-black text-white p-6 rounded-lg shadow-lg mb-12 max-w-4xl mx-auto text-center">

        <h2 class="text-2xl font-bold mb-4">📩 Contact - Magasin</h2>
    </div>
    <?php if (isset($_GET['success']) && $_GET['success'] == 1) : ?>
        <p class="text-green-600 text-center font-semibold">Votre message a bien été envoyé !</p>
    <?php endif; ?>

    <form action="contact_process" method="post" class="max-w-2xl mx-auto bg-gray-100 p-6 rounded-lg shadow-md">
        <input type="hidden" name="source" value="evenements">

        <label for="name" class="block text-gray-700 font-semibold">Nom :</label>
        <input type="text" name="name" id="name" required class="w-full p-2 border border-gray-300 rounded-md mb-4">

        <label for="email" class="block text-gray-700 font-semibold">Email :</label>
        <input type="email" name="email" id="email" required class="w-full p-2 border border-gray-300 rounded-md mb-4">

        <label for="subject" class="block text-gray-700 font-semibold">Sujet :</label>
        <input type="text" name="subject" id="subject" required class="w-full p-2 border border-gray-300 rounded-md mb-4">

        <label for="message" class="block text-gray-700 font-semibold">Message :</label>
        <textarea name="message" id="message" rows="5" required class="w-full p-2 border border-gray-300 rounded-md mb-4"></textarea>

        <button type="submit" class="bg-[#8B5A2B] text-white px-6 py-2 rounded-md w-full">Envoyer</button>
    </form>
</div>

<?php include('src/app/Views/includes/events/footerEvents.php'); ?>