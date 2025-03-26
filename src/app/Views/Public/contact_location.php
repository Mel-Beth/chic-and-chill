<div class="container mx-auto px-4 py-12">
    <h2 class="text-4xl font-bold text-center mb-8 p-12 bg-black text-white">📩 Contact - Magasin</h2>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1) : ?>
        <p class="text-green-600 text-center font-semibold">Votre message a bien été envoyé !</p>
    <?php endif; ?>

    <form action="contact_process" method="post" class="max-w-2xl mx-auto bg-gray-100 p-6 rounded-lg shadow-md">
        <input type="hidden" name="source" value="location">

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