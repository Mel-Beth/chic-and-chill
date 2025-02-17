<?php

namespace Models;

class NotificationModel extends ModeleParent
{
    public function getUnreadNotifications()
    {
        $stmt = $this->pdo->query("
            SELECT id, message FROM notifications WHERE status = 'unread' ORDER BY created_at DESC
        ");
        return $stmt->fetchAll() ?: [];
    }

    public function markAsRead($id)
    {
        $stmt = $this->pdo->prepare("UPDATE notifications SET status = 'read' WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
