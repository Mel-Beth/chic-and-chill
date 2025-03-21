<?php

namespace Models;

class AppelArticleModelShop extends ModeleParent
{
// Récupérer les produits en fonction du genre
    public function getProductsByGender($gender) {
    $query = "SELECT * FROM products WHERE gender = :gender";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute(['gender' => $gender]);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }


    public function getProductsByBrand($brand) {
    $query = "SELECT * FROM products WHERE brand = :brand";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute(['brand' => $brand]);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function getProduitById($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors de la récupération du produit : " . $e->getMessage());
        }
    }

    public function getProductsFiltered($gender, $brand, $id_categories = null)
    //attention, l'ordre des parametre doit etre le meme qd on appel la function ailleurs, sinon ça fonctionne pas
{
    if ($this->pdo === null) {
        throw new \Exception("La connexion à la base de données est introuvable.");
    }

    $query = "SELECT * FROM products WHERE 1=1";

    if (!is_null($gender)) {
        $query .= " AND gender = :gender";
    }

    if (!is_null($brand)) {
        $query .= " AND brand = :brand";
    }

    if (!is_null($id_categories)) {
        $query .= " AND id_categories = :id_categories";
    }

    // 🔍 Affichage de la requête SQL et des valeurs bindées
    error_log("Requête SQL générée : " . $query);
    error_log("Valeurs : " . print_r(['gender' => $gender, 'brand' => $brand, 'id_categories' => $id_categories], true));

    $stmt = $this->pdo->prepare($query);

    if (!is_null($gender)) {
        $stmt->bindValue(':gender', $gender, \PDO::PARAM_STR);
    }

    if (!is_null($brand)) {
        $stmt->bindValue(':brand', $brand, \PDO::PARAM_STR);
    }

    if (!is_null($id_categories)) {
        $stmt->bindValue(':id_categories', $id_categories, \PDO::PARAM_INT);
    }

    $stmt->execute();
    $resultats = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    // 🔍 Vérification des résultats
    error_log("Résultats SQL : " . print_r($resultats, true));

    return $resultats;
}

// fonction qui defini ce qu'on veut afficher d on appel un article
    public function getProducts()
    {
        $stmt = $this->pdo->prepare("SELECT id, name, image, brand AS image_path FROM products");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
}
?>