<?php

namespace Controllers\Events;

use Models\OutfitsModel;
use Models\AppelArticleModelShop;

class OutfitsController
{
    private $outfitsModel;

    public function __construct()
    {
        $this->outfitsModel = new OutfitsModel();
    }

    public function manageOutfits()
    {
        try {
            // 1) Récupérer les tenues
            $outfits = $this->outfitsModel->getAllOutfitsAdmin();

            // 2) Récupérer les produits (via AppelArticleModelShop)
            $productsModel = new AppelArticleModelShop();
            $products = $productsModel->getProducts();

            // 3) Inclure la vue en lui passant $outfits et $products
            $success = $_GET['success'] ?? null;
            include('src/app/Views/Admin/events/admin_outfits.php');
        } catch (\Exception $e) {
            echo "Exception : " . $e->getMessage();
            // Ou var_dump($e); pour voir la trace complète
        }
    }

    public function addOutfit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product_id = $_POST['product_id'] ?? null;
            $outfit_name = !empty($_POST['outfit_name']) ? htmlspecialchars($_POST['outfit_name']) : null;
            $accessories = !empty($_POST['accessories']) ? htmlspecialchars($_POST['accessories']) : null;
            $status = $_POST['status'] ?? 'inactive'; // Valeur par défaut si non fournie

            if (!$product_id || !$outfit_name) {
                header("Location: ../outfits?success=0&action=add&error=missing_data");
                exit();
            }

            $success = $this->outfitsModel->addOutfit($product_id, $outfit_name, $accessories, $status);

            if ($success) {
                header("Location: ../outfits?success=1&action=add");
                exit();
            } else {
                header("Location: ../outfits?success=0&action=add");
                exit();
            }
        }
    }

    public function updateOutfit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product_id = $_POST['product_id'];
            $outfit_name = htmlspecialchars($_POST['outfit_name']);
            $accessories = htmlspecialchars($_POST['accessories']);
            // $image = $_POST['image'] ?? null;
            // $products = isset($_POST['products']) ? implode(",", $_POST['products']) : '';
            $status = $_POST['status'];

            $success = $this->outfitsModel->updateOutfit($id, $product_id, $outfit_name, $accessories, $status);

            if ($success) {
                header("Location: ../?success=1&action=update");
                exit();
            } else {
                header("Location: ../?success=0&action=update");
                exit();
            }
        }
    }

    public function deleteOutfit($id)
    {
        $success = $this->outfitsModel->deleteOutfit($id);

        if ($success) {
            header("Location: ../?success=1&action=delete");
            exit();
        } else {
            header("Location: ../?success=0&action=delete");
            exit();
        }
    }
}
