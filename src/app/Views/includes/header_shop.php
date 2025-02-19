<?php 
namespace Models;
require_once 'src/app/Models/AppelArticleModelShop.php';

$model = new AppelArticleModelShop();

$gender = $_GET['gender'] ?? 'femmes'; // Par défaut, affiche les femmes
$id_categories = $_GET['id_categories'] ?? null;
$gender_child = $_GET['gender_child'] ?? null;

$products = $model->getProductsFiltered($gender, $id_categories, $gender_child);
?>

<header class="header_shop">
  <div class="logo-chic-and-chill-shop">
    <img src="assets/images/logo_magasin-chic-mel.png" alt="logo-magasin-chic-and-chill">
    <span class="chic-and-chill">CHIC <span class="span_and_logo_barre_nav_shop">AND</span> CHILL</span>
  </div>
  
  <nav>
        <a href="accueil_shop">Accueil</a>
    <div class="dropdown">
      <a href="produit_shop?gender=femmes">Femmes</a>
      <div class="dropdown-content">
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
    <div class="dropdown">
      <a href="#">Enfants</a>
      <div class="dropdown-content">
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
    <div class="dropdown">
    <a href="#">Marques</a>
    <div class="dropdown-content">
        <a href="#">Tout voir</a>
        <a href="#">Zara</a>
        <a href="#">Petit bateau</a>
        <a href="#">Prada</a>
        <a href="#">Tommy Hilfiger</a>
      </div>
     </div>
    <a href="#">Nouveautés</a>
    <a href="#">Promotions</a>
    <a href="#">Evenements</a>
    <a href="#">Location</a>
  
  </nav>
  <div class="right-section">
    
    <div class="icons_shop_nav_bar">
  <a href="#"><i class="fas fa-search"></i></a> <!-- Loupe -->
  <a href="#"><i class="fas fa-shopping-cart"></i></a> <!-- Panier -->
  
    <!-- btn profil user -->
    <?php if (isset($_SESSION['membre_id'])): ?>
                        <li class="nav-item px-lg-4"><a class="nav-link text-uppercase" href="src/app/Views/Public/profil_user_shop.php">Mon profil</a></li>
                        <li class="nav-item px-lg-4"><a class="nav-link text-uppercase" href="src/app/Controllers/deconnexion_shop.php">Déconnexion</a></li>
                    <?php else: ?>
                      <a href="connexion_shop"><i class="fas fa-user"></i></a> <!-- Profil -->
<?php endif; ?> 
</div>
</div>
</header>