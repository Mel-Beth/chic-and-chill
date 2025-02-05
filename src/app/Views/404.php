<?php include("src/app/Views/includes/head.php"); ?>
<?php include("src/app/Views/includes/header.php"); ?>

<!-- Conteneur principal avec effet de fond -->
<div class="relative z-10 text-center text-white p-10 rounded-lg bg-white/20 backdrop-blur-lg shadow-lg">
    <h1 class="text-6xl font-bold text-[#8B5A2B] drop-shadow-lg" style="font-family: 'Cormorant Garamond', serif;">Erreur 404</h1>
    <p class="mt-4 text-lg text-gray-200" style="font-family: 'Cormorant Garamond', serif;">Oups... La page que vous cherchez n'existe pas.</p>

    <a href="accueil"
        class="mt-6 inline-block px-6 py-3 text-lg font-semibold text-[#8B5A2B] border-2 border-[#8B5A2B] rounded-full 
              bg-white/20 backdrop-blur-md transition duration-300 hover:bg-[#8B5A2B] hover:text-white hover:shadow-lg"
        style="font-family: 'Cormorant Garamond', serif;">
        Retour à l'accueil
    </a>
</div>


</div>

<?php include("src/app/Views/includes/footer.php"); ?>