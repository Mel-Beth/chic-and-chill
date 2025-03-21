<?php

namespace Models;

// modèle pr mettre à jour le stock après 1 paiement validé

class MiseAjourStockShop extends ModeleParent
{
    public function mettreAJourStock($idProduit, $quantiteAchetee)
{
    try {
        // Vérifier si le produit existe et récupérer son stock actuel
        $sqlVerif = "SELECT stock FROM products WHERE id = :idProduit";
        $stmtVerif = $this->pdo->prepare($sqlVerif);
        $stmtVerif->bindValue(':idProduit', $idProduit, \PDO::PARAM_INT);
        $stmtVerif->execute();
        $produit = $stmtVerif->fetch(\PDO::FETCH_ASSOC);

        if (!$produit) {
            error_log("❌ Produit introuvable (ID: $idProduit)");
            return false;
        }

        if ($produit['stock'] < $quantiteAchetee) {
            error_log("⚠️ Stock insuffisant pour le produit ID: $idProduit (Stock: {$produit['stock']}, Demandé: $quantiteAchetee)");
            return false;
        }

        // Mettre à jour le stock
        $sql = "UPDATE products SET stock = stock - :quantite WHERE id = :idProduit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':quantite', $quantiteAchetee, \PDO::PARAM_INT);
        $stmt->bindValue(':idProduit', $idProduit, \PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            error_log("⚠️ Mise à jour du stock échouée pour le produit (ID: $idProduit)");
            return false;
        }

        error_log("✅ Stock mis à jour pour le produit ID: $idProduit (Stock restant: " . ($produit['stock'] - $quantiteAchetee) . ")");
        return true;
    } catch (\PDOException $e) {
        error_log('❌ Erreur lors de la mise à jour du stock : ' . $e->getMessage());
        return false;
    }
}
}
