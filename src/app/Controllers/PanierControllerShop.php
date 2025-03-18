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
        $this->produitModel = new AppelArticleModelShop(); // on va chercher les infos d produits
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

            // vérifier si la quantité demandée est dispo
            if ($quantite > $produit['stock']) {
                $_SESSION['error'] = "Stock insuffisant.";
                header('Location: produit_detail_shop?id=' . $id);
                exit;
            }

            // On ajoute au panier, panierModel c'est le model du même nom
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

    // partie ou on modifie la quantité dans le panier, mais a voir, car si un seul produit faut faire en sorte qu'on est pas ce choix
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

    // Supprimer un produit du panier
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
    // redirection vers le panier 
    header('Location: panier_shop');
    exit;
}




    // vider completement le panier
    public function viderPanier()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->panierModel->viderPanier();
            $_SESSION['message'] = "Panier vidé avec succès.";
            header('Location: panier_shop');
            exit;
        }
    }

    //afficher le panier
    public function afficherPanier()
    {
        $panier = $this->panierModel->getPanier();
        $total = $this->panierModel->calculerTotal();

        // Stocker le total du panier en session pr l'utiliser plus tard ac stripe
    $_SESSION['panier_total'] = $total;

        include 'src/app/Views/Public/panier_shop.php'; //afficher la page du panier
}
}