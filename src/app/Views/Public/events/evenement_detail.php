<?php include('src/app/Views/includes/events/headEvents.php'); ?>
<?php include('src/app/Views/includes/events/headerEvents.php'); ?>

<?php if (!empty($event)) : ?>
    <!-- HERO SECTION -->
    <div class="relative w-full h-96 bg-cover bg-center flex justify-center items-center">
        <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center text-center px-4">
            <h1 class="text-white text-5xl font-bold"><?= htmlspecialchars($event['title']); ?></h1>
        </div>
    </div>

    <!-- CONTENU PRINCIPAL -->
    <div class="container mx-auto px-4 py-12">
        <!-- DESCRIPTION -->
        <div class="bg-black text-white p-6 rounded-lg shadow-lg mb-12 max-w-4xl mx-auto text-center">
            <h2 class="text-2xl font-bold mb-4">📖 Description de l'événement</h2>
            <p class="text-lg leading-relaxed"><?= nl2br(htmlspecialchars($event['description'])); ?></p>
        </div>

        <!-- INFORMATIONS -->
        <div class="bg-gray-100 p-6 rounded-lg shadow-lg max-w-xl mx-auto text-center">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">📅 Informations</h2>
            <ul class="list-none space-y-2">
                <li><strong>Date :</strong> <?= date('d F Y', strtotime($event['date_event'])); ?></li>
                <li><strong>Lieu :</strong> <?= htmlspecialchars($event['location']); ?></li>
            </ul>
        </div>

        <!-- GALERIE SWIPER (bien intégrée) -->
        <?php if (!empty($eventMedia)) : ?>
            <div class="max-w-5xl mx-auto mt-12 overflow-hidden">
                <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">📸 Galerie de l'événement</h2>

                <div class="swiper-container-gallery relative w-full">
                    <div class="swiper-wrapper">
                        <?php foreach ($eventMedia as $media) : ?>
                            <div class="swiper-slide">
                                <?php if ($media['type'] === 'image') : ?>
                                    <!-- Affichage des images -->
                                    <img src="assets/images/events/<?= htmlspecialchars($media['image_url']); ?>"
                                        alt="Image de l'événement"
                                        class="rounded-lg shadow-lg w-full h-64 object-cover">
                                <?php elseif ($media['type'] === 'video') : ?>
                                    <!-- Affichage des vidéos (Mute activé) -->
                                    <video muted autoplay loop playsinline class="rounded-lg shadow-lg w-full h-64 object-cover">
                                        <source src="assets/images/events/<?= htmlspecialchars($media['image_url']); ?>" type="video/mp4">
                                        Votre navigateur ne supporte pas la vidéo.
                                    </video>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Navigation -->
                    <div class="swiper-button-next swiper-button-next-gallery"></div>
                    <div class="swiper-button-prev swiper-button-prev-gallery"></div>
                    <div class="swiper-pagination mt-4"></div>
                </div>
            </div>
        <?php endif; ?>

        <!-- PAGINATION DES ÉVÉNEMENTS -->
        <div class="flex justify-between items-center max-w-4xl mx-auto mt-16 border-t border-gray-300 pt-8 w-full">
            <?php if (!empty($prevEvent)) : ?>
                <a href="evenement_detail?id=<?= $prevEvent['id'] ?>"
                    class="flex items-center text-gray-800 hover:text-[#8B5A2B] transition duration-300 flex-grow">
                    <span class="inline-block border border-gray-800 p-3 rounded-lg mr-4">←</span>
                    <span class="text-lg font-semibold"><?= htmlspecialchars($prevEvent['title']); ?></span>
                </a>
            <?php else : ?>
                <span class="flex-grow"></span>
            <?php endif; ?>

            <?php if (!empty($nextEvent)) : ?>
                <a href="evenement_detail?id=<?= $nextEvent['id'] ?>"
                    class="flex items-center text-gray-800 hover:text-[#8B5A2B] transition duration-300 flex-grow justify-end">
                    <span class="text-lg font-semibold"><?= htmlspecialchars($nextEvent['title']); ?></span>
                    <span class="inline-block border border-gray-800 p-3 rounded-lg ml-4">→</span>
                </a>
            <?php else : ?>
                <span class="flex-grow"></span>
            <?php endif; ?>
        </div>

        <!-- BOUTON RETOUR -->
        <div class="text-center mt-12">
            <a href="evenements"
                class="inline-block bg-[#8B5A2B] text-white text-lg font-semibold px-8 py-4 rounded-md transition duration-300 hover:scale-105 hover:shadow-lg">
                Retour aux événements
            </a>
        </div>
    </div>
<?php else : ?>
    <div class="container mx-auto px-4 py-12 text-center">
        <h1 class="text-3xl font-bold text-red-600">Événement introuvable</h1>
        <p class="text-gray-600 mt-4">L'événement demandé n'existe pas ou a été supprimé.</p>
        <a href="evenements" class="mt-6 inline-block bg-[#8B5A2B] text-white text-lg font-semibold px-6 py-3 rounded-md transition duration-300 hover:scale-105 hover:shadow-lg">
            Retour aux événements
        </a>
    </div>
<?php endif; ?>

<?php include('src/app/Views/includes/events/footerEvents.php'); ?>

<!-- SwiperJS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

<script>
    var swiperGallery = new Swiper('.swiper-container-gallery', {
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        slidesPerView: 3,
        spaceBetween: 15,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next-gallery',
            prevEl: '.swiper-button-prev-gallery',
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
</script>