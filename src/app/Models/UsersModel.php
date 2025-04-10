<?php

namespace Models;

class UsersModel extends ModeleParent
{
    // Récupère tous les utilisateurs avec leurs informations de base
    public function getAllUsers()
    {
        $stmt = $this->pdo->query("SELECT id, name, email, role, status FROM users ORDER BY created_at DESC"); // Requête pour tous les utilisateurs, triés par date de création
        return $stmt->fetchAll(); // Retourne un tableau de tous les utilisateurs
    }

    // Récupère un utilisateur spécifique par son ID
    public function getUserById($userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?"); // Prépare la requête avec un paramètre
        $stmt->execute([$userId]); // Exécute avec l'ID fourni
        return $stmt->fetch(); // Retourne les détails de l'utilisateur ou false si non trouvé
    }

    // Vérifie si le mot de passe fourni correspond au mot de passe haché de l'utilisateur
    public function verifyPassword($userId, $password)
    {
        $user = $this->getUserById($userId); // Récupère les détails de l'utilisateur
        return password_verify($password, $user['password']); // Vérifie le mot de passe et retourne true/false
    }

    // Met à jour le mot de passe d'un utilisateur avec un nouveau mot de passe haché
    public function updatePassword($userId, $hashedPassword)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE users SET password = ? WHERE id = ?"); // Prépare la requête de mise à jour
            return $stmt->execute([$hashedPassword, $userId]); // Exécute avec le mot de passe haché et l'ID
        } catch (\PDOException $e) {
            error_log("Erreur de mise à jour du mot de passe: " . $e->getMessage()); // Log l'erreur en cas d'échec
            return false; // Retourne false si erreur
        }
    }

    // Met à jour le statut d'un utilisateur (par exemple, actif/inactif)
    public function updateStatus($id, $status)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET status = ? WHERE id = ?"); // Prépare la requête de mise à jour
        return $stmt->execute([$status, $id]); // Exécute avec le statut et l'ID, retourne true si succès
    }

    // Supprime un utilisateur spécifique par son ID
    public function deleteUser($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?"); // Prépare la requête de suppression
        return $stmt->execute([$id]); // Exécute avec l'ID, retourne true si succès
    }

    // Récupère l'historique complet d'un utilisateur (actions, commandes, réservations, paiements)
    public function getUserFullHistory($user_id)
    {
        $stmt = $this->pdo->prepare("
            SELECT 'historique' AS type, action AS description, action_date AS date
            FROM user_history
            WHERE user_id = ?
            UNION
            SELECT 'commande' AS type, CONCAT('Commande #', id, ' - ', total_price, '€ - ', status) AS description, created_at AS date
            FROM event_orders
            WHERE user_id = ?
            UNION
            SELECT 'reservation_evenement' AS type, CONCAT('Réservation événement ', event_id, ' - ', status) AS description, created_at AS date
            FROM event_reservations
            WHERE email = (SELECT email FROM users WHERE id = ?)
            UNION
            SELECT 'reservation_pack' AS type, CONCAT('Réservation pack ', pack_id, ' - ', status) AS description, created_at AS date
            FROM pack_reservations
            WHERE email = (SELECT email FROM users WHERE id = ?)
            UNION
            SELECT 'paiement' AS type, CONCAT('Paiement de ', amount, '€ - ', status) AS description, created_at AS date
            FROM payments
            WHERE user_id = ?
            ORDER BY date DESC
        "); // Requête UNION pour combiner différentes sources d'historique
        $stmt->execute([$user_id, $user_id, $user_id, $user_id, $user_id]); // Exécute avec l'ID de l'utilisateur
        return $stmt->fetchAll(); // Retourne un tableau de l'historique trié par date décroissante
    }
}
?>