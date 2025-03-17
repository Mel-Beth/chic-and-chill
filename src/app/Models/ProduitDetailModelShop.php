<?php

namespace Models;



class ProduitDetailModelShop extends ModeleParent
{
  

    // 🔹 Récupérer un produit par son ID
    public function getProduitById($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors de la récupération du produit : " . $e->getMessage());
        }
    }
}
