<?php
include('src/app/Views/includes/admin/admin_head.php');
include('src/app/Views/includes/admin/admin_header.php');
include('src/app/Views/includes/admin/admin_sidebar.php');
?>

<style>
    .media-preview img, .media-preview video {
        max-width: 150px;
        max-height: 150px;
        object-fit: cover;
        border-radius: 4px;
        margin: 10px;
    }
    .custom-file-input {
        position: relative;
        display: inline-block;
        width: 100%;
    }
    .custom-file-input input[type="file"] {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }
    .custom-file-input .file-display {
        width: 100%;
        padding: 12px;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        background-color: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
    }
    .custom-file-input .file-display span {
        color: #6b7280;
    }
    .custom-file-input .file-display .browse-btn {
        color: #8B5A2B;
        font-weight: 600;
    }
</style>

<div class="min-h-screen flex flex-col lg:pl-64 mt-12">
    <div class="container mx-auto px-6 py-8 flex-grow">
        <h2 class="text-3xl font-bold text-gray-800 mb-8">⚙️ Configuration de l'événement : <?= htmlspecialchars($event['title']) ?></h2>

        <?php if (isset($_GET['success']) && $_GET['success'] === 'media_added'): ?>
            <div class="bg-green-500 text-white p-3 rounded-md mb-4">
                ✅ Médias ajoutés avec succès !
            </div>
        <?php endif; ?>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <!-- Formulaire pour ajouter des médias -->
            <form action="admin/evenements/configurer/<?= $event['id'] ?>/media" method="POST" enctype="multipart/form-data">
                <h3 class="text-xl font-semibold mb-4">Ajouter des médias à la galerie</h3>
                <label for="mediaFiles" class="block text-sm text-gray-600 mb-2">Images ou vidéos</label>
                <div class="custom-file-input">
                    <input type="file" id="mediaFiles" name="media[]" multiple class="absolute opacity-0 w-full h-full cursor-pointer" accept="image/*,video/*">
                    <div class="file-display">
                        <span id="mediaFileName">Choisir des fichiers</span>
                        <span class="browse-btn">Parcourir</span>
                    </div>
                </div>
                <button type="submit" class="mt-4 bg-[#8B5A2B] text-white px-6 py-2 rounded-md hover:scale-105 transition">Ajouter</button>
            </form>

            <!-- Aperçu des médias existants -->
            <h3 class="text-xl font-semibold mt-8 mb-4">Médias actuels</h3>
            <div class="media-preview flex flex-wrap">
                <?php
                $mediaList = $this->eventsModel->getEventMedia($event['id']);
                if (!empty($mediaList)) :
                    foreach ($mediaList as $media) :
                        if ($media['type'] === 'image') : ?>
                            <div class="relative">
                                <img src="<?= htmlspecialchars($media['image_url']) ?>" alt="Media">
                                <button class="text-red-600 hover:underline deleteMediaBtn absolute bottom-0 left-0" data-id="<?= $media['id'] ?>">Supprimer</button>
                            </div>
                        <?php elseif ($media['type'] === 'video') : ?>
                            <div class="relative">
                                <video src="<?= htmlspecialchars($media['image_url']) ?>" controls></video>
                                <button class="text-red-600 hover:underline deleteMediaBtn absolute bottom-0 left-0" data-id="<?= $media['id'] ?>">Supprimer</button>
                            </div>
                        <?php endif; ?>
                <?php endforeach;
                else : ?>
                    <p>Aucun média pour cet événement.</p>
                <?php endif; ?>
            </div>
        </div>

        <a href="admin/evenements" class="mt-6 inline-block bg-gray-500 text-white px-6 py-3 rounded-md hover:bg-gray-600">Retour</a>
    </div>
</div>

<script src="src/js/common.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Gestion de l'input file
        document.getElementById("mediaFiles").addEventListener("change", function(e) {
            const files = e.target.files;
            const fileNameDisplay = document.getElementById("mediaFileName");
            if (files.length > 0) {
                fileNameDisplay.textContent = files.length === 1 ? files[0].name : `${files.length} fichiers sélectionnés`;
            } else {
                fileNameDisplay.textContent = "Choisir des fichiers";
            }
        });

        // Gestion de la suppression des médias
        setupDeleteWithConfirmation({
            deleteBtnSelector: '.deleteMediaBtn',
            deleteModalId: 'deleteModal', // Note : Il manque une modale ici, il faudra l'ajouter
            deleteUrlPrefix: `admin/evenements/configurer/<?= $event['id'] ?>/supprimer_media`,
            successMessage: 'Média supprimé avec succès.',
            errorMessage: 'Erreur lors de la suppression.',
            tableRowSelector: '.media-preview div:has([data-id="${deleteId}"])'
        });
    });
</script>