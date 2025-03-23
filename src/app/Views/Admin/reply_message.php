<?php
include('src/app/Views/includes/admin/admin_head.php');
include('src/app/Views/includes/admin/admin_header.php');
include('src/app/Views/includes/admin/admin_sidebar.php');
?>

<div class="min-h-screen flex flex-col lg:pl-64 mt-12">
    <div class="container mx-auto px-6 py-8 flex-grow">
        <h2 class="text-3xl font-bold text-gray-800 mb-8">📩 Répondre au message</h2>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold">Message original</h3>
            <p><strong>De :</strong> <?= htmlspecialchars($message['name']) ?> (<?= htmlspecialchars($message['email']) ?>)</p>
            <p><strong>Source :</strong> <?= ucfirst(htmlspecialchars($message['source'])) ?></p>
            <p><strong>Message :</strong></p>
            <blockquote class="border-l-4 pl-4 italic text-gray-600"><?= nl2br(htmlspecialchars($message['message'])) ?></blockquote>

            <form action="admin/messages/reply/<?= $message['id'] ?>" method="POST" class="mt-6">
                <label for="reply_body" class="block text-gray-700 font-semibold mb-2">Votre réponse :</label>
                <textarea id="reply_body" name="reply_body" rows="5" class="w-full border p-3 rounded-md focus:ring focus:ring-[#8B5A2B]" required></textarea>
                <button type="submit" class="mt-4 bg-green-500 text-white px-6 py-3 rounded-md hover:scale-105 transition">Envoyer la réponse</button>
            </form>
        </div>
    </div>
</div>