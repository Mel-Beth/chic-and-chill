<?php

namespace Models;

class NotificationModel extends ModeleParent
{
    // Récupère toutes les notifications non lues
    public function getUnreadNotifications()
    {
        try {
            $stmt = $this->pdo->query("
                SELECT id, message FROM notifications WHERE status = 'unread' ORDER BY created_at DESC
            "); // Requête pour sélectionner les notifications non lues, triées par date décroissante
            return $stmt->fetchAll() ?: []; // Retourne les résultats ou un tableau vide si aucun résultat
        } catch (\PDOException $e) {
            error_log("Erreur récupération notifications non lues: " . $e->getMessage()); // Log l'erreur en cas d'échec
            return []; // Retourne un tableau vide si erreur
        }
    }

    // Marque une notification comme lue en fonction de son ID
    public function markAsRead($id)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE notifications SET status = 'read' WHERE id = ?"); // Prépare la requête de mise à jour
            return $stmt->execute([$id]); // Exécute avec l'ID fourni et retourne true si succès
        } catch (\PDOException $e) {
            error_log("Erreur marquage notification comme lue: " . $e->getMessage()); // Log l'erreur en cas d'échec
            return false; // Retourne false si erreur
        }
    }

    // Crée une nouvelle notification avec un message donné
    public function createNotification($message)
    {
        try {
            error_log("Tentative d'insertion de notification : $message"); // Log le début de l'opération
            $stmt = $this->pdo->prepare("
                INSERT INTO notifications (message, status, created_at) VALUES (?, 'unread', NOW())
            "); // Prépare la requête d'insertion avec statut "unread" et date actuelle
            $result = $stmt->execute([$message]); // Exécute avec le message fourni
            error_log("Insertion notification : " . ($result ? "Succès" : "Échec") . " - Message : $message"); // Log le résultat
            return $result; // Retourne true si succès, false sinon
        } catch (\PDOException $e) {
            error_log("Erreur création notification : " . $e->getMessage()); // Log l'erreur en cas d'échec
            return false; // Retourne false si erreur
        }
    }
}
?>