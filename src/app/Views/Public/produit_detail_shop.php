<!-- Vérifier s'il y a un message et le stocker dans une variable temporaire -->
 <?php
$message = $_SESSION['message'] ?? null;
unset($_SESSION['message']); // Supprime le message dès le chargement de la page
?>

<!DOCTYPE html>
<html lang="fr">
<?php include("src/app/Views/includes/head_shop.php"); ?>

<body class="body_detail_produit_shop" id="top_shop_accueil">
    <?php include('src/app/Views/includes/header_shop.php'); ?>

    <main class="produit_detail_container_shop">
    <?php if (isset($_SESSION['message'])): ?>
    <div class="alert success">
        <?= $_SESSION['message']; ?>
    </div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert error">
        <?= $_SESSION['error']; ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>
        <!-- Section image du produit -->
        <div class="produit_image_shop">
            <img src="<?php echo htmlspecialchars($produit['image']); ?>" alt="<?php echo htmlspecialchars($produit['name']); ?>">
        </div>

        <!-- Section infos produit -->

        <div class="produit_info_shop">
        <div id="message-panier" style="display: none; color: red; font-weight: bold;"></div>

            <h1 class="produit_titre_shop"><?php echo htmlspecialchars($produit['name']); ?></h1>
            <p class="produit_prix_shop"><?php echo number_format($produit['price'], 2, ',', ' '); ?> €</p>


            <!-- Affichage de la taille -->
            <?php if (!empty($produit['size'])): ?>
                <p class="produit_taille_shop"><strong>Taille :</strong> <?php echo htmlspecialchars($produit['size']); ?></p>
            <?php endif; ?>
            <!-- Description -->
            <h2 class="titre_description_shop">DESCRIPTION</h2>
            <p class="produit_description_shop">
                <?php echo htmlspecialchars($produit['description']); ?>
            </p>

            <!-- Bouton Ajouter au panier -->
            <!-- <form action="panier_shop" method="POST">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($produit['id']); ?>">
                <input type="hidden" name="quantite" value="1"> 
                <button type="submit" class="btn_ajout_panier_shop">Ajouter au panier</button>
            </form> --> 
            <form id="ajoutPanierForm">
    <input type="hidden" name="id" value="<?= $produit['id']; ?>">
    <input type="hidden" name="quantite" value="1">
    <button type="submit" class="btn_ajout_panier_shop">Ajouter au panier</button>
</form>

            <!-- ✅ Affichage conditionnel de la section "Disponible à la location" -->
            <?php if ($produit['is_rentable'] === 'oui') : ?>
                <div class="produit_location_shop">
                    <span class="span_loc_detail_produit">Disponible à la location</span>
                    <button class="location_btn">Location</button>
                </div>
            <?php endif; ?>
        </div>

    </main>


    <?php include('src/app/Views/includes/footer_shop.php'); ?>

    <script>
        document.addEventListener('scroll', function() {
            const header = document.querySelector('.header_shop');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    </script>


<script>
document.getElementById("ajoutPanierForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Empêche le rechargement de la page

    let formData = new FormData(this);

    fetch("panier_shop", { // Change l'URL si nécessaire
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            document.getElementById("message-panier").innerText = data.message;
            document.getElementById("message-panier").style.display = "block";
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error("Erreur :", error));
});
</script>

</body>

</html>