<?php include('src/app/Views/includes/headEvents.php'); ?>
<?php include('src/app/Views/includes/headerEvents.php'); ?>

<style>
    /* =============================== */
    /* 1. Configuration générale       */
    /* =============================== */
    html,
    body {
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }

    /* =============================== */
    /* 2. Hero Section                 */
    /* =============================== */
    .hero-section {
        position: relative;
        width: 100%;
        height: 80vh;
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .hero-section::before {
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
    }

    .hero-content {
        position: relative;
        text-align: center;
        color: white;
        padding: 20px;
    }

    .hero-content h1 {
        font-size: clamp(2rem, 5vw, 4rem);
        font-weight: bold;
    }

    /* =============================== */
    /* 3. Sections de Contenu          */
    /* =============================== */
    .section-container {
        max-width: 1200px;
        margin: auto;
        padding: 40px 20px;
    }

    /* Styles des blocs */
    .text-block,
    .info-block {
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
    }

    .text-block {
        background: black;
        color: white;
        text-align: center;
    }

    .info-block {
        background: #f7f7f7;
        text-align: center;
    }

    /* =============================== */
    /* 4. Galerie Swiper               */
    /* =============================== */
    .swiper-container-gallery {
        max-width: 100%;
        margin: auto;
        padding: 20px;
    }

    /* Images de la galerie */
    .swiper-container-gallery img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 10px;
    }

    /* Personnalisation des flèches */
    .swiper-button-next-gallery,
    .swiper-button-prev-gallery {
        color: #8B5A2B !important;
    }

    .swiper-button-next-gallery::after,
    .swiper-button-prev-gallery::after {
        font-size: 30px !important;
    }

    /* =============================== */
    /* 5. Pagination des événements    */
    /* =============================== */
    .pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 800px;
        margin: 50px auto;
        padding-top: 20px;
        border-top: 1px solid #ddd;
    }

    .pagination-container a {
        display: flex;
        align-items: center;
        color: #8B5A2B;
        font-size: 1rem;
        text-decoration: none;
        transition: color 0.3s;
    }

    .pagination-container a:hover {
        color: black;
    }

    .pagination-container span {
        font-size: 1.2rem;
        font-weight: bold;
    }

    /* =============================== */
    /* 6. Bouton Retour                */
    /* =============================== */
    .return-button {
        display: block;
        text-align: center;
        background: #8B5A2B;
        color: white;
        padding: 15px 30px;
        font-size: 1.2rem;
        border-radius: 8px;
        margin: 30px auto;
        transition: transform 0.3s ease-in-out;
        max-width: 300px;
    }

    .return-button:hover {
        transform: scale(1.05);
    }

    /* =============================== */
    /* 7. Responsive Design            */
    /* =============================== */
    @media (max-width: 1024px) {
        .hero-section {
            height: 60vh;
        }

        .hero-content h1 {
            font-size: 2.5rem;
        }

        .text-block,
        .info-block {
            max-width: 90%;
            margin: auto;
        }
    }

    @media (max-width: 768px) {
        .hero-section {
            height: 50vh;
        }

        .pagination-container {
            flex-direction: column;
            text-align: center;
        }

        .pagination-container a {
            margin-bottom: 10px;
        }
    }

    @media (max-width: 480px) {
        .hero-content h1 {
            font-size: 2rem;
        }

        .return-button {
            font-size: 1rem;
            padding: 12px 20px;
        }
    }
</style>

<?php if (!empty($event)) : ?>
    <!-- HERO SECTION -->
    <div class="relative w-full h-screen bg-cover bg-center flex justify-center items-center"
        style="background-image: url('<?= BASE_URL ?>assets/images/events/<?= htmlspecialchars($event['image']); ?>');">
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
        <?php if (!empty($eventImages)) : ?>
            <div class="max-w-5xl mx-auto mt-12 overflow-hidden"> <!-- Empêche le débordement -->
                <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">📸 Galerie de l'événement</h2>

                <div class="swiper-container-gallery relative w-full">
                    <div class="swiper-wrapper">
                        <?php foreach ($eventImages as $image) : ?>
                            <div class="swiper-slide">
                                <img src="<?= BASE_URL . htmlspecialchars($image); ?>" alt="Photo de l'événement" class="rounded-lg shadow-lg">
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Navigation (flèches marron) -->
                    <div class="swiper-button-next swiper-button-next-gallery"></div>
                    <div class="swiper-button-prev swiper-button-prev-gallery"></div>
                    <div class="swiper-pagination mt-4"></div>
                </div>
            </div>
        <?php endif; ?>

        <!-- PAGINATION DES ÉVÉNEMENTS -->
        <div class="flex justify-between items-center max-w-4xl mx-auto mt-16 border-t border-gray-300 pt-8 w-full">
            <?php if (!empty($prevEvent)) : ?>
                <a href="<?= BASE_URL ?>evenement_detail?id=<?= $prevEvent['id'] ?>"
                    class="flex items-center text-gray-800 hover:text-[#8B5A2B] transition duration-300 flex-grow">
                    <span class="inline-block border border-gray-800 p-3 rounded-lg mr-4">←</span>
                    <span class="text-lg font-semibold"><?= htmlspecialchars($prevEvent['title']); ?></span>
                </a>
            <?php else : ?>
                <span class="flex-grow"></span>
            <?php endif; ?>

            <?php if (!empty($nextEvent)) : ?>
                <a href="<?= BASE_URL ?>evenement_detail?id=<?= $nextEvent['id'] ?>"
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
            <a href="<?= BASE_URL ?>evenements"
                class="inline-block bg-[#8B5A2B] text-white text-lg font-semibold px-8 py-4 rounded-md transition duration-300 hover:scale-105 hover:shadow-lg">
                Retour aux événements
            </a>
        </div>
    </div>
<?php else : ?>
    <div class="container mx-auto px-4 py-12 text-center">
        <h1 class="text-3xl font-bold text-red-600">Événement introuvable</h1>
        <p class="text-gray-600 mt-4">L'événement demandé n'existe pas ou a été supprimé.</p>
        <a href="<?= BASE_URL ?>evenements" class="mt-6 inline-block bg-[#8B5A2B] text-white text-lg font-semibold px-6 py-3 rounded-md transition duration-300 hover:scale-105 hover:shadow-lg">
            Retour aux événements
        </a>
    </div>
<?php endif; ?>

<?php include('src/app/Views/includes/footerEvents.php'); ?>

<!-- SwiperJS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

<style>
    /* Correction des flèches */
    .swiper-button-next-gallery,
    .swiper-button-prev-gallery {
        color: #8B5A2B !important;
        /* Marron Chic & Chill */
    }

    .swiper-button-next-gallery::after,
    .swiper-button-prev-gallery::after {
        font-size: 30px !important;
        /* Taille plus visible */
    }
</style>

<script>
    var swiperGallery = new Swiper('.swiper-container-gallery', {
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        slidesPerView: 3, // S'assure qu'on voit bien 3 images en même temps
        spaceBetween: 15, // Ajoute un petit espace entre les images
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
                slidesPerView: 1 // Affiche une seule image sur petit écran
            },
            768: {
                slidesPerView: 2 // Affiche 2 images sur tablette
            },
            1024: {
                slidesPerView: 3 // Affiche 3 images sur grand écran
            }
        }
    });
</script>