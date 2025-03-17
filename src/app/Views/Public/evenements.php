<?php include('src/app/Views/includes/headEvents.php'); ?>
<?php include('src/app/Views/includes/headerEvents.php'); ?>

<!-- CARROUSEL SWIPER MAGASIN -->
<div class="swiper-container">
    <div class="swiper-wrapper">
        <div class="swiper-slide">
            <div class="relative w-full h-screen">
                <!-- Première image sans lazy pour LCP -->
                <img src="assets/images/facadeMagasin.webp" class="w-full h-full object-cover" alt="Facade Magasin" width="1920" height="1080">
                <div class="overlay-content">
                    <h1>Bienvenue chez Chic & Chill</h1>
                    <p>Découvrez notre boutique et nos collections uniques.</p>
                </div>
            </div>
        </div>
        <div class="swiper-slide">
            <div class="relative w-full h-screen">
                <img src="assets/images/RDCMagasin.webp" loading="lazy" class="w-full h-full object-cover" alt="RDC Magasin" width="1920" height="1080">
                <div class="overlay-content">
                    <h1>Un espace chaleureux</h1>
                    <p>Mode chic et abordable dans un cadre unique.</p>
                </div>
            </div>
        </div>
        <div class="swiper-slide">
            <div class="relative w-full h-screen">
                <img src="assets/images/showrromMagasin.webp" loading="lazy" class="w-full h-full object-cover" alt="Showroom Magasin" width="1920" height="1080">
                <div class="overlay-content">
                    <h1>Notre showroom exclusif</h1>
                    <p>Essayez nos pièces uniques dans un cadre élégant.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="swiper-pagination"></div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</div>

<!-- GRILLE DES ÉVÉNEMENTS -->
<div class="container mx-auto px-4 py-12">
    <h2 class="text-4xl font-bold text-center mb-8 p-12 bg-black text-white">
        <span class="material-symbols-rounded text-white text-5xl">event</span> Nos Événements
    </h2>
    <h3 class="text-4xl font-bold text-center text-gray-800 mb-12 uppercase tracking-wide">
        <span class="ph ph-calendar-check"></span> Nos Événements Passés
    </h3>

    <div class="grid_events">
        <?php foreach ($events as $event) : ?>
            <div class="group pack-card">
                <!-- Image de l'événement -->
                <?php if (!empty($event['image'])) : ?>
                    <img src="<?= htmlspecialchars($event['image']) ?>" alt="Image de l'événement">
                <?php else : ?>
                    <img src="assets/images/placeholder.webp" alt="Image par défaut">
                <?php endif; ?>

                <!-- Effet au survol -->
                <div class="overlay">
                    <h4 class="text-2xl text-center text-white"><?= htmlspecialchars($event['title']) ?></h4>
                    <p class="mb-4"><?= htmlspecialchars($event['description']) ?></p>
                    <a href="evenement_detail?id=<?= $event['id'] ?>" class="btn">En savoir plus</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ENCART ÉVÉNEMENTS À VENIR -->
