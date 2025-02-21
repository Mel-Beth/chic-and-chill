<header class="header_shop">
  <div class="logo-chic-and-chill-shop">
    <img src="assets/images/logo_magasin-chic-mel.png" alt="logo-magasin-chic-and-chill">
    <span class="chic-and-chill">CHIC <span class="span_and_logo_barre_nav_shop">AND</span> CHILL</span>
  </div>
  
  <nav>
    <a href="accueil_shop">ACCUEIL</a>
    <div class="dropdown_shop">
      <a href="produit_shop?gender=femmes">FEMMES</a>
      <div class="dropdown_content_shop">
        <a href="produit_shop?gender=femmes">Tout voir</a>
        <a href="produit_shop?gender=femmes&id_categories=1">Vestes et Manteaux</a>
        <a href="produit_shop?gender=femmes&id_categories=6">Pulls, Sweats & Gilets</a>
        <a href="produit_shop?gender=femmes&id_categories=2">Tops et T-shirts</a>
        <a href="produit_shop?gender=femmes&id_categories=4">Jupes et Robes</a>
        <a href="produit_shop?gender=femmes&id_categories=5">Accessoires</a>
        <a href="produit_shop?gender=femmes&id_categories=7">Chaussures</a>
        <a href="produit_shop?gender=femmes&id_categories=3">Pantalons et Shorts</a>
        <a href="produit_shop?gender=femmes&id_categories=9">Sportwear</a>
      </div>
    </div>
    <div class="dropdown_shop">
      <a href="#">ENFANTS</a>
      <div class="dropdown_content_shop">
        <a href="produit_shop?gender=enfants">Tout voir</a>
        <a href="produit_shop?gender=enfants&id_categories=10">Vestes et manteaux</a>
        <a href="produit_shop?gender=enfants&id_categories=10">Pantalons et Shorts</a>
        <a href="produit_shop?gender=enfants&id_categories=11">Tops et T-shirts</a>
        <a href="produit_shop?gender=enfants&id_categories=15">Pulls, Sweats et Gilets</a>
        <a href="produit_shop?gender=enfants&id_categories=13">Jupes et Robes</a>
        <a href="produit_shop?gender=enfants&id_categories=14">Accessoires</a>
        <a href="produit_shop?gender=enfants&id_categories=16">Chaussures</a>
        <a href="produit_shop?gender=enfants&id_categories=17">Sportwear</a>
      </div>
    </div>
    <div class="dropdown_shop">
      <a href="#">MARQUES</a>
      <div class="dropdown_content_shop">
        <a href="#">Tout voir</a>
        <a href="#">Zara</a>
        <a href="#">Petit bateau</a>
        <a href="#">Prada</a>
        <a href="#">Tommy Hilfiger</a>
      </div>
    </div>
    <a href="#">NOUVEAUTES</a>
    <a href="#">PROMOTIONS</a>
    <a href="#">EVENEMENTS</a>
    <a href="#">LOCATION</a>
  </nav>

  <div class="right-section">
    <?php if (isset($_SESSION['user_id'])): ?>
      <a class="nav-link" href="profil_user_shop">MON PROFIL</a>
      <?php if (!empty($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
        <a href="views/dashboard.php">Dashboard</a>
      <?php endif; ?>
    <?php else: ?>
      <a class="nav-link" href="connexion_shop">Connexion</a>
    <?php endif; ?>
    <div class="icons_shop_nav_bar">
      <a href="#"><i class="fas fa-search"></i></a>
      <a href="#"><i class="fas fa-shopping-cart"></i></a>
    </div>
  </div>
</header>
