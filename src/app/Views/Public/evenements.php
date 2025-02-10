<?php include('src/app/Views/includes/headEvents.php'); ?>
<?php include('src/app/Views/includes/headerEvents.php'); ?>

<style>
    /* Empêcher le débordement du carrousel */
    html,
    body {
        overflow-x: hidden;
    }

    /* Swiper - Carrousel des tenues */
    .swiper-container-tenues {
        max-width: 900px;
        margin: auto;
        padding: 40px 0;
    }

    .swiper-slide {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #fff;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
        transition: transform 0.5s ease-in-out;
    }

    /* Swiper Effet Coverflow */
    .swiper-slide-active {
        transform: scale(1.2);
        z-index: 10;
    }

    .swiper-slide-prev,
    .swiper-slide-next {
        transform: scale(0.9);
        opacity: 0.8;
    }

    .swiper-pagination {
        margin-top: 15px;
    }

    /* Personnalisation des flèches Swiper */
    .swiper-button-next-tenues,
    .swiper-button-prev-tenues {
        color: #8B5A2B !important;
        /* Applique la couleur marron */
        font-size: 20px;
    }

    .swiper-button-next-tenues::after,
    .swiper-button-prev-tenues::after {
        font-size: 30px !important;
        /* Augmente la taille des flèches */
        color: #8B5A2B !important;
        /* Change la couleur en marron */
    }
</style>

<!-- CARROUSEL SWIPER PLEIN ÉCRAN -->
<div class="swiper-container h-screen w-full">
    <div class="swiper-wrapper">
        <?php foreach ($events as $event) : ?>
            <div class="swiper-slide relative w-full h-screen bg-cover bg-center flex justify-center items-center" style="background-image: url('assets/images/events/<?= htmlspecialchars($event['image']) ?>');">
                <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center text-center px-4">
                    <h1 class="text-white text-5xl font-bold"><?= htmlspecialchars($event['title']) ?></h1>
                    <p class="text-white text-lg mt-4 max-w-2xl"><?= htmlspecialchars($event['description']) ?></p>
                    <a href="evenement_detail.php?id=<?= $event['id'] ?>" class="inline-block text-white bg-[#8B5A2B] px-4 py-2 text-sm rounded-md font-medium transition duration-300 hover:scale-105 hover:shadow-lg">En savoir plus</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <!-- Pagination et Navigation -->
    <div class="swiper-pagination-tenues absolute bottom-0 left-1/2 transform -translate-x-1/2 mt-4"></div>
    <div class="swiper-button-next swiper-button-next-tenues"></div>
    <div class="swiper-button-prev swiper-button-prev-tenues"></div>
</div>

<!-- SwiperJS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

<script>
    var swiper = new Swiper('.swiper-container', {
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        effect: 'slide',
        speed: 1200,
    });
</script>

<!-- GRILLE DES ÉVÉNEMENTS STYLE JOUR & NUIT -->
<div class="container mx-auto px-4 py-12">
    <h2 class="text-4xl font-bold text-center text-gray-800 mb-12 uppercase tracking-wide">Nos Événements</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php foreach ($events as $event) : ?>
            <div class="relative group bg-white shadow-lg rounded-lg overflow-hidden max-w-[350px] mx-auto">
                <!-- Image de l'événement -->
                <img src="<?= BASE_URL ?>assets/images/events/<?= htmlspecialchars($event['image']) ?>"
                    class="w-full h-[400px] object-cover transition-transform duration-500 group-hover:scale-105">

                <!-- Contenu affiché au survol -->
                <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-end p-6 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    <h3 class="text-white text-xl font-semibold text-center"><?= htmlspecialchars($event['title']) ?></h3>
                    <p class="text-gray-300 text-sm mt-2 text-center"><?= htmlspecialchars($event['description']) ?></p>

                    <!-- BOUTON EN SAVOIR PLUS PETIT ET CENTRÉ -->
                    <div class="text-center mt-3">
                        <a href="evenement_detail.php?id=<?= $event['id'] ?>"
                            class="inline-block text-white bg-[#8B5A2B] px-4 py-2 text-sm rounded-md font-medium transition duration-300 hover:scale-105 hover:shadow-lg">
                            En savoir plus
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- BOUTON RÉSERVATION -->
<div class="text-center mt-12">
    <a href="contact" class="inline-block border-2 border-[#8B5A2B] text-[#8B5A2B] text-lg font-semibold px-8 py-4 rounded-md transition duration-300 hover:scale-105 hover:shadow-lg">
        Réserver votre événement
    </a>
</div>

<!-- IDÉES DE TENUES -->
<div class="container mx-auto px-4 py-12 mt-12">
    <h2 class="text-4xl font-bold text-center mb-8 p-12 bg-black text-white">💡 Idées de tenues</h2>

    <div class="swiper-container-tenues max-w-4xl mx-auto relative">
        <div class="swiper-wrapper">
            <?php if (!empty($suggestedOutfits)) : ?>
                <?php foreach ($suggestedOutfits as $tenue) : ?>
                    <div class="swiper-slide bg-white shadow-lg rounded-xl p-6 flex flex-col items-center justify-center text-center transition duration-300 transform scale-90">
                        <?php if (!empty($tenue['image'])) : ?>
                            <img src="<?= BASE_URL ?>assets/images/products/<?= htmlspecialchars($tenue['image']); ?>"
                                alt="<?= htmlspecialchars($tenue['outfit_name']); ?>"
                                class="w-40 h-40 object-cover rounded-md mb-4">
                        <?php else : ?>
                            <div class="w-40 h-40 bg-gray-200 flex items-center justify-center text-gray-500 rounded-md mb-4">
                                Aucune image
                            </div>
                        <?php endif; ?>
                        <h4 class="font-semibold text-xl"><?= htmlspecialchars($tenue['outfit_name']); ?></h4>
                        <p class="text-gray-600 text-sm mt-2 max-w-md"><?= htmlspecialchars($tenue['accessories']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p class="text-gray-500 text-center col-span-3">Aucune suggestion disponible pour le moment.</p>
            <?php endif; ?>
        </div>

        <!-- Pagination et Navigation -->
        <div class="swiper-pagination-tenues absolute bottom-0 left-1/2 transform -translate-x-1/2 mt-4"></div>
        <div class="swiper-button-next swiper-button-next-tenues"></div>
        <div class="swiper-button-prev swiper-button-prev-tenues"></div>
    </div>
</div>

<!-- SwiperJS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

<script>
    var swiperTenues = new Swiper('.swiper-container-tenues', {
        effect: 'coverflow',
        grabCursor: true,
        centeredSlides: true,
        loop: true,
        slidesPerView: "auto",
        coverflowEffect: {
            rotate: 0,
            stretch: 50,
            depth: 180,
            modifier: 2.5,
            slideShadows: false,
        },
        autoplay: {
            delay: 6000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },
        pagination: {
            el: '.swiper-pagination-tenues',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next-tenues',
            prevEl: '.swiper-button-prev-tenues',
        },
        breakpoints: {
            640: {
                slidesPerView: 1.5
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

<!-- PACKS ÉVÉNEMENTIELS -->
<div class="container mx-auto px-4 py-12">
    <h2 class="text-4xl font-bold text-center mb-8 p-12 bg-black text-white">🎁 Packs événementiels</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-5xl mx-auto">
        <?php if (!empty($eventPacks)) : ?>
            <?php foreach ($eventPacks as $pack) : ?>
                <div class="relative bg-black text-white rounded-lg shadow-lg overflow-hidden group transition-all duration-500 h-64 flex flex-col items-center justify-center">

                    <!-- Bordure intérieure épaisse qui s’amincit -->
                    <div class="absolute inset-2 border-[3px] border-[#8B5A2B] transition-all duration-500 group-hover:inset-4 group-hover:border-[1px]"></div>

                    <!-- Titre qui se soulève légèrement -->
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-lg md:text-xl font-semibold text-center transition-all duration-500 group-hover:-translate-y-6">
                        <?= htmlspecialchars($pack['title']); ?>
                    </div>

                    <!-- Contenu caché qui apparaît progressivement -->
                    <div class="absolute bottom-4 w-full px-6 text-center opacity-0 transition-all duration-500 group-hover:opacity-100 group-hover:bottom-10">
                        <p class="text-sm"><?= htmlspecialchars($pack['description']); ?></p>
                        <button class="mt-4 bg-[#8B5A2B] text-white px-4 py-2 rounded-md transition duration-300 hover:scale-105 hover:shadow-lg">
                            Découvrir
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p class="text-gray-500 text-center">Aucun pack disponible pour le moment.</p>
        <?php endif; ?>
    </div>
</div>

<!-- SECTION À PROPOS DE CHIC & CHILL -->
<div class="container mx-auto px-4 py-12">
    <div class="bg-black text-white text-center py-12">
        <h2 class="text-3xl font-bold uppercase tracking-wide">L'Expérience Chic & Chill</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-12 py-12">
        <!-- Bloc "Notre Concept" -->
        <div class="text-center">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Notre Concept</h3>
            <p class="text-gray-600">
                Chic & Chill est plus qu’une boutique, c’est un **univers où l’élégance rencontre l’accessibilité**.
                Nous proposons des vêtements chics à la vente et à la location, pour que chacun puisse se sentir unique, sans compromis sur le prix ni sur l’éthique.
            </p>
        </div>

        <!-- Bloc "Notre Vision" -->
        <div class="text-center">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Notre Vision</h3>
            <p class="text-gray-600">
                Nous croyons en une mode **plus responsable et inclusive**.
                Chic & Chill valorise la **seconde main et l’éco-responsabilité** en mettant en avant des pièces soigneusement sélectionnées, pour offrir une mode chic et engagée.
            </p>
        </div>

        <!-- Bloc "Notre Engagement" -->
        <div class="text-center">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Notre Engagement</h3>
            <p class="text-gray-600">
                Chaque article est pensé pour **s’adapter à tous les budgets** et occasions.
                Que ce soit pour une location événementielle ou un achat coup de cœur, **notre mission est de rendre la mode chic accessible à tous**.
            </p>
        </div>
    </div>
</div>


<?php include('src/app/Views/includes/footerEvents.php'); ?>