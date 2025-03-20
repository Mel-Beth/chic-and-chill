<?php

namespace Models;

class ContactModel extends ModeleParent
{
    public function addMessage($name, $email, $message, $source)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO contact_messages (name, email, message, source) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $message, $source]);
            return true;
        } catch (\PDOException $e) {
            error_log("Erreur lors de l'enregistrement du message: " . $e->getMessage());
            return false;
        }
    }

    public function addNewsletterSubscription($email)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO newsletter_subscribers (email, created_at) VALUES (?, NOW())");
            return $stmt->execute([$email]);
        } catch (\PDOException $e) {
            error_log("Erreur Newsletter: " . $e->getMessage());
            return false;
        }
    }

    public function getAllMessages()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function deleteMessage($id)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getRecentMessages()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function markMessageAsRead($id)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE contact_messages SET status = 'read' WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            error_log("Erreur mise à jour message lu: " . $e->getMessage());
            return false;
        }
    }

    public function countUnreadMessages()
    {
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) AS unread FROM contact_messages WHERE status = 'unread'");
            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            error_log("Erreur récupération messages non lus: " . $e->getMessage());
            return 0;
        }
    }

    public function updateMessageStatus($id, $status)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE contact_messages SET status = ? WHERE id = ?");
            return $stmt->execute([$status, $id]);
        } catch (\PDOException $e) {
            error_log("Erreur mise à jour statut du message: " . $e->getMessage());
            return false;
        }
    }

    public function getMessageSources($filter = '')
    {
        try {
            // On ignore le filtre pour toujours récupérer toutes les sources
            $query = "SELECT source, COUNT(*) as count FROM contact_messages GROUP BY source";
            $stmt = $this->pdo->query($query);
            $result = $stmt->fetchAll() ?: [];
            error_log("ContactModel getMessageSources result: " . json_encode($result));
            return $result;
        } catch (\PDOException $e) {
            error_log("Erreur dans ContactModel getMessageSources: " . $e->getMessage());
            return [];
        }
    }
}