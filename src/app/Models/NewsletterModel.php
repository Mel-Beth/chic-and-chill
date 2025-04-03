<?php

namespace Models;

class NewsletterModel extends ModeleParent
{
    public function getAllSubscribers()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM newsletter_subscribers ORDER BY created_at DESC");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log("Erreur récupération abonnés newsletter : " . $e->getMessage());
            return [];
        }
    }

    public function deleteSubscriber($subscriber_id)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM newsletter_subscribers WHERE id = ?");
            $result = $stmt->execute([$subscriber_id]);
            error_log("Suppression abonné : " . ($result ? "Succès" : "Échec") . " - ID : $subscriber_id");
            return $result;
        } catch (\PDOException $e) {
            error_log("Erreur suppression abonné : " . $e->getMessage());
            return false;
        }
    }

    public function deleteSubscriberByEmail($email)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM newsletter_subscribers WHERE email = ?");
            $result = $stmt->execute([$email]);
            error_log("Suppression abonné par email : " . ($result ? "Succès" : "Échec") . " - Email : $email");
            return $result;
        } catch (\PDOException $e) {
            error_log("Erreur suppression abonné par email : " . $e->getMessage());
            return false;
        }
    }

    public function addNewsletterSubscription($email)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM newsletter_subscribers WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetchColumn() > 0) {
                error_log("Ajout abonné BDD : Email déjà inscrit - Email : $email");
                return true;
            }

            $stmt = $this->pdo->prepare("INSERT INTO newsletter_subscribers (email) VALUES (?)");
            $result = $stmt->execute([$email]);
            error_log("Ajout abonné BDD : " . ($result ? "Succès" : "Échec") . " - Email : $email");
            return $result;
        } catch (\PDOException $e) {
            error_log("Erreur ajout abonné newsletter : " . $e->getMessage());
            return false;
        }
    }
}