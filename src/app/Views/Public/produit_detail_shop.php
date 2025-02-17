<!DOCTYPE html>
<html lang="en">
<?php include("./../includes/head_shop.php"); ?>
<body class="body_detail_produit_shop"  >
<?php include('./../includes/header_shop.php'); ?>

<main class="produit_detail_container_shop">
        <!-- Section image du produit -->
        <div class="produit_image_shop">
            <img src="assets/images/robe_soiree.jpg" alt="Nom du produit">
        </div>

        <!-- Section infos produit -->
        <div class="produit_info_shop">
            <h1 class="produit_titre_shop">Robe de soirée</h1>
            <p class="produit_prix_shop">30,00 €</p>

            <!-- Choix des couleurs -->
            <div class="produit_colors_shop">
                <span>Couleurs :</span>
                <div class="color_options_produit_shop">
                    <span class="color-circle color-black"></span>
                    
                </div>
            </div>

            <!-- Description -->
            <p class="produit_description_shop">
            Robe de soirée à sequins, manches longues et décoletté échancré.
            </p>

            <!-- Bouton Ajouter au panier -->
            <button class="btn_ajout_panier_shop">Ajouter au panier</button>

            <!-- Encart location -->
            <div class="produit_location_shop">
                <span class="span_loc_detail_produit">Disponible à la location</span>
                <button class="location_btn">Location</button>
            </div>
        </div>
    </main>


<?php include('./../includes/footer_shop.php');?>
<script>
 document.addEventListener('scroll', function () {
    const header = document.querySelector('.header_shop');
    if (window.scrollY > 50) { // Quand le scroll dépasse 50px
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});

</script>
</body>
</html>