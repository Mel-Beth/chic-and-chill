<?php
include('src/app/Views/includes/admin/admin_head.php');
include('src/app/Views/includes/admin/admin_header.php');
include('src/app/Views/includes/admin/admin_sidebar.php');
?>

<style>
    .media-preview img,
    .media-preview video {
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

    @media (max-width: 640px) {
        .media-preview {
            flex-direction: column;
            align-items: center;
        }
    }
</style>

<div class="min-h-screen flex flex-col pl-0 md:pl-64 mt-12">
    <div class="container mx-auto px-4 py-6 md:px-6 md:py-8 flex-grow">
        <h2 class="text-xl md:text-3xl font-bold text-gray-800 mb-6 md:mb-8">⚙️ Configuration de l'événement : <?= htmlspecialchars($event['title']) ?></h2>

        <?php if (isset($_GET['success']) && $_GET['success'] === 'media_added'): ?>
            <div class="bg-green-500 text-white p-3 rounded-md mb-4 text-sm md:text-base">
                ✅ Médias ajoutés avec succès !
            </div>
        <?php endif; ?>

        <div class="bg-white p-4 md:p-6 rounded-lg shadow-md">
            <form action="admin/evenements/configurer/<?= $event['id'] ?>/media" method="POST" enctype="multipart/form-data">
                <h3 class="text-lg md:text-xl font-semibold mb-4">Ajouter des médias à la galerie</h3>
                <label for="mediaFiles" class="block text-sm text-gray-600 mb-2">Images ou vidéos</label>
                <div class="custom-file-input">
                    <input type="file" id="mediaFiles" name="media[]" multiple class="absolute opacity-0 w-full h-full cursor-pointer" accept="image/*,video/*">
                    <div class="file-display">
                        <span id="mediaFileName">Choisir des fichiers</span>
                        <span class="browse-btn">Parcourir</span>
                    </div>
                </div>
                <button type="submit" class="mt-4 bg-[#8B5A2B] text-white px-4 py-2 md:px-6 md:py-2 rounded-md hover:scale-105 transition w-full md:w-auto text-sm md:text-base">Ajouter</button>
            </form>

            <h3 class="text-lg md:text-xl font-semibold mt-6 md:mt-8 mb-4">Médias actuels</h3>
            <div class="media-preview flex flex-wrap justify-start md:justify-start">
                <?php
                $mediaList = $this->eventsModel->getEventMedia($event['id']);
                if (!empty($mediaList)) :
                    foreach ($mediaList as $media) :
                ?>
                        <div class="relative w-full sm:w-1/2 md:w-1/3 lg:w-1/4 p-2">
                            <?php if ($media['type'] === 'image') : ?>
                                <img src="<?= htmlspecialchars($media['image_url']) ?>" alt="Media" class="w-full">
                                <button class="text-red-600 hover:underline deleteMediaBtn absolute bottom-2 left-2 text-sm" data-id="<?= $media['id'] ?>">Supprimer</button>
                            <?php elseif ($media['type'] === 'video') : ?>
                                <video src="<?= htmlspecialchars($media['image_url']) ?>" controls class="w-full"></video>
                                <button class="text-red-600 hover:underline deleteMediaBtn absolute bottom-2 left-2 text-sm" data-id="<?= $media['id'] ?>">Supprimer</button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach;
                else : ?>
                    <p class="text-sm md:text-base text-gray-500">Aucun média pour cet événement.</p>
                <?php endif; ?>
            </div>
        </div>

        <a href="admin/evenements" class="mt-4 md:mt-6 inline-block bg-gray-500 text-white px-4 py-2 md:px-6 md:py-3 rounded-md hover:bg-gray-600 w-full md:w-auto text-sm md:text-base text-center">Retour</a>
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white p-4 md:p-6 rounded-lg shadow-lg max-w-lg w-full">
        <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-4">⚠️ Confirmer la suppression</h3>
        <p class="text-gray-600 text-sm md:text-base">Voulez-vous vraiment supprimer ce média ? Cette action est irréversible.</p>
        <div class="flex flex-col md:flex-row justify-between mt-4 space-y-2 md:space-y-0 md:space-x-4">
            <button id="cancelDelete" class="bg-gray-500 text-white px-3 py-2 rounded-md hover:bg-gray-600 flex items-center w-full md:w-auto">❌ Annuler</button>
            <button id="confirmDelete" class="bg-red-600 text-white px-4 py-2 md:px-6 md:py-3 rounded-md hover:scale-105 transition flex items-center w-full md:w-auto">✅ Supprimer</button>
        </div>
    </div>
</div>

<script src="src/js/common.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("mediaFiles").addEventListener("change", function(e) {
            const files = e.target.files;
            const fileNameDisplay = document.getElementById("mediaFileName");
            if (files.length > 0) {
                fileNameDisplay.textContent = files.length === 1 ? files[0].name : `${files.length} fichiers sélectionnés`;
            } else {
                fileNameDisplay.textContent = "Choisir des fichiers";
            }
        });

        let deleteMediaId = null;
        document.querySelectorAll('.deleteMediaBtn').forEach(button => {
            button.addEventListener('click', function() {
                deleteMediaId = this.dataset.id;
                document.getElementById('deleteModal').classList.remove('hidden');
            });
        });

        document.getElementById('cancelDelete').addEventListener('click', function() {
            document.getElementById('deleteModal').classList.add('hidden');
            deleteMediaId = null;
        });

        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (deleteMediaId) {
                fetch(`admin/evenements/configurer/<?= $event['id'] ?>/supprimer_media/${deleteMediaId}`, {
                        method: 'DELETE'
                    })
                    .then(response => {
                        if (response.ok) {
                            document.querySelector(`button[data-id="${deleteMediaId}"]`).closest('div').remove();
                            alert('Média supprimé avec succès.');
                        } else {
                            alert('Erreur lors de la suppression.');
                        }
                        document.getElementById('deleteModal').classList.add('hidden');
                    })
                    .catch(error => console.error('Erreur:', error));
            }
        });
    });
</script>