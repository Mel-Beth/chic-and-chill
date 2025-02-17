<?php

namespace Models;

class UsersModel extends ModeleParent
{
    public function getAllUsers()
    {
        $stmt = $this->pdo->query("SELECT id, name, email, role, status FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function getUserById($userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public function verifyPassword($userId, $password)
    {
        $user = $this->getUserById($userId);
        return password_verify($password, $user['password']);
    }

    public function updatePassword($userId, $hashedPassword)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            return $stmt->execute([$hashedPassword, $userId]);
        } catch (\PDOException $e) {
            error_log("Erreur de mise à jour du mot de passe: " . $e->getMessage());
            return false;
        }
    }

    public function updateStatus($id, $status)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function deleteUser($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getUserFullHistory($user_id)
    {
        // Récupération de l'historique général
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
    ");
        $stmt->execute([$user_id, $user_id, $user_id, $user_id, $user_id]);
        return $stmt->fetchAll();
    }
}