<div class="container mx-auto px-4 py-12">
    <h3 class="text-4xl font-bold text-center text-gray-800 mb-12 uppercase tracking-wide">
        <span class="ph ph-calendar-star"></span> Nos Événements à venir
    </h3>
    <?php if (!empty($upcomingEvents)) : ?>
        <div class="grid_events mb-12">
            <?php foreach ($upcomingEvents as $event) : ?>
                <div class="group pack-card">
                    <?php if (!empty($event['image'])) : ?>
                        <img src="<?= htmlspecialchars($event['image']) ?>" loading="lazy" alt="Événement <?= htmlspecialchars($event['title']) ?>" width="350" height="400">
                    <?php else : ?>
                        <img src="assets/images/placeholder.webp" loading="lazy" alt="Image par défaut" width="350" height="400">
                    <?php endif; ?>
                    <div class="overlay">
                        <h4><?= htmlspecialchars($event['title']) ?></h4>
                        <p><?= htmlspecialchars($event['description']) ?></p>
                        <p class="text-white font-semibold mt-2">📅 <?= date('d F Y', strtotime($event['date_event'])); ?></p>
                        <a href="evenement_detail?id=<?= $event['id'] ?>" class="btn" aria-label="Voir l'événement <?= htmlspecialchars($event['title']) ?>">Voir l'événement</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="text-center bg-gray-100 p-6 rounded-lg shadow-md max-w-lg mx-auto mt-6 mb-10">
            <p class="text-lg font-semibold text-[#8B5A2B] flex items-center justify-center">
                <span class="ph ph-calendar-x text-[#8B5A2B] text-2xl mr-2"></span>
                Aucun événement n'est prévu pour le moment, mais restez informé en vous inscrivant à notre Newsletter !
            </p>
        </div>
    <?php endif; ?>
    <div class="container mx-auto px-4 py-8 text-center bg-gray-100 rounded-lg shadow-md max-w-2xl">
        <h3 class="text-2xl font-semibold text-gray-800 flex items-center justify-center">
            <span class="material-symbols-rounded text-3xl text-gray-800 mr-2">mail</span>
            Ne manquez aucun événement !
        </h3>
        <p class="text-gray-600 mt-3">Recevez toutes les infos sur nos prochains événements en vous inscrivant à notre newsletter.</p>
        <?php if (isset($_GET['success']) && $_GET['success'] == 1) : ?>
            <p class="text-green-600 text-center font-semibold bg-green-100 p-3 rounded-md">
                ✅ Vous êtes bien inscrit à la newsletter !
            </p>
        <?php endif; ?>
        <form action="newsletter" method="post" class="mt-4">
            <input type="email" name="email" placeholder="Votre e-mail" class="px-4 py-2 border border-gray-300 rounded-md w-64 focus:outline-none focus:ring-2 focus:ring-[#8B5A2B]" required>
            <button type="submit" class="bg-[#8B5A2B] text-white px-6 py-2 rounded-md transition duration-300 hover:scale-105 hover:shadow-lg">S'inscrire</button>
        </form>
    </div>
</div>

<!-- BOUTON RÉSERVATION -->
<div class="text-center mt-12">
    <a href="reservation_evenement" class="inline-block border-2 border-[#8B5A2B] text-[#8B5A2B] text-lg font-semibold px-8 py-4 rounded-md transition duration-300 hover:scale-105 hover:shadow-lg flex items-center gap-2" aria-label="Réserver un événement">
        <span class="ph ph-calendar-plus text-xl"></span> Réserver un événement
    </a>
</div>

<!-- IDÉES DE TENUES -->
<div class="container mx-auto px-4 py-12 mt-12">
    <h2 class="text-4xl font-bold text-center mb-8 p-12 bg-black text-white">
        <span class="material-symbols-rounded text-4xl">styler</span> Idées de tenues
    </h2>
    <div class="swiper-container-tenues max-w-5xl mx-auto relative px-4 py-12">
        <div class="swiper-wrapper">
            <?php if (!empty($suggestedOutfits)) : ?>
                <?php foreach ($suggestedOutfits as $tenue) : ?>
                    <div class="swiper-slide bg-white shadow-lg rounded-lg p-6 flex flex-col items-center justify-center text-center transition duration-300 transform hover:scale-105 hover:shadow-2xl">
                        <a href="achat/produit?id=<?= htmlspecialchars($tenue['id']); ?>" class="group">
                            <?php if (!empty($tenue['image'])) : ?>
                                <img src="assets/images/products/<?= htmlspecialchars($tenue['image']) ?>" loading="lazy" alt="<?= htmlspecialchars($tenue['outfit_name']) ?>" width="176" height="176">
                            <?php else : ?>
                                <img src="assets/images/placeholder.webp" loading="lazy" alt="Image par défaut" width="176" height="176">
                            <?php endif; ?>
                        </a>
                        <h4 class="font-semibold text-xl"><?= htmlspecialchars($tenue['outfit_name']) ?></h4>
                        <p class="text-gray-600 text-sm mt-2 max-w-md"><?= htmlspecialchars($tenue['accessories']) ?></p>
                        <a href="achat/produit?id=<?= htmlspecialchars($tenue['id']); ?>" class="mt-4 bg-[#8B5A2B] text-white px-5 py-3 rounded-md transition duration-300 hover:scale-105 hover:shadow-lg flex items-center gap-2" aria-label="Acheter <?= htmlspecialchars($tenue['outfit_name']) ?>">
                            <span class="material-symbols-rounded text-lg">shopping_cart</span> Acheter l'article
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p class="text-gray-500 text-center col-span-3">Aucune suggestion disponible pour le moment.</p>
            <?php endif; ?>
        </div>
        <div class="text-center bg-gray-100 p-6 rounded-lg shadow-md max-w-lg mx-auto mt-12 mb-8">
            <p class="text-lg font-semibold text-[#8B5A2B] flex items-center justify-center">
                <i class="fas fa-info-circle text-2xl mr-2 text-[#8B5A2B]"></i>
                Vous pouvez acheter ou louer ces articles !
            </p>
            <div class="flex justify-center gap-4 mt-6">
                <a href="accueil_shop" class="flex items-center justify-center gap-2 px-6 py-3 text-[#8B5A2B] border-2 border-[#8B5A2B] rounded-lg font-semibold transition duration-300 hover:bg-[#8B5A2B] hover:text-white" aria-label="Visiter le magasin">
                    <i class="fas fa-shopping-bag"></i> Magasin
                </a>
                <a href="location" class="flex items-center justify-center gap-2 px-6 py-3 text-[#8B5A2B] border-2 border-[#8B5A2B] rounded-lg font-semibold transition duration-300 hover:bg-[#8B5A2B] hover:text-white" aria-label="Options de location">
                    <i class="fas fa-map-marker-alt"></i> Location
                </a>
            </div>
        </div>
        <div class="swiper-button-next swiper-button-next-tenues"></div>
        <div class="swiper-button-prev swiper-button-prev-tenues"></div>
    </div>
</div>

<!-- PACKS ÉVÉNEMENTIELS -->
<div class="container mx-auto px-4 py-12">
    <h2 class="text-4xl font-bold text-center mb-8 p-12 bg-black text-white flex items-center justify-center gap-3">
        <span class="ph ph-gift text-5xl"></span> Packs événementiels
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-5xl mx-auto">
        <?php if (!empty($eventPacks)) : ?>
            <?php foreach ($eventPacks as $pack) : ?>
                <div class="relative bg-black text-white rounded-lg shadow-lg overflow-hidden group transition-all duration-500 h-64 flex flex-col items-center justify-center">
                    <div class="absolute inset-2 border-[3px] border-[#8B5A2B] transition-all duration-500 group-hover:inset-4 group-hover:border-[1px]"></div>
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-lg md:text-xl font-semibold text-center transition-all duration-500 group-hover:-translate-y-18">
                        <?= htmlspecialchars($pack['title']) ?>
                    </div>
                    <div class="absolute bottom-4 w-full px-6 text-center opacity-0 transition-all duration-500 group-hover:opacity-100 group-hover:bottom-12">
                        <p class="text-sm pb-2"><?= htmlspecialchars($pack['description']) ?></p>
                        <a href="pack_detail/<?= htmlspecialchars($pack['id']) ?>" class="mt-4 bg-[#8B5A2B] text-white px-4 py-2 rounded-md transition duration-300 hover:scale-105 hover:shadow-lg" aria-label="Découvrir le pack <?= htmlspecialchars($pack['title']) ?>">Découvrir</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p class="text-gray-500 text-center">Aucun pack disponible pour le moment.</p>
        <?php endif; ?>
    </div>
</div>

<!-- SECTION À PROPOS -->
<div class="container mx-auto px-4 py-12">
    <div class="bg-black text-white text-center py-12 flex items-center justify-center">
        <h2 class="text-3xl font-bold uppercase tracking-wide flex items-center justify-center gap-3">
            <span class="material-symbols-rounded text-4xl">spa</span> L'Expérience Chic & Chill
        </h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-12 py-12">
        <div class="text-center">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Notre Concept</h3>
            <p class="text-gray-600">Chic & Chill est plus qu’une boutique, c’est un <strong>univers où l’élégance rencontre l’accessibilité</strong>. Nous proposons des vêtements chics et chills à la vente et à la location, pour que chacun puisse se sentir unique, sans compromis sur le prix ni sur l’éthique.</p>
        </div>
        <div class="text-center">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Notre Vision</h3>
            <p class="text-gray-600">Nous croyons en une mode <strong>plus responsable et inclusive</strong>. Chic & Chill valorise la <strong>seconde main et l’éco-responsabilité</strong> en mettant en avant des pièces soigneusement sélectionnées, pour offrir une mode pour tous et engagée.</p>
        </div>
        <div class="text-center">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Notre Engagement</h3>
            <p class="text-gray-600">Chaque article est pensé pour <strong>s’adapter à tous les budgets</strong> et occasions. Que ce soit pour une location événementielle ou un achat coup de cœur, <strong>notre mission est de rendre la mode accessible à tous</strong>.</p>
        </div>
    </div>
</div>

<?php include('src/app/Views/includes/footerEvents.php'); ?>