<?php

namespace Models;

class OutfitsModel extends ModeleParent
{
    private $notificationModel;

    public function __construct()
    {
        parent::__construct();
        $this->notificationModel = new NotificationModel();
    }

    public function getAllOutfits()
    {
        try {
            $stmt = $this->pdo->query("
                SELECT os.id, os.outfit_name, os.accessories, os.product_id, os.status, p.image AS product_image
                FROM outfits_suggestions os
                LEFT JOIN products p ON os.product_id = p.id
                ORDER BY os.id ASC
            ");
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function getAllOutfitsAdmin()
    {
        try {
            $stmt = $this->pdo->query("
                SELECT os.id, os.outfit_name, os.accessories, os.product_id, os.status, os.created_at, 
                       p.image AS product_image, p.name AS product_name, p.stock
                FROM outfits_suggestions os
                LEFT JOIN products p ON os.product_id = p.id
                ORDER BY os.id ASC
            ");
            $outfits = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $this->checkOutfitsStock($outfits); // Vérifie le stock ici
            return $outfits;
        } catch (\PDOException $e) {
            error_log("Erreur dans getAllOutfitsAdmin : " . $e->getMessage());
            return [];
        }
    }

    // Nouvelle méthode pour vérifier le stock et générer une notification
    private function checkOutfitsStock($outfits)
    {
        foreach ($outfits as $outfit) {
            if ($outfit['stock'] == 0) {
                $message = "L'article '{$outfit['product_name']}' (ID: {$outfit['product_id']}) lié à l'idée de tenue '{$outfit['outfit_name']}' est en rupture de stock. Veuillez mettre à jour cette suggestion.";
                $this->notificationModel->createNotification($message);
            }
        }
    }

    public function addOutfit($product_id, $outfit_name, $accessories, $status)
    {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO outfits_suggestions (product_id, outfit_name, accessories, created_at, status) 
                VALUES (:product_id, :outfit_name, :accessories, NOW(), :status)
            ");
            $stmt->bindValue(':product_id', $product_id, \PDO::PARAM_INT);
            $stmt->bindValue(':outfit_name', $outfit_name, \PDO::PARAM_STR);
            $stmt->bindValue(':accessories', !empty($accessories) ? $accessories : null, !empty($accessories) ? \PDO::PARAM_STR : \PDO::PARAM_NULL);
            $stmt->bindValue(':status', $status, \PDO::PARAM_STR);
            return $stmt->execute();
        } catch (\PDOException $e) {
            error_log("Erreur dans addOutfit : " . $e->getMessage());
            return false;
        }
    }

    public function updateOutfit($outfit_id, $product_id, $outfit_name, $accessories, $status)
    {
        $stmt = $this->pdo->prepare("UPDATE outfits_suggestions SET product_id = ?, outfit_name = ?, accessories = ?, created_at = NOW(), status = ? WHERE id = ?");
        return $stmt->execute([$product_id, $outfit_name, $accessories, $status, $outfit_id]);
    }

    public function deleteOutfit($outfit_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM outfits_suggestions WHERE id = ?");
        return $stmt->execute([$outfit_id]);
    }
}