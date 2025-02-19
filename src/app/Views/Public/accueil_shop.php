<!DOCTYPE html>
<html lang="en" class="html_shop">
<?php include('src/app/Views/includes/head_shop.php'); ?>
<body class="body_shop_accueil" id="top_shop_accueil">
<?php include('src/app/Views/includes/header_shop.php'); ?>




  <!-- Hero Section -->
  <section class="hero">
  <div class="container_banniere_accueil_shop">
  <div class="content">
    <h1>Seconde Main Durable et Stylée</h1>
    <p>+700 pièces certifiées parfait état</p>
    <div class="buttons_shop_banniere_accueil">
      <a href="#">Femmes</a>
      <a href="#">Enfants</a>
      <a href="#">Accessoires</a>
    </div>
  </div>
</div>

  </section>
  <?php include('src/app/Views/includes/footer_shop.php'); ?>

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
