<?php

namespace Controllers\Events;

use Models\Events\OutfitsModel;
use Models\AppelArticleModelShop;

class OutfitsController
{
    private $outfitsModel;

    // Constructeur : Initialise le modèle pour gérer les tenues
    public function __construct()
    {
        $this->outfitsModel = new OutfitsModel(); // Instancie le modèle OutfitsModel
    }

    // Gère l'affichage de la liste des tenues pour l'administration
    public function manageOutfits()
    {
        try {
            // Étape 1 : Récupère toutes les tenues pour l'administrateur
            $outfits = $this->outfitsModel->getAllOutfitsAdmin();

            // Étape 2 : Initialise le modèle des produits et récupère la liste des produits
            $productsModel = new AppelArticleModelShop();
            $products = $productsModel->getProducts();

            // Étape 3 : Trie les produits par nom dans l'ordre alphabétique
            usort($products, function ($a, $b) {
                return strcmp($a['name'], $b['name']); // Compare les noms pour le tri
            });

            // Étape 4 : Récupère le paramètre 'success' de l'URL (optionnel) et inclut la vue
            $success = $_GET['success'] ?? null; // Indique si une action précédente a réussi
            include('src/app/Views/Admin/events/admin_outfits.php'); // Affiche la vue avec les données
        } catch (\Exception $e) {
            // En cas d'erreur, affiche un message avec l'exception
            echo "Exception : " . $e->getMessage();
            // Option : var_dump($e); pourrait être utilisé pour un débogage plus détaillé
        }
    }

    // Ajoute une nouvelle tenue via une requête POST
    public function addOutfit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupère les données du formulaire, avec des valeurs par défaut si absentes
            $product_id = $_POST['product_id'] ?? null; // ID du produit associé
            $accessories = !empty($_POST['accessories']) ? htmlspecialchars($_POST['accessories']) : null; // Accessoires (optionnels)
            $status = $_POST['status'] ?? 'inactive'; // Statut par défaut : inactive

            // Vérifie si l'ID du produit est fourni, sinon redirige avec une erreur
            if (!$product_id) {
                header("Location: ../outfits?success=0&action=add&error=missing_data");
                exit();
            }

            // Ajoute la tenue dans la base de données via le modèle
            $success = $this->outfitsModel->addOutfit($product_id, $accessories, $status);

            // Redirige avec un message de succès ou d'échec
            if ($success) {
                header("Location: ../outfits?success=1&action=add");
                exit();
            } else {
                header("Location: ../outfits?success=0&action=add");
                exit();
            }
        }
    }

    // Met à jour une tenue existante via une requête POST
    public function updateOutfit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupère et nettoie les données du formulaire
            $product_id = $_POST['product_id']; // ID du produit associé
            $accessories = htmlspecialchars($_POST['accessories']); // Accessoires
            $status = $_POST['status']; // Statut (active/inactive)

            // Met à jour la tenue dans la base de données via le modèle
            $success = $this->outfitsModel->updateOutfit($id, $product_id, $accessories, $status);

            // Redirige avec un message de succès ou d'échec
            if ($success) {
                header("Location: ../?success=1&action=update");
                exit();
            } else {
                header("Location: ../?success=0&action=update");
                exit();
            }
        }
    }

    // Supprime une tenue spécifique en fonction de son ID
    public function deleteOutfit($id)
    {
        // Supprime la tenue via le modèle
        $success = $this->outfitsModel->deleteOutfit($id);

        // Redirige avec un message de succès ou d'échec
        if ($success) {
            header("Location: ../?success=1&action=delete");
            exit();
        } else {
            header("Location: ../?success=0&action=delete");
            exit();
        }
    }
}
?>