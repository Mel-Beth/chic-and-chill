<?php

namespace Controllers;

use Models\AppelArticleModelShop;

require_once 'src/app/Models/AppelArticleModelShop.php';

class ArticleControllerShop
{ 
    private $appelArticleModel;

    public function __construct()
    {
        $this->appelArticleModel = new AppelArticleModelShop();
    }

    public function showProducts()
    {
        $gender = $_GET['gender'] ?? null;
        $brand = $_GET['brand'] ?? null;
        $id_categories = isset($_GET['id_categories']) ? (int)$_GET['id_categories'] : null;

        // Récupérer les produits filtrés 
        $products = $this->appelArticleModel->getProductsFiltered($gender, $brand, $id_categories);

        // Si le nom de la catégorie est inclus dans les produits (via jointure)
        $nomCategorie = isset($products[0]['category_name']) ? $products[0]['category_name'] : 'Inconnue';

        // OU sinon, récupère le nom avec une méthode simple dans le modèle
        if ($nomCategorie === 'Inconnue' && $id_categories !== null) {
            $nomCategorie = $this->appelArticleModel->getCategoryNameById($id_categories) ?? 'Inconnue';
        }

        // Affiche la vue en lui laissant accès à $products, $nomCategorie, etc.
        require 'src/app/Views/Public/produits_shop.php';
    }
}