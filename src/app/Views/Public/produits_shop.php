<!DOCTYPE html>
<html lang="en" class="html_shop">
<?php include("./../includes/head_shop.php"); ?>
<body class="body_shop_produits" id="top_shop_accueil">
<?php include('./../includes/header_shop.php'); ?>
    
<main class="product-grid" >

  <!-- Produit 1 -->
  <div class="product-card">
    <img src="assets/images/haut_printemps.jpg" alt="Produit 1"> 
    <div class="product-info">
      <h3>Top blanc corset</h3>
      <p>Petit top blanc, style corset avec fermeture dorée.</p>
      <span class="product-price">15,00 €</span>
    </div>
  </div>

  <!-- Produit 2 -->
  <div class="product-card">
    <img src="assets/images/pull_pastel.jpg" alt="Produit 2">
    <div class="product-info">
      <h3>Pull pastel </h3>
      <p>Pull en laine, couleur vert d'eau pastel, manche longue.</p>
      <span class="product-price">20,00 €</span>
    </div>
  </div>

    <!-- Produit 3 -->
    <div class="product-card">
    <img src="assets/images/robe_soiree.jpg" alt="Produit 2">
    <div class="product-info">
      <h3>Robe de soirée</h3>
      <p>Robe de soirée à sequins, manches longues et décoletté échancré</p>
      <span class="product-price">30,00 €</span>
    </div>
  </div>

    <!-- Produit 4 -->
    <div class="product-card">
    <img src="assets/images/pantacourt_jean.jpg" alt="Produit 2">
    <div class="product-info">
      <h3>Pantacourt</h3>
      <p>Pantacourt en jean et tissu. </p>
      <span class="product-price">10,00 €</span>
    </div>
  </div>

  
    <!-- Produit 5 -->
    <div class="product-card">
    <img src="assets/images/manteau_fourrure.jpg" alt="Produit 2">
    <div class="product-info">
      <h3>Manteau blanc</h3>
      <p>Manteau blanc, fausse fourrure, idéal pour passer l'hiver avec style</p>
      <span class="product-price">40,00 €</span>
    </div>
  </div>

  
    <!-- Produit 6 -->
    <div class="product-card">
    <img src="assets/images/veste_croptop.jpg" alt="Produit 2">
    <div class="product-info">
      <h3>Veste Crop Top</h3>
      <p>Veste courte, à pailettes, idéale pour vos soirées.</p>
      <span class="product-price">20,00 €</span>
    </div>
  </div>

  <!-- Ajoutez d'autres produits de la même manière -->
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
