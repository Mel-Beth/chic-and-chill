<?php include("src/app/Views/includes/head.php"); ?>
<?php include("src/app/Views/includes/header.php"); ?>

<!-- Conteneur principal pour l'erreur 404 -->
<div id="error-container" class="relative w-full h-screen flex flex-col items-center justify-center overflow-hidden">

    <!-- Image de fond (identique à celle de l'accueil) -->
    <img src="assets/images/image_accueil.png" alt="Chic And Chill" class="absolute top-0 left-0 w-full h-full object-cover z-0">

    <!-- Cercle central indiquant l'erreur -->
    <div id="circle-error" class="absolute flex flex-col justify-center items-center z-10 rounded-full border-[3px] border-[#8B5A2B] bg-white/20 backdrop-blur-md shadow-lg">
        <span class="text-[#8B5A2B] font-bold tracking-wide text-4xl md:text-6xl" style="font-family: 'Cormorant Garamond', serif;">
            Erreur 404
        </span>
        <p class="text-gray-800 font-semibold text-lg md:text-xl text-center mt-2 px-4">
            Oups... La page que vous cherchez n'existe pas.
        </p>
    </div>

    <!-- Bouton retour à l'accueil, repositionné plus haut -->
    <a href="accueil" 
       class="absolute px-6 py-3 text-lg font-semibold text-[#8B5A2B] border-2 border-[#8B5A2B] rounded-full 
              bg-white/20 backdrop-blur-md transition duration-300 hover:bg-[#8B5A2B] hover:text-white hover:shadow-lg"
       id="return-button"
       style="font-family: 'Cormorant Garamond', serif;">
        Retour à l'accueil
    </a>

</div>

<?php include("src/app/Views/includes/footer.php"); ?>

<style>
    /* Style du cercle d'erreur */
    #circle-error {
        width: 28vw;
        height: 28vw;
        max-width: 280px;
        max-height: 280px;
        text-align: center;
    }

    /* Effet responsive */
    @media (max-width: 768px) {
        #circle-error {
            width: 40vw;
            height: 40vw;
            max-width: 240px;
            max-height: 240px;
        }
    }

    /* Ajustement du bouton pour le remonter */
    #return-button {
        bottom: 18vh; /* Au lieu de 10vh pour le remonter */
    }

    @media (max-width: 768px) {
        #return-button {
            bottom: 20vh; /* Ajustement spécifique pour les petits écrans */
        }
    }
</style>

</body>
</html>
