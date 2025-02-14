<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Chic & Chill</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-100">

<header class="bg-[#8B5A2B] text-white py-4">
    <div class="container mx-auto flex justify-between items-center px-6">
        <h1 class="text-2xl font-bold">📊 Admin - Chic & Chill</h1>
        <nav>
            <ul class="flex space-x-6">
                <li><a href="<?= BASE_URL ?>admin_evenements" class="hover:underline">Événements</a></li>
                <li><a href="<?= BASE_URL ?>admin/packs" class="hover:underline">Packs</a></li>
                <li><a href="<?= BASE_URL ?>admin/reservations" class="hover:underline">Réservations</a></li>
                <li><a href="<?= BASE_URL ?>admin/users" class="hover:underline">Utilisateurs</a></li>
                <li><a href="<?= BASE_URL ?>admin/messages" class="hover:underline">Messages</a></li>
                <li><a href="<?= BASE_URL ?>admin/newsletter" class="hover:underline">Newsletter</a></li>
            </ul>
        </nav>
        <a href="<?= BASE_URL ?>" class="text-sm bg-white text-[#8B5A2B] px-4 py-2 rounded-md hover:bg-gray-200">Retour au site</a>
    </div>
</header>
