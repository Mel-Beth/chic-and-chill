<?php

namespace Models;

class NotificationModel extends ModeleParent
{
    public function getUnreadNotifications()
    {
        try {
            // Si les notifications sont spécifiques à un utilisateur, ajoutez une condition WHERE user_id = ?
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
}