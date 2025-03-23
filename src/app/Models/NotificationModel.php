<?php

namespace Models;

class NotificationModel extends ModeleParent
{
    public function getUnreadNotifications()
    {
        try {
            $stmt = $this->pdo->query("
                SELECT id, message FROM notifications WHERE status = 'unread' ORDER BY created_at DESC
            ");
            return $stmt->fetchAll() ?: [];
        } catch (\PDOException $e) {
            error_log("Erreur récupération notifications non lues: " . $e->getMessage());
            return [];
        }
    }

    public function markAsRead($id)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE notifications SET status = 'read' WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            error_log("Erreur marquage notification comme lue: " . $e->getMessage());
            return false;
        }
    }

    public function createNotification($message)
    {
        try {
            error_log("Tentative d'insertion de notification : $message");
            $stmt = $this->pdo->prepare("INSERT INTO notifications (message, status, created_at) VALUES (?, 'unread', NOW())");
            $result = $stmt->execute([$message]);
            error_log("Insertion notification : " . ($result ? "Succès" : "Échec") . " - Message : $message");
            return $result;
        } catch (\PDOException $e) {
            error_log("Erreur création notification : " . $e->getMessage());
            return false;
        }
    }
}
