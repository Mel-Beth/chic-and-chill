<?php
// Inclusion des fichiers communs pour l'interface admin
include('src/app/Views/includes/admin/admin_head.php'); // Contient les métadonnées et styles de base
include('src/app/Views/includes/admin/admin_header.php'); // En-tête avec navigation
include('src/app/Views/includes/admin/admin_sidebar.php'); // Barre latérale de navigation
?>

<style>
    /* Styles pour les aperçus des médias */
    .media-preview img,
    .media-preview video {
        max-width: 150px;
        /* Limite la largeur maximale */
        max-height: 150px;
        /* Limite la hauteur maximale */
        object-fit: cover;
        /* Redimensionne tout en conservant les proportions */
        border-radius: 4px;
        /* Coins arrondis */
        margin: 10px;
        /* Espacement autour */
    }

    /* Styles pour personnaliser l'entrée de fichier */
    .custom-file-input {
        position: relative;
        /* Positionnement relatif pour superposer les éléments */
        display: inline-block;
        width: 100%;
    }

    .custom-file-input input[type="file"] {
        position: absolute;
        /* Superpose l'entrée réelle */
        opacity: 0;
        /* Rends invisible */
        width: 100%;
        height: 100%;
        cursor: pointer;
        /* Curseur de clic */
    }

    .custom-file-input .file-display {
        width: 100%;
        padding: 12px;
        /* Espacement interne */
        border: 1px solid #d1d5db;
        /* Bordure grise claire */
        border-radius: 4px;
        background-color: #fff;
        display: flex;
        justify-content: space-between;
        /* Espace entre texte et bouton */
        align-items: center;
        cursor: pointer;
    }

    .custom-file-input .file-display span {
        color: #6b7280;
        /* Texte gris pour le nom du fichier */
    }

    .custom-file-input .file-display .browse-btn {
        color: #8B5A2B;
        /* Couleur marron personnalisée */
        font-weight: 600;
        /* Texte en gras */
    }

    /* Media query pour écrans mobiles */
    @media (max-width: 640px) {
        .media-preview {
            flex-direction: column;
            /* Empile les éléments verticalement */
            align-items: center;
            /* Centre les éléments */
        }
    }
</style>

<div class="min-h-screen flex flex-col pl-0 md:pl-64 mt-12">
    <!-- Contenu principal avec marge gauche pour la sidebar sur écrans moyens et grands -->
    <div class="container mx-auto px-4 py-6 md:px-6 md:py-8 flex-grow">
        <h2 class="text-xl md:text-3xl font-bold text-gray-800 mb-6 md:mb-8">
            ⚙️ Configuration de l'événement : <?= htmlspecialchars($event['title']) ?>
            <!-- Titre avec protection contre les attaques XSS -->
        </h2>

        <?php if (isset($_GET['success']) && $_GET['success'] === 'media_added'): ?>
            <!-- Message de succès après ajout de média -->
            <div class="bg-green-500 text-white p-3 rounded-md mb-4 text-sm md:text-base">
                ✅ Médias ajoutés avec succès !
            </div>
        <?php endif; ?>

        <div class="bg-white p-4 md:p-6 rounded-lg shadow-md">
            <!-- Formulaire pour ajouter des médias -->
            <form action="admin/evenements/configurer/<?= $event['id'] ?>/media" method="POST" enctype="multipart/form-data">
                <h3 class="text-lg md:text-xl font-semibold mb-4">Ajouter des médias à la galerie</h3>
                <label for="mediaFiles" class="block text-sm text-gray-600 mb-2">Images ou vidéos</label>
                <div class="custom-file-input">
                    <input type="file" id="mediaFiles" name="media[]" multiple class="absolute opacity-0 w-full h-full cursor-pointer" accept="image/*,video/*">
                    <!-- Champ de fichier masqué avec acceptation d'images et vidéos -->
                    <div class="file-display">
                        <span id="mediaFileName">Choisir des fichiers</span>
                        <span class="browse-btn">Parcourir</span>
                    </div>
                </div>
                <button type="submit" class="mt-4 bg-[#8B5A2B] text-white px-4 py-2 md:px-6 md:py-2 rounded-md hover:scale-105 transition w-full md:w-auto text-sm md:text-base">
                    Ajouter
                </button>
            </form>

            <h3 class="text-lg md:text-xl font-semibold mt-6 md:mt-8 mb-4">Médias actuels</h3>
            <div class="media-preview flex flex-wrap justify-start md:justify-start">
                <?php
                $mediaList = $this->eventsModel->getEventMedia($event['id']); // Récupère les médias de l'événement
                if (!empty($mediaList)) :
                    foreach ($mediaList as $media) : // Boucle sur chaque média
                ?>
                        <div class="relative w-full sm:w-1/2 md:w-1/3 lg:w-1/4 p-2">
                            <?php if ($media['type'] === 'image') : ?>
                                <img src="<?= htmlspecialchars($media['image_url']) ?>" alt="Media" class="w-full">
                                <button class="text-red-600 hover:underline deleteMediaBtn absolute bottom-2 left-2 text-sm" data-id="<?= $media['id'] ?>">
                                    Supprimer
                                </button>
                            <?php elseif ($media['type'] === 'video') : ?>
                                <video src="<?= htmlspecialchars($media['image_url']) ?>" controls class="w-full"></video>
                                <button class="text-red-600 hover:underline deleteMediaBtn absolute bottom-2 left-2 text-sm" data-id="<?= $media['id'] ?>">
                                    Supprimer
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p class="text-sm md:text-base text-gray-500">Aucun média pour cet événement.</p>
                <?php endif; ?>
            </div>
        </div>

        <a href="admin/evenements" class="mt-4 md:mt-6 inline-block bg-gray-500 text-white px-4 py-2 md:px-6 md:py-3 rounded-md hover:bg-gray-600 w-full md:w-auto text-sm md:text-base text-center">
            ⬅️ Retour
        </a>
    </div>
</div>

<!-- Modale de confirmation de suppression -->
<div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white p-4 md:p-6 rounded-lg shadow-lg max-w-lg w-full">
        <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-4">⚠️ Confirmer la suppression</h3>
        <p class="text-gray-600 text-sm md:text-base">Voulez-vous vraiment supprimer ce média ? Cette action est irréversible.</p>
        <div class="flex flex-col md:flex-row justify-between mt-4 space-y-2 md:space-y-0 md:space-x-4">
            <button id="cancelDelete" class="bg-gray-500 text-white px-3 py-2 rounded-md hover:bg-gray-600 flex items-center w-full md:w-auto">
                ❌ Annuler
            </button>
            <button id="confirmDelete" class="bg-red-600 text-white px-4 py-2 md:px-6 md:py-3 rounded-md hover:scale-105 transition flex items-center w-full md:w-auto">
                ✅ Supprimer
            </button>
        </div>
    </div>
</div>

</body>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Mise à jour du nom du fichier sélectionné
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
        // Gestion des boutons de suppression
        document.querySelectorAll('.deleteMediaBtn').forEach(button => {
            button.addEventListener('click', function() {
                deleteMediaId = this.dataset.id; // Stocke l'ID du média à supprimer
                document.getElementById('deleteModal').classList.remove('hidden'); // Affiche la modale
            });
        });

        // Annulation de la suppression
        document.getElementById('cancelDelete').addEventListener('click', function() {
            document.getElementById('deleteModal').classList.add('hidden');
            deleteMediaId = null;
        });

        // Confirmation de la suppression
        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (deleteMediaId) {
                fetch(`admin/evenements/configurer/<?= $event['id'] ?>/supprimer_media/${deleteMediaId}`, {
                        method: 'DELETE' // Requête DELETE pour supprimer le média
                    })
                    .then(response => {
                        if (response.ok) {
                            document.querySelector(`button[data-id="${deleteMediaId}"]`).closest('div').remove(); // Supprime l'élément du DOM
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
</html>