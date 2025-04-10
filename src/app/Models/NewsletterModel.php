<?php

namespace Models;

class NewsletterModel extends ModeleParent
{
    // Récupère tous les abonnés à la newsletter, triés par date d'inscription décroissante
    public function getAllSubscribers()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM newsletter_subscribers ORDER BY created_at DESC"); // Requête pour tous les abonnés
            return $stmt->fetchAll(); // Retourne les résultats sous forme de tableau
        } catch (\PDOException $e) {
            error_log("Erreur récupération abonnés newsletter : " . $e->getMessage()); // Log l'erreur en cas d'échec
            return []; // Retourne un tableau vide si erreur
        }
    }

    // Supprime un abonné spécifique par son ID
    public function deleteSubscriber($subscriber_id)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM newsletter_subscribers WHERE id = ?"); // Prépare la requête de suppression
            $result = $stmt->execute([$subscriber_id]); // Exécute avec l'ID fourni
            error_log("Suppression abonné : " . ($result ? "Succès" : "Échec") . " - ID : $subscriber_id"); // Log le résultat
            return $result; // Retourne true si succès, false sinon
        } catch (\PDOException $e) {
            error_log("Erreur suppression abonné : " . $e->getMessage()); // Log l'erreur en cas d'échec
            return false; // Retourne false si erreur
        }
    }

    // Supprime un abonné spécifique par son email
    public function deleteSubscriberByEmail($email)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM newsletter_subscribers WHERE email = ?"); // Prépare la requête de suppression
            $result = $stmt->execute([$email]); // Exécute avec l'email fourni
            error_log("Suppression abonné par email : " . ($result ? "Succès" : "Échec") . " - Email : $email"); // Log le résultat
            return $result; // Retourne true si succès, false sinon
        } catch (\PDOException $e) {
            error_log("Erreur suppression abonné par email : " . $e->getMessage()); // Log l'erreur en cas d'échec
            return false; // Retourne false si erreur
        }
    }

    // Ajoute un nouvel abonné à la newsletter s'il n'existe pas déjà
    public function addNewsletterSubscription($email)
    {
        try {
            // Vérifie si l'email existe déjà dans la base de données
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM newsletter_subscribers WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetchColumn() > 0) {
                error_log("Ajout abonné BDD : Email déjà inscrit - Email : $email"); // Log si email déjà présent
                return true; // Retourne true car l'abonné existe déjà
            }

            // Insère le nouvel abonné
            $stmt = $this->pdo->prepare("INSERT INTO newsletter_subscribers (email) VALUES (?)"); // Prépare la requête d'insertion
            $result = $stmt->execute([$email]); // Exécute avec l'email fourni
            error_log("Ajout abonné BDD : " . ($result ? "Succès" : "Échec") . " - Email : $email"); // Log le résultat
            return $result; // Retourne true si succès, false sinon
        } catch (\PDOException $e) {
            error_log("Erreur ajout abonné newsletter : " . $e->getMessage()); // Log l'erreur en cas d'échec
            return false; // Retourne false si erreur
        }
    }
}
?>