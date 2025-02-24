<?php

namespace Controllers;

use Models\AppelArticleModelShop;

class ArticleControllerShop
{

    private $productModel;

    public function __construct()
    {
        $this->productModel = new AppelArticleModelShop(); // ✅ Instancier le modèle
    }

    // ✅ Vérifie que cette méthode est bien définie
    public function showProducts()
    {
        $appelArticleModelShop = new AppelArticleModelShop();
        $gender = $_GET['gender'] ?? 'femmes'; // Par défaut, afficher les produits femmes
        $products = $this->productModel->getProductsByGender($gender);

        include 'src/app/Views/Public/produits_shop.php'; // Charger la vue
    }

    public function displayProducts()
    {
        $products = $this->productModel->getProducts();
        require 'src/app/Views/admin_products.php';
    }
}
