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
}
