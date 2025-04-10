<?php

namespace Models\Events;

class OutfitsModel extends \Models\ModeleParent
{
    private $notificationModel;

    // Constructeur : Initialise le modèle parent et le modèle de notifications
    public function __construct()
    {
        parent::__construct(); // Appelle le constructeur du parent pour initialiser PDO
        $this->notificationModel = new \Models\NotificationModel(); // Initialise le modèle de notifications
    }

    // Récupère toutes les suggestions de tenues avec les informations de base
    public function getAllOutfits()
    {
        try {
            $stmt = $this->pdo->query("
                SELECT os.id, os.outfit_name, os.accessories, os.product_id, os.status, p.image AS product_image
                FROM outfits_suggestions os
                LEFT JOIN products p ON os.product_id = p.id
                ORDER BY os.id ASC
            "); // Requête pour récupérer les tenues avec l'image du produit associée
            return $stmt->fetchAll(\PDO::FETCH_ASSOC); // Retourne un tableau associatif
        } catch (\PDOException $e) {
            error_log($e->getMessage()); // Log l'erreur en cas d'échec
            return []; // Retourne un tableau vide si erreur
        }
    }

    // Récupère toutes les suggestions de tenues pour l'administration avec des détails supplémentaires
    public function getAllOutfitsAdmin()
    {
        try {
            $stmt = $this->pdo->query("
                SELECT os.id, os.outfit_name, os.accessories, os.product_id, os.status, os.created_at, 
                       p.image AS product_image, p.name AS product_name, p.stock
                FROM outfits_suggestions os
                LEFT JOIN products p ON os.product_id = p.id
                ORDER BY os.id ASC
            "); // Requête pour récupérer les tenues avec des détails supplémentaires (nom, stock)
            $outfits = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Récupère les résultats
            $this->checkOutfitsStock($outfits); // Vérifie le stock et génère des notifications si nécessaire
            return $outfits; // Retourne les tenues
        } catch (\PDOException $e) {
            error_log("Erreur dans getAllOutfitsAdmin : " . $e->getMessage()); // Log l'erreur
            return []; // Retourne un tableau vide si erreur
        }
    }

    // Vérifie le stock des produits liés aux tenues et génère une notification si rupture
    private function checkOutfitsStock($outfits)
    {
        foreach ($outfits as $outfit) {
            if ($outfit['stock'] == 0) { // Vérifie si le stock est à zéro
                $message = "L'article '{$outfit['product_name']}' (ID: {$outfit['product_id']}) lié à l'idée de tenue '{$outfit['outfit_name']}' est en rupture de stock. Veuillez mettre à jour cette suggestion.";
                $this->notificationModel->createNotification($message); // Crée une notification pour l'administrateur
            }
        }
    }

    // Ajoute une nouvelle suggestion de tenue
    public function addOutfit($product_id, $accessories, $status)
    {
        try {
            // Récupère le nom du produit associé pour l'utiliser comme nom de la tenue
            $stmt = $this->pdo->prepare("SELECT name FROM products WHERE id = :product_id");
            $stmt->bindValue(':product_id', $product_id, \PDO::PARAM_INT); // Lie l'ID du produit
            $stmt->execute();
            $product = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$product) {
                throw new \Exception("Produit non trouvé pour l'ID $product_id"); // Erreur si produit inexistant
            }

            $outfit_name = $product['name']; // Utilise le nom du produit comme nom de la tenue

            // Insère la nouvelle tenue dans la base de données
            $stmt = $this->pdo->prepare("
                INSERT INTO outfits_suggestions (product_id, outfit_name, accessories, created_at, status) 
                VALUES (:product_id, :outfit_name, :accessories, NOW(), :status)
            ");
            $stmt->bindValue(':product_id', $product_id, \PDO::PARAM_INT); // Lie l'ID du produit
            $stmt->bindValue(':outfit_name', $outfit_name, \PDO::PARAM_STR); // Lie le nom de la tenue
            $stmt->bindValue(':accessories', !empty($accessories) ? $accessories : null, !empty($accessories) ? \PDO::PARAM_STR : \PDO::PARAM_NULL); // Lie les accessoires ou null
            $stmt->bindValue(':status', $status, \PDO::PARAM_STR); // Lie le statut
            return $stmt->execute(); // Exécute et retourne true si succès
        } catch (\PDOException $e) {
            error_log("Erreur dans addOutfit : " . $e->getMessage()); // Log l'erreur
            return false; // Retourne false si erreur
        }
    }

    // Met à jour une suggestion de tenue existante
    public function updateOutfit($outfit_id, $product_id, $accessories, $status)
    {
        try {
            // Récupère le nom du produit associé pour l'utiliser comme nom de la tenue
            $stmt = $this->pdo->prepare("SELECT name FROM products WHERE id = :product_id");
            $stmt->bindValue(':product_id', $product_id, \PDO::PARAM_INT); // Lie l'ID du produit
            $stmt->execute();
            $product = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$product) {
                throw new \Exception("Produit non trouvé pour l'ID $product_id"); // Erreur si produit inexistant
            }

            $outfit_name = $product['name']; // Utilise le nom du produit comme nom de la tenue

            // Met à jour la tenue dans la base de données
            $stmt = $this->pdo->prepare("
                UPDATE outfits_suggestions 
                SET product_id = :product_id, outfit_name = :outfit_name, accessories = :accessories, created_at = NOW(), status = :status 
                WHERE id = :outfit_id
            ");
            $stmt->bindValue(':product_id', $product_id, \PDO::PARAM_INT); // Lie l'ID du produit
            $stmt->bindValue(':outfit_name', $outfit_name, \PDO::PARAM_STR); // Lie le nom de la tenue
            $stmt->bindValue(':accessories', !empty($accessories) ? $accessories : null, !empty($accessories) ? \PDO::PARAM_STR : \PDO::PARAM_NULL); // Lie les accessoires ou null
            $stmt->bindValue(':status', $status, \PDO::PARAM_STR); // Lie le statut
            $stmt->bindValue(':outfit_id', $outfit_id, \PDO::PARAM_INT); // Lie l'ID de la tenue
            return $stmt->execute(); // Exécute et retourne true si succès
        } catch (\PDOException $e) {
            error_log("Erreur dans updateOutfit : " . $e->getMessage()); // Log l'erreur
            return false; // Retourne false si erreur
        }
    }

    // Supprime une suggestion de tenue par son ID
    public function deleteOutfit($outfit_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM outfits_suggestions WHERE id = ?"); // Prépare la requête de suppression
        return $stmt->execute([$outfit_id]); // Exécute et retourne true si succès
    }
}
?>