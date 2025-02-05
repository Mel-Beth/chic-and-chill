<?php include("src/app/Views/includes/head.php"); ?>
<?php include("src/app/Views/includes/header.php"); ?>

<!-- Conteneur principal avec effet de fond -->
<div class="relative w-full h-screen flex flex-col items-center justify-center bg-cover bg-center" 
     style="background-image: url('assets/images/image_accueil.png');">

    <!-- Overlay sombre avec flou -->
    <div class="absolute inset-0 bg-black/40 backdrop-blur-md"></div>

    <!-- Contenu de l'erreur 404 -->
    <div class="relative z-10 text-center text-white p-10 rounded-lg bg-white/20 backdrop-blur-lg shadow-lg">
        <h1 class="text-6xl font-bold text-[#8B5A2B] drop-shadow-lg">Erreur 404</h1>
        <p class="mt-4 text-lg text-gray-200">Oups... La page que vous cherchez n'existe pas.</p>

        <!-- Lien retour vers l'accueil -->
        <a href="accueil" 
           class="mt-6 inline-block px-6 py-3 text-lg font-semibold text-[#8B5A2B] border-2 border-[#8B5A2B] rounded-full 
                  bg-white/20 backdrop-blur-md transition duration-300 hover:bg-[#8B5A2B] hover:text-white hover:shadow-lg">
            Retour à l'accueil
        </a>
    </div>

</div>

<?php include("src/app/Views/includes/footer.php"); ?>
