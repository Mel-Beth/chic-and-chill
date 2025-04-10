<?php

namespace Models\Events;

class PacksModel extends \Models\ModeleParent
{
    // Récupère tous les packs d'événements, triés par ID croissant
    public function getAllPacks()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM event_packs ORDER BY id ASC"); // Requête pour tous les packs
            return $stmt->fetchAll(); // Retourne un tableau de tous les packs
        } catch (\PDOException $e) {
            error_log($e->getMessage()); // Log l'erreur en cas d'échec
            return []; // Retourne un tableau vide si erreur
        }
    }

    // Récupère un pack spécifique par son ID
    public function getPackById($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM event_packs WHERE id = ?"); // Prépare la requête avec un paramètre
            $stmt->execute([$id]); // Exécute avec l'ID fourni
            return $stmt->fetch(); // Retourne une seule ligne (ou false si non trouvé)
        } catch (\PDOException $e) {
            error_log($e->getMessage()); // Log l'erreur en cas d'échec
            return null; // Retourne null si erreur
        }
    }

    // Récupère tous les packs pour l'administration, triés par date de création décroissante
    public function getAllPacksAdmin()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM event_packs ORDER BY created_at DESC"); // Requête pour tous les packs, ordre décroissant
            return $stmt->fetchAll(); // Retourne un tableau de tous les packs
        } catch (\PDOException $e) {
            error_log($e->getMessage()); // Log l'erreur en cas d'échec
            return []; // Retourne un tableau vide si erreur
        }
    }

    // Ajoute un nouveau pack d'événement à la base de données
    public function addPack($title, $description, $price, $duration, $included, $status)
    {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO event_packs (title, description, price, duration, included, status) 
                VALUES (?, ?, ?, ?, ?, ?)
            "); // Prépare la requête d'insertion
            return $stmt->execute([$title, $description, $price, $duration, $included, $status]); // Exécute avec les paramètres fournis
        } catch (\PDOException $e) {
            error_log($e->getMessage()); // Log l'erreur en cas d'échec
            return false; // Retourne false si erreur
        }
    }

    // Met à jour un pack existant dans la base de données
    public function updatePack($pack_id, $title, $description, $price, $duration, $included, $status)
    {
        $stmt = $this->pdo->prepare("
            UPDATE event_packs 
            SET title = ?, description = ?, price = ?, duration = ?, included = ?, status = ? 
            WHERE id = ?
        "); // Prépare la requête de mise à jour
        return $stmt->execute([$title, $description, $price, $duration, $included, $status, $pack_id]); // Exécute avec les paramètres fournis
    }

    // Supprime un pack spécifique par son ID
    public function deletePack($pack_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM event_packs WHERE id = ?"); // Prépare la requête de suppression
        return $stmt->execute([$pack_id]); // Exécute et retourne true si succès
    }
}
?>