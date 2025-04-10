<?php
// Création d'un formateur de date en français pour un affichage personnalisé
$formatter = new IntlDateFormatter(
    'fr_FR',
    IntlDateFormatter::LONG, // Format long pour le mois (ex. "mars")
    IntlDateFormatter::NONE, // Pas d'heure
    'Europe/Paris',
    IntlDateFormatter::GREGORIAN,
    'd MMMM yyyy' // Format : jour mois année (ex. "15 mars 2025")
);
?>

<?php
include('src/app/Views/includes/events/headEvents.php'); // Inclusion des métadonnées
include('src/app/Views/includes/events/headerEvents.php'); // Inclusion de la navigation
?>

<?php if (!empty($event)) : ?>
    <!-- Section héroïque avec image de fond -->
    <div class="relative w-full h-96 bg-cover bg-center flex justify-center items-center">
        <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center text-center px-4">
            <h1 class="text-white text-5xl font-bold"><?= htmlspecialchars($event['title']); ?></h1> <!-- Titre sécurisé -->
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="container mx-auto px-4 py-12">
        <!-- Description de l'événement -->
        <div class="bg-black text-white p-6 rounded-lg shadow-lg mb-12 max-w-4xl mx-auto text-center">
            <h2 class="text-2xl font-bold mb-4">📖 Description de l'événement</h2>
            <?php
            // Vérification si l'événement est à venir ou passé
            $eventDate = strtotime($event['date_event']);
            $currentDate = time();
            $isFutureEvent = $eventDate > $currentDate;

            if ($isFutureEvent) : ?>
                <p class="text-lg leading-relaxed">
                    Cet événement est à venir ! Restez à l'écoute pour plus de détails passionnants.
                    Réservez votre place dès maintenant en <a href="reservation_evenement" class="text-[#8B5A2B] underline hover:text-white">cliquant ici</a>.
                </p>
            <?php else : ?>
                <p class="text-lg leading-relaxed"><?= nl2br(htmlspecialchars($event['description'])); ?></p> <!-- Description sécurisée avec sauts de ligne -->
            <?php endif; ?>
        </div>

        <!-- Informations de l'événement -->
        <div class="bg-gray-100 p-6 rounded-lg shadow-lg max-w-xl mx-auto text-center">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">📅 Informations</h2>
            <ul class="list-none space-y-2">
                <li><strong>Date :</strong> <?= $formatter->format(new DateTime($event['date_event'])); ?></li> <!-- Date formatée -->
                <li><strong>Lieu :</strong> <?= htmlspecialchars($event['location']); ?></li> <!-- Lieu sécurisé -->
            </ul>
        </div>

        <!-- Galerie Swiper pour les médias -->
        <?php if (!empty($eventMedia)) : ?>
            <div class="max-w-5xl mx-auto mt-12 overflow-hidden">
                <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">📸 Galerie de l'événement</h2>
                <div class="swiper-container-gallery relative w-full">
                    <div class="swiper-wrapper">
                        <?php foreach ($eventMedia as $index => $media) : ?>
                            <div class="swiper-slide">
                                <?php if ($media['type'] === 'image') : ?>
                                    <!-- Image cliquable pour ouvrir la modale -->
                                    <img src="assets/images/events/<?= htmlspecialchars($media['image_url']); ?>"
                                        alt="Image de l'événement"
                                        class="rounded-lg shadow-lg w-full h-64 object-cover cursor-pointer"
                                        onclick="openMediaModal(<?= $index ?>)">
                                <?php elseif ($media['type'] === 'video') : ?>
                                    <!-- Vidéo cliquable pour ouvrir la modale -->
                                    <video muted autoplay loop playsinline
                                        class="rounded-lg shadow-lg w-full h-64 object-cover cursor-pointer"
                                        onclick="openMediaModal(<?= $index ?>)">
                                        <source src="assets/images/events/<?= htmlspecialchars($media['image_url']); ?>" type="video/mp4">
                                        Votre navigateur ne supporte pas la vidéo.
                                    </video>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <!-- Navigation Swiper -->
                    <div class="swiper-button-next swiper-button-next-gallery"></div>
                    <div class="swiper-button-prev swiper-button-prev-gallery"></div>
                    <div class="swiper-pagination mt-4"></div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Modale pour afficher les médias en grand -->
        <div id="mediaModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
            <div class="relative w-full max-w-4xl max-h-[90vh] p-4 sm:p-6">
                <button onclick="closeMediaModal()" class="absolute top-4 right-4 text-white text-3xl sm:text-4xl font-bold">×</button> <!-- Bouton de fermeture -->
                <button onclick="showPreviousMedia()" class="absolute left-2 sm:left-4 top-1/2 transform -translate-y-1/2 text-white text-2xl sm:text-3xl font-bold">←</button> <!-- Bouton précédent -->
                <button onclick="showNextMedia()" class="absolute right-2 sm:right-4 top-1/2 transform -translate-y-1/2 text-white text-2xl sm:text-3xl font-bold">→</button> <!-- Bouton suivant -->
                <div id="mediaModalContent" class="w-full h-full flex items-center justify-center transition-opacity duration-300">
                    <!-- Contenu inséré dynamiquement -->
                </div>
                <div id="mediaPositionIndicator" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white text-lg sm:text-xl font-semibold bg-black bg-opacity-50 px-4 py-2 rounded-full"></div> <!-- Indicateur de position -->
            </div>
        </div>

        <!-- Pagination des événements -->
        <div class="flex justify-between items-center max-w-4xl mx-auto mt-16 border-t border-gray-300 pt-8 w-full">
            <?php if (!empty($prevEvent)) : ?>
                <a href="evenement_detail?id=<?= $prevEvent['id'] ?>"
                    class="flex items-center text-gray-800 hover:text-[#8B5A2B] transition duration-300 flex-grow">
                    <span class="inline-block border border-gray-800 p-3 rounded-lg mr-4 hover:bg-[#8B5A2B] hover:text-white">←</span>
                    <span class="text-lg font-semibold"><?= htmlspecialchars($prevEvent['title']); ?></span>
                </a>
            <?php else : ?>
                <span class="flex-grow"></span> <!-- Espace vide si pas d'événement précédent -->
            <?php endif; ?>

            <?php if (!empty($nextEvent)) : ?>
                <a href="evenement_detail?id=<?= $nextEvent['id'] ?>"
                    class="flex items-center text-gray-800 hover:text-[#8B5A2B] transition duration-300 flex-grow justify-end">
                    <span class="text-lg font-semibold"><?= htmlspecialchars($nextEvent['title']); ?></span>
                    <span class="inline-block border border-gray-800 p-3 rounded-lg ml-4 hover:bg-[#8B5A2B] hover:text-white">→</span>
                </a>
            <?php else : ?>
                <span class="flex-grow"></span> <!-- Espace vide si pas d'événement suivant -->
            <?php endif; ?>
        </div>

        <!-- Bouton retour -->
        <div class="text-center mt-12">
            <a href="evenements"
                class="inline-block bg-[#8B5A2B] text-white text-lg font-semibold px-8 py-4 rounded-md transition duration-300 hover:scale-105 hover:shadow-lg">
                Retour aux événements
            </a>
        </div>
    </div>
<?php else : ?>
    <!-- Message si l'événement n'existe pas -->
    <div class="container mx-auto px-4 py-12 text-center">
        <h1 class="text-3xl font-bold text-red-600">Événement introuvable</h1>
        <p class="text-gray-600 mt-4">L'événement demandé n'existe pas ou a été supprimé.</p>
        <a href="evenements" class="mt-6 inline-block bg-[#8B5A2B] text-white text-lg font-semibold px-6 py-3 rounded-md transition duration-300 hover:scale-105 hover:shadow-lg">
            Retour aux événements
        </a>
    </div>
<?php endif; ?>

<?php include('src/app/Views/includes/events/footerEvents.php'); ?>

<!-- Inclusion de Swiper pour la galerie -->
<link rel="stylesheet" href="src/libs/swiper-bundle.min.css" />
<script src="src/libs/swiper-bundle.min.js"></script>

<script>
    // Tableau des médias pour la navigation dans la modale
    const eventMedia = <?php
                        $mediaArray = array_map(function ($media) {
                            return [
                                'type' => $media['type'],
                                'url' => 'assets/images/events/' . $media['image_url']
                            ];
                        }, $eventMedia);
                        echo json_encode($mediaArray, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
                        ?>;
    let currentMediaIndex = 0;

    // Préchargement des médias voisins pour améliorer la fluidité
    function preloadMedia(index) {
        const prevIndex = (index - 1 + eventMedia.length) % eventMedia.length;
        const nextIndex = (index + 1) % eventMedia.length;

        const prevMedia = eventMedia[prevIndex];
        if (prevMedia.type === 'image') {
            const img = new Image();
            img.src = prevMedia.url;
        } else if (prevMedia.type === 'video') {
            const video = document.createElement('video');
            video.src = prevMedia.url;
            video.preload = 'auto';
        }

        const nextMedia = eventMedia[nextIndex];
        if (nextMedia.type === 'image') {
            const img = new Image();
            img.src = nextMedia.url;
        } else if (nextMedia.type === 'video') {
            const video = document.createElement('video');
            video.src = nextMedia.url;
            video.preload = 'auto';
        }
    }

    // Initialisation de Swiper pour la galerie
    var swiperGallery = new Swiper('.swiper-container-gallery', {
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false
        },
        slidesPerView: 3,
        spaceBetween: 15,
        pagination: {
            el: '.swiper-pagination',
            clickable: true
        },
        navigation: {
            nextEl: '.swiper-button-next-gallery',
            prevEl: '.swiper-button-prev-gallery'
        },
        effect: 'slide',
        speed: 1200,
        breakpoints: {
            640: {
                slidesPerView: 1
            },
            768: {
                slidesPerView: 2
            },
            1024: {
                slidesPerView: 3
            }
        }
    });

    // Ouvre la modale avec le média sélectionné
    function openMediaModal(index) {
        currentMediaIndex = index;
        const modal = document.getElementById('mediaModal');
        const modalContent = document.getElementById('mediaModalContent');
        modalContent.style.opacity = '0';
        modalContent.innerHTML = '';

        const media = eventMedia[currentMediaIndex];
        if (media.type === 'image') {
            const img = document.createElement('img');
            img.src = media.url;
            img.className = 'max-w-full max-h-[80vh] object-contain rounded-lg';
            modalContent.appendChild(img);
        } else if (media.type === 'video') {
            const video = document.createElement('video');
            video.controls = true;
            video.autoplay = true;
            video.muted = true;
            video.className = 'max-w-full max-h-[80vh] object-contain rounded-lg';
            const source = document.createElement('source');
            source.src = media.url;
            source.type = 'video/mp4';
            video.appendChild(source);
            modalContent.appendChild(video);
        }

        preloadMedia(currentMediaIndex);
        updatePositionIndicator();
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.style.opacity = '1';
        }, 50);
    }

    // Affiche le média précédent
    function showPreviousMedia() {
        currentMediaIndex = (currentMediaIndex - 1 + eventMedia.length) % eventMedia.length;
        openMediaModal(currentMediaIndex);
    }

    // Affiche le média suivant
    function showNextMedia() {
        currentMediaIndex = (currentMediaIndex + 1) % eventMedia.length;
        openMediaModal(currentMediaIndex);
    }

    // Met à jour l'indicateur de position
    function updatePositionIndicator() {
        const indicator = document.getElementById('mediaPositionIndicator');
        indicator.textContent = `${currentMediaIndex + 1}/${eventMedia.length}`;
    }

    // Ferme la modale
    function closeMediaModal() {
        const modal = document.getElementById('mediaModal');
        const modalContent = document.getElementById('mediaModalContent');
        modalContent.style.opacity = '0';
        setTimeout(() => {
            modalContent.innerHTML = '';
            modal.classList.add('hidden');
        }, 300);
    }

    // Ferme la modale en cliquant à l'extérieur
    document.getElementById('mediaModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeMediaModal();
        }
    });

    // Ferme la modale avec la touche Échap
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeMediaModal();
        }
    });

    // Navigation avec les touches fléchées
    document.addEventListener('keydown', function(e) {
        if (!document.getElementById('mediaModal').classList.contains('hidden')) {
            if (e.key === 'ArrowLeft') {
                showPreviousMedia();
            } else if (e.key === 'ArrowRight') {
                showNextMedia();
            }
        }
    });

    // Navigation tactile (swipe)
    let touchStartX = 0;
    let touchEndX = 0;

    document.getElementById('mediaModalContent').addEventListener('touchstart', function(e) {
        touchStartX = e.changedTouches[0].screenX;
    });

    document.getElementById('mediaModalContent').addEventListener('touchend', function(e) {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    });

    function handleSwipe() {
        const swipeThreshold = 50;
        if (touchStartX - touchEndX > swipeThreshold) {
            showNextMedia();
        } else if (touchEndX - touchStartX > swipeThreshold) {
            showPreviousMedia();
        }
    }
</script>

<style>
    /* Transition pour la modale */
    #mediaModal {
        transition: opacity 0.3s ease;
    }

    #mediaModal.hidden {
        opacity: 0;
        pointer-events: none;
    }

    #mediaModal:not(.hidden) {
        opacity: 1;
        pointer-events: auto;
    }

    /* Transition pour les boutons */
    #mediaModal button {
        transition: background-color 0.3s ease;
    }

    #mediaModal button:hover {
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
    }

    /* Transition pour l'indicateur */
    #mediaPositionIndicator {
        transition: opacity 0.3s ease;
    }

    /* Ajustements responsifs */
    @media (max-width: 640px) {
        #mediaModal .max-w-4xl {
            max-width: 90%;
        }

        #mediaModal button {
            font-size: 1.5rem;
        }

        #mediaPositionIndicator {
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
        }
    }

    @media (min-width: 641px) and (max-width: 1024px) {
        #mediaModal .max-w-4xl {
            max-width: 80%;
        }
    }
</style>