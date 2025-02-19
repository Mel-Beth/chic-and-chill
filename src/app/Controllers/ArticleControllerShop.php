<?php
namespace Controllers;

use Models\AppelArticleModelShop;
require_once 'src/app/Controllers/DatabaseShop.php'; // Connexion à la BDD



class ArticleControllerShop { // ✅ Assure-toi que le nom est correct
    private $productModel;

    public function __construct() {
        $this->productModel = new AppelArticleModelShop(); // ✅ Instancier le modèle
    }

    // ✅ Vérifie que cette méthode est bien définie
    public function showProducts() {
        $gender = $_GET['gender'] ?? 'femmes'; // Par défaut, afficher les produits femmes
        $products = $this->productModel->getProductsByGender($gender);

        include 'src/app/Views/Public/produits_shop.php'; // Charger la vue
    }
}
?>