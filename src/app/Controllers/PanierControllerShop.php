<?php

namespace Controllers;

use Models\PanierModelShop;
use Models\AppelArticleModelShop;


class PanierControllerShop
{
    private $panierModel;
    private $produitModel;

    public function __construct()
    {
        $this->panierModel = new PanierModelShop();
        $this->produitModel = new AppelArticleModelShop(); // Permet d'aller chercher les infos produits
    }

    // 🔹 Ajouter un produit au panier
    public function ajouterAuPanier()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $quantite = $_POST['quantite'] ?? 1;

            if (!$id) {
                $_SESSION['error'] = "Produit introuvable.";
                header('Location: panier_shop');
                exit;
            }

            // Récupérer les infos du produit
            $produit = $this->produitModel->getProduitById($id);


            if (!$produit) {
                $_SESSION['error'] = "Le produit n'existe pas.";
                header('Location: produit_shop');
                exit;
            }

            // Vérifier si la quantité demandée est disponible
            if ($quantite > $produit['stock']) {
                $_SESSION['error'] = "Stock insuffisant.";
                header('Location: produit_detail_shop?id=' . $id);
                exit;
            }

            // Ajouter au panier via le modèle
            $ajout = $this->panierModel->ajouterProduit(
                $produit['id'],
                $produit['name'],
                $produit['description'],
                $produit['price'],
                $produit['discount_price'],
                $produit['stock'],
                $produit['category'],
                $produit['brand'],
                $produit['id_categories'],
                $produit['id_ss_categories'],
                $produit['gender'],
                $produit['gender_child'],
                $produit['code_ena'],
                $produit['size'],
                $produit['image'],
                $quantite
            );

            if ($ajout) {
                $_SESSION['message'] = "Produit ajouté au panier.";
                echo json_encode(["status" => "success", "message" => $_SESSION['message']]);
            } else {
                $_SESSION['error'] = "Impossible d'ajouter au panier.";
                echo json_encode(["status" => "error", "message" => $_SESSION['error']]);
            }
            
            exit;
            
        }
    }

    // 🔹 Modifier la quantité d’un produit
    public function modifierQuantite()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $quantite = $_POST['quantite'] ?? 1;

            if (!$id || $quantite < 1) {
                $_SESSION['error'] = "Modification invalide.";
                header('Location: panier_shop');
                exit;
            }

            $modification = $this->panierModel->modifierQuantite($id, $quantite);

            if ($modification) {
                $_SESSION['message'] = "Quantité mise à jour.";
            } else {
                $_SESSION['error'] = "Stock insuffisant ou produit introuvable.";
            }
            header('Location: panier_shop');
            exit;
        }
    }

    // 🔹 Supprimer un produit du panier
public function supprimerProduit()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = "Produit introuvable.";
        } else {
            if (isset($_SESSION['panier'][$id])) {
                unset($_SESSION['panier'][$id]);
                $_SESSION['message'] = "Produit supprimé du panier.";
            } else {
                $_SESSION['error'] = "Le produit n'existe pas dans le panier.";
            }
        }
    }
    // ✅ Redirection vers la page panier après suppression
    header('Location: panier_shop');
    exit;
}




    // 🔹 Vider le panier complètement
    public function viderPanier()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->panierModel->viderPanier();
            $_SESSION['message'] = "Panier vidé avec succès.";
            header('Location: panier_shop');
            exit;
        }
    }

    // 🔹 Afficher le panier
    public function afficherPanier()
    {
        $panier = $this->panierModel->getPanier();
        $total = $this->panierModel->calculerTotal();

        include 'src/app/Views/Public/panier_shop.php'; // Charge la vue du panier
    }
}
