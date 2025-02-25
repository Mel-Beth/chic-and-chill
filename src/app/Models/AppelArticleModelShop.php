<?php

namespace Models;

use PDO;
use PDOException;
use Controllers\DatabaseShop;

require_once 'src/app/Controllers/DatabaseShop.php'; // Correction du chemin

class AppelArticleModelShop
{
    private $pdo;

    public function __construct()
    {
        
        $this->pdo = DatabaseShop::getConnection(); // Assurez-vous que cette méthode retourne bien une instance PDO
    }


// Récupérer les produits en fonction du genre
    public function getProductsByGender($gender) {
    $query = "SELECT * FROM products WHERE gender = :gender";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute(['gender' => $gender]);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);

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
    

    public function getProductsFiltered($gender, $id_categories = null, $gender_child = null)
    {
        if ($this->pdo === null) {
            throw new \Exception("La connexion à la base de données est introuvable.");
        }

        $query = "SELECT * FROM products WHERE gender = :gender";

        if (!is_null($id_categories)) {
            $query .= " AND id_categories = :id_categories";
        }

        if (!is_null($gender_child)) {
            $query .= " AND gender_child = :gender_child";
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':gender', $gender, PDO::PARAM_STR);

        if (!is_null($id_categories)) {
            $stmt->bindValue(':id_categories', $id_categories, PDO::PARAM_INT);
        }

        if (!is_null($gender_child)) {
            $stmt->bindValue(':gender_child', $gender_child, PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>