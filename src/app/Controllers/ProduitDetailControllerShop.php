<?php

namespace Controllers;

use PDOException;
use Controllers\DatabaseShop;

use Models\ProduitDetailModelShop;




class ProduitDetailControllerShop
{
    private $productModel;

    public function __construct()
    {
        $this->productModel = new ProduitDetailModelShop();
    }

    public function afficherDetailProduit()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            die("Erreur : Aucun produit sélectionné.");
        }

        
        $produit = $this->productModel->getProduitById($id);

        if (!$produit) {
            $_SESSION['error'] = "Produit introuvable.";
    header("Location: produit_shop");
    exit;
        }

        include 'src/app/Views/Public/produit_detail_shop.php';
    }
}
