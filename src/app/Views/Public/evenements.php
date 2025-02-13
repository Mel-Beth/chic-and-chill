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
    /* 2. Carrousel Swiper principal   */
    /* =============================== */
    .swiper-container {
        width: 100vw;
        height: 100vh;
    }

    /* Suppression des bordures indésirables */
    .swiper-container,
    .swiper-wrapper,
    .swiper-slide {
        box-sizing: border-box;
        border: none;
    }

    /* Gestion des slides */
    .swiper-slide {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #fff;
        transition: transform 0.5s ease-in-out;
        position: relative;
        overflow: hidden;
    }

    /* Animation des slides */
    .swiper-slide-active {
        transform: scale(1.1);
    }

    .swiper-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Overlay texte Swiper */
    .swiper-slide .overlay-content {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        background: rgba(0, 0, 0, 0.5);
        color: white;
        padding: 20px;
    }

    .overlay-content h1 {
        font-size: clamp(1.5rem, 5vw, 3rem);
        /* S'adapte aux écrans */
        font-weight: bold;
    }

    .overlay-content p {
        font-size: clamp(1rem, 3vw, 1.5rem);
        max-width: 80%;
    }

    /* =============================== */
    /* 3. Personnalisation des flèches */
    /* =============================== */
    .swiper-button-next,
    .swiper-button-prev {
        color: #8B5A2B !important;
    }

    .swiper-button-next::after,
    .swiper-button-prev::after {
        font-size: 40px !important;
        color: #8B5A2B !important;
    }

    /* =============================== */
    /* 4. Carrousel des tenues         */
    /* =============================== */
    .swiper-container-tenues {
        max-width: 900px;
        margin: auto;
        padding: 40px 0;
    }

    /* =============================== */
    /* 5. Grille des événements        */
    /* =============================== */
    .grid_events {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        justify-items: center;
        padding: 20px;
    }

    /* =============================== */
    /* 6. Styles des cartes événements */
    /* =============================== */
    .pack-card {
        position: relative;
        width: 100%;
        max-width: 350px;
        height: 400px;
        overflow: hidden;
        border-radius: 15px;
        box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease-in-out;
    }

    .group:hover {
        transform: scale(1.05);
    }

    /* Images événements */
    .pack-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* =============================== */
    /* 7. Overlay (Texte sur image)    */
    /* =============================== */
    .overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.4s ease-in-out;
    }

    .group:hover .overlay {
        opacity: 1;
    }

    .overlay h3 {
        color: white;
        font-size: 1.5rem;
        text-align: center;
    }

    .overlay p {
        color: white;
        font-size: 1rem;
        text-align: center;
        max-width: 80%;
    }

    /* =============================== */
    /* 8. Boutons et interactions      */
    /* =============================== */
    .overlay .btn {
        background: #8B5A2B;
        color: white;
        padding: 10px 15px;
        border-radius: 6px;
        text-decoration: none;
        transition: transform 0.2s ease-in-out;
    }

    .overlay .btn:hover {
        background: #5a3d1c;
        transform: scale(1.1);
    }

    /* =============================== */
    /* 9. Responsive Design            */
    /* =============================== */
    @media (max-width: 1024px) {
        .swiper-slide .overlay-content h1 {
            font-size: 2rem;
        }

        .swiper-slide .overlay-content p {
            font-size: 1.1rem;
        }
    }

    @media (max-width: 768px) {

        /* Ajustements globaux */
        .swiper-container {
            height: 60vh;
        }

        .grid_events {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .pack-card {
            max-width: 90%;
        }

        .overlay h3 {
            font-size: 1.2rem;
        }

        .overlay p {
            font-size: 0.9rem;
        }

        .overlay .btn {
            padding: 8px 12px;
            font-size: 0.9rem;
        }

        /* Boutons Swiper plus petits */
        .swiper-button-next::after,
        .swiper-button-prev::after {
            font-size: 30px !important;
        }
    }

    @media (max-width: 480px) {
        .swiper-slide .overlay-content h1 {
            font-size: 1.5rem;
        }

        .swiper-slide .overlay-content p {
            font-size: 1rem;
        }

        .grid_events {
            gap: 15px;
        }
    }
</style>

<!-- CARROUSEL SWIPER MAGASIN -->
<div class="swiper-container">
    <div class="swiper-wrapper">
        <!-- Slide 1 - Façade du magasin -->
        <div class="swiper-slide">
            <div class="relative w-full h-screen">
                <img src="<?= BASE_URL ?>assets/images/facadeMagasin.jpg" class="w-full h-full object-cover" alt="Facade Magasin">
                <div class="overlay-content">
                    <h1>Bienvenue chez Chic & Chill</h1>
                    <p>Découvrez notre boutique et nos collections uniques.</p>
                </div>
            </div>
        </div>

        <!-- Slide 2 - Rez-de-chaussée du magasin -->
        <div class="swiper-slide">
            <div class="relative w-full h-screen">
                <img src="<?= BASE_URL ?>assets/images/RDCMagasin.jpg" class="w-full h-full object-cover" alt="RDC Magasin">
                <div class="overlay-content">
                    <h1>Un espace chaleureux</h1>
                    <p>Mode chic et abordable dans un cadre unique.</p>
                </div>
            </div>
        </div>

        <!-- Slide 3 - Showroom du magasin -->
        <div class="swiper-slide">
            <div class="relative w-full h-screen">
                <img src="<?= BASE_URL ?>assets/images/showrromMagasin.jpg" class="w-full h-full object-cover" alt="Showroom Magasin">
                <div class="overlay-content">
                    <h1>Notre showroom exclusif</h1>
                    <p>Essayez nos pièces uniques dans un cadre élégant.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination et Navigation -->
    <div class="swiper-pagination"></div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</div>

<!-- SwiperJS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

<script>
    var swiper = new Swiper('.swiper-container', {
        loop: true,
        autoplay: {
            delay: 4000,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        effect: 'fade',
        speed: 1200,
    });
</script>

<!-- GRILLE DES ÉVÉNEMENTS -->
<div class="container mx-auto px-4 py-12">
    <h2 class="text-4xl font-bold text-center mb-8 p-12 bg-black text-white">📅 Nos Événements</h2>
    <h2 class="text-4xl font-bold text-center text-gray-800 mb-12 uppercase tracking-wide">Nos Événements Passés</h2>

    <div class="grid_events">
        <?php foreach ($events as $event) : ?>
            <div class="group pack-card">
                <!-- Image de l'événement -->
                <?php if (!empty($event['image'])) : ?>
                    <img src="<?= BASE_URL ?>/<?= htmlspecialchars($event['image']) ?>" alt="Image de l'événement">
                <?php else : ?>
                    <img src="<?= BASE_URL ?>/assets/images/events/placeholder.jpg" alt="Image par défaut">
                <?php endif; ?>

                <!-- Effet au survol -->
                <div class="overlay">
                    <h3><?= htmlspecialchars($event['title']) ?></h3>
                    <p><?= htmlspecialchars($event['description']) ?></p>
                    <a href="evenement_detail?id=<?= $event['id'] ?>" class="btn">En savoir plus</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ENCART ÉVÉNEMENTS À VENIR -->
<div class="container mx-auto px-4 py-12">
    <h2 class="text-4xl font-bold text-center text-gray-800 mb-12 uppercase tracking-wide">Nos Événements à venir</h2>

    <?php if (!empty($upcomingEvents)) : ?>
        <div class="grid_events mb-12">
            <?php foreach ($upcomingEvents as $event) : ?>
                <div class="group pack-card">
                    <!-- Image de l'événement -->
                    <?php if (!empty($event['image'])) : ?>
                        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($event['image']) ?>" alt="Image de l'événement">
                    <?php else : ?>
                        <img src="<?= BASE_URL ?>/assets/images/events/placeholder.jpg" alt="Image par défaut">
                    <?php endif; ?>

                    <!-- Effet au survol -->
                    <div class="overlay">
                        <h3><?= htmlspecialchars($event['title']) ?></h3>
                        <p><?= htmlspecialchars($event['description']) ?></p>
                        <p class="text-white font-semibold mt-2">📅 <?= date('d F Y', strtotime($event['date_event'])); ?></p>
                        <a href="evenement_detail?id=<?= $event['id'] ?>" class="btn">Voir l'événement</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="text-center bg-gray-100 p-6 rounded-lg shadow-md max-w-lg mx-auto mt-6 mb-10">
            <p class="text-lg font-semibold text-[#8B5A2B] flex items-center justify-center">
                <svg class="w-6 h-6 mr-2 text-[#8B5A2B]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4H9m4 0h1m1-5.002A2 2 0 0012 5a2 2 0 00-2 2v.002m2 0h2m2 5v4h-1m-4 4h.01" />
                </svg>
                Aucun événement n'est prévu pour le moment, mais restez informé en vous inscrivant à notre Newsletter !
            </p>
        </div>
    <?php endif; ?>

    <!-- Formulaire Newsletter (Toujours affiché) -->
    <div class="container mx-auto px-4 py-8 text-center bg-gray-100 rounded-lg shadow-md max-w-2xl">
        <h3 class="text-2xl font-semibold text-gray-800">📩 Ne manquez aucun événement !</h3>
        <p class="text-gray-600 mt-3">Recevez toutes les infos sur nos prochains événements en vous inscrivant à notre newsletter.</p>
        <?php if (isset($_GET['success']) && $_GET['success'] == 1) : ?>
            <p class="text-green-600 text-center font-semibold bg-green-100 p-3 rounded-md">
                ✅ Vous êtes bien inscrit à la newsletter !
            </p>
        <?php endif; ?>

        <form action="newsletter" method="post" class="mt-4">
            <input type="email" name="email" placeholder="Votre e-mail" class="px-4 py-2 border border-gray-300 rounded-md w-64 focus:outline-none focus:ring-2 focus:ring-[#8B5A2B]">
            <button type="submit" class="bg-[#8B5A2B] text-white px-6 py-2 rounded-md transition duration-300 hover:scale-105 hover:shadow-lg">
                S'inscrire
            </button>
        </form>
    </div>
</div>

<!-- BOUTON RÉSERVATION -->
<div class="text-center mt-12">
    <a href="<?= BASE_URL ?>reservation_evenement"
        class="inline-block border-2 border-[#8B5A2B] text-[#8B5A2B] text-lg font-semibold px-8 py-4 rounded-md transition duration-300 hover:scale-105 hover:shadow-lg">
        📅 Réserver un événement
    </a>
</div>

<!-- IDÉES DE TENUES -->
<div class="container mx-auto px-4 py-12 mt-12">
    <h2 class="text-4xl font-bold text-center mb-8 p-12 bg-black text-white">💡 Idées de tenues</h2>

    <div class="swiper-container-tenues max-w-5xl mx-auto relative px-4 py-12">
        <div class="swiper-wrapper">
            <?php if (!empty($suggestedOutfits)) : ?>
                <?php foreach ($suggestedOutfits as $tenue) : ?>
                    <div class="swiper-slide bg-white shadow-lg rounded-lg p-6 flex flex-col items-center justify-center text-center transition duration-300 transform hover:scale-105 hover:shadow-2xl">

                        <!-- Lien cliquable sur l'image -->
                        <a href="<?= BASE_URL ?>achat/produit?id=<?= htmlspecialchars($tenue['id']); ?>" class="group">
                            <?php if (!empty($tenue['image'])) : ?>
                                <img src="<?= BASE_URL ?>assets/images/products/<?= htmlspecialchars($tenue['image']); ?>"
                                    alt="<?= htmlspecialchars($tenue['outfit_name']); ?>"
                                    class="w-44 h-44 object-cover rounded-md mb-4 transition duration-300 transform group-hover:scale-110">
                            <?php else : ?>
                                <img src="<?= BASE_URL ?>/assets/images/events/placeholder.jpg" alt="Image par défaut"
                                    class="w-44 h-44 object-cover rounded-md mb-4 opacity-70">
                            <?php endif; ?>
                        </a>

                        <!-- Titre & Description -->
                        <h4 class="font-semibold text-xl"><?= htmlspecialchars($tenue['outfit_name']); ?></h4>
                        <p class="text-gray-600 text-sm mt-2 max-w-md"><?= htmlspecialchars($tenue['accessories']); ?></p>

                        <!-- Bouton Acheter -->
                        <a href="<?= BASE_URL ?>achat/produit?id=<?= htmlspecialchars($tenue['id']); ?>"
                            class="mt-4 bg-[#8B5A2B] text-white px-5 py-3 rounded-md transition duration-300 hover:scale-105 hover:shadow-lg flex items-center gap-2">
                            🛒 Acheter l'article
                        </a>

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
            stretch: 60,
            depth: 200,
            modifier: 2.5,
            slideShadows: false,
        },
        autoplay: {
            delay: 5000,
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
                slidesPerView: 1.2
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

                    <!-- Titre qui remonte encore plus haut -->
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-lg md:text-xl font-semibold text-center transition-all duration-500 group-hover:-translate-y-18">
                        <?= htmlspecialchars($pack['title']); ?>
                    </div>

                    <!-- Contenu caché qui apparaît progressivement -->
                    <div class="absolute bottom-4 w-full px-6 text-center opacity-0 transition-all duration-500 group-hover:opacity-100 group-hover:bottom-12">
                        <p class="text-sm pb-2"><?= htmlspecialchars($pack['description']); ?></p>
                        <a href="<?= BASE_URL ?>pack_detail/<?= htmlspecialchars($pack['id']); ?>" class="mt-4 bg-[#8B5A2B] text-white px-4 py-2 rounded-md transition duration-300 hover:scale-105 hover:shadow-lg">
                            Découvrir</a>
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
                Nous proposons des vêtements chics et chills à la vente et à la location, pour que chacun puisse se sentir unique, sans compromis sur le prix ni sur l’éthique.
            </p>
        </div>

        <!-- Bloc "Notre Vision" -->
        <div class="text-center">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Notre Vision</h3>
            <p class="text-gray-600">
                Nous croyons en une mode **plus responsable et inclusive**.
                Chic & Chill valorise la **seconde main et l’éco-responsabilité** en mettant en avant des pièces soigneusement sélectionnées, pour offrir une mode pour tous et engagée.
            </p>
        </div>

        <!-- Bloc "Notre Engagement" -->
        <div class="text-center">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Notre Engagement</h3>
            <p class="text-gray-600">
                Chaque article est pensé pour **s’adapter à tous les budgets** et occasions.
                Que ce soit pour une location événementielle ou un achat coup de cœur, **notre mission est de rendre la mode accessible à tous**.
            </p>
        </div>
    </div>
</div>


<?php include('src/app/Views/includes/footerEvents.php'); ?>