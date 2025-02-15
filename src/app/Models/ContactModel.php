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
}
