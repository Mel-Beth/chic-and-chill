<?php

namespace Models;

class OutfitsModel extends ModeleParent
{
    public function getAllOutfits()
    {
        try {
            $stmt = $this->pdo->query("
                SELECT os.id, os.outfit_name, os.accessories, p.image 
                FROM outfits_suggestions os
                LEFT JOIN products p ON os.outfit_name = p.name
                ORDER BY os.id ASC
            ");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function getAllOutfitsAdmin()
    {
        $stmt = $this->pdo->query("SELECT * FROM outfits ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function createOutfit($title, $description, $image, $products)
    {
        $stmt = $this->pdo->prepare("INSERT INTO outfits (title, description, image, products) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$title, $description, $image, $products]);
    }

    public function updateOutfit($outfit_id, $title, $description, $image, $products)
    {
        $stmt = $this->pdo->prepare("UPDATE outfits SET title = ?, description = ?, image = ?, products = ? WHERE id = ?");
        return $stmt->execute([$title, $description, $image, $products, $outfit_id]);
    }

    public function deleteOutfit($outfit_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM outfits WHERE id = ?");
        return $stmt->execute([$outfit_id]);
    }
}
