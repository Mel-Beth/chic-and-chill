<?php
namespace Controllers;

use Models\AppelArticleModelShop;

class ArticleControllerShop { 

    private $appelArticleModel;

    public function __construct()
    {
        $this->appelArticleModel = new AppelArticleModelShop();
    }

    // Vérifie que cette méthode est bien définie
    public function showProducts() {
        $gender = $_GET['gender'] ?? null;
        $brand = $_GET['brand'] ?? null;
        $id_categories = isset($_GET['id_categories']) ? (int)$_GET['id_categories'] : null;

        //Vérification des paramètres récupérés
        var_dump($gender, $brand, $id_categories);

        

        //Récupérer les produits filtrés 
        $products = $this->appelArticleModel->getProductsFiltered($gender, $brand, $id_categories);

        include 'src/app/Views/Public/produits_shop.php'; // Charger la vue
    }
}
?>
