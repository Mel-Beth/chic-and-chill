<?php
namespace Controllers;

use Models\AppelArticleModelShop;

class ArticleControllerShop { 

    private $appelArticleModel;

    public function __construct()
    {
        $this->appelArticleModel = new AppelArticleModelShop();
    }

    // ✅ Vérifie que cette méthode est bien définie
    public function showProducts() {
        $gender = $_GET['gender'] ?? 'femmes'; // Par défaut, afficher les produits femmes
        $id_categories = $_GET['id_categories'] ?? null;
        $id_ss_categories = $_GET['id_ss_categories'] ?? null;
    
        // Récupérer les produits filtrés
        $products = $this->appelArticleModel->getProductsFiltered($gender, $id_categories, $id_ss_categories);
    
        include 'src/app/Views/Public/produits_shop.php'; // Charger la vue
    }
}
?>