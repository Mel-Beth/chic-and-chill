<?php

namespace Models;

use PDO;
use PDOException;
use Controllers\DatabaseShop;

require_once 'src/app/Controllers/DatabaseShop.php'; // Correction du chemin

class PanierModelShop
{
    private $pdo;
    
    public function __construct()
    {
       
        $this->pdo = DatabaseShop::getConnection(); 
        if (!isset($_SESSION['panier'])) {
            $_SESSION['panier'] = []; // Initialise un panier vide
        }
    }
    public function getProduitById($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new \Exception("Erreur lors de la récupération du produit : " . $e->getMessage());
        }
    }

    // 🔹 Ajouter un produit au panier
    public function ajouterProduit($id, $name, $description, $price, $discount_price, $stock, $category, $brand, $id_categories, $id_ss_categories, $gender, $gender_child, $code_ena, $size, $image, $quantite)
    {
        // Vérifier si le produit est déjà dans le panier
        if (isset($_SESSION['panier'][$id])) {
            // Vérifier si on dépasse le stock
            if ($_SESSION['panier'][$id]['quantite'] + $quantite > $stock) {
                return false; // Évite de dépasser le stock disponible
            }
            $_SESSION['panier'][$id]['quantite'] += $quantite;
        } else {
            // Vérifier si la quantité demandée est disponible
            if ($quantite > $stock) {
                return false;
            }

            $_SESSION['panier'][$id] = [
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'discount_price' => $discount_price,
                'stock' => $stock,
                'category' => $category,
                'brand' => $brand,
                'id_categories' => $id_categories,
                'id_ss_categories' => $id_ss_categories,
                'gender' => $gender,
                'gender_child' => $gender_child,
                'code_ena' => $code_ena,
                'size' => $size,
                'image' => $image,
                'quantite' => $quantite
            ];
        }
        return true;
    }

    // 🔹 Récupérer les produits du panier
    public function getPanier()
    {
        return $_SESSION['panier'];
    }

    // 🔹 Modifier la quantité d’un produit
    public function modifierQuantite($id, $quantite)
    {
        if (isset($_SESSION['panier'][$id])) {
            // Vérifier si on dépasse le stock
            if ($quantite > $_SESSION['panier'][$id]['stock']) {
                return false;
            }
            if ($quantite > 0) {
                $_SESSION['panier'][$id]['quantite'] = $quantite;
            } else {
                unset($_SESSION['panier'][$id]); // Supprime si quantité <= 0
            }
            return true;
        }
        return false;
    }

    // 🔹 Supprimer un produit du panier
    public function supprimerProduit($id)
    {
        if (isset($_SESSION['panier'][$id])) {
            unset($_SESSION['panier'][$id]);
        }
    }

    // 🔹 Vider complètement le panier
    public function viderPanier()
    {
        $_SESSION['panier'] = [];
    }

    // 🔹 Calculer le total du panier
    public function calculerTotal()
    {
        $total = 0;
        foreach ($_SESSION['panier'] as $produit) {
            $prixFinal = ($produit['discount_price'] > 0) ? $produit['discount_price'] : $produit['price'];
            $total += $prixFinal * $produit['quantite'];
        }
        return $total;
    }
}
