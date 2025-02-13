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
}
