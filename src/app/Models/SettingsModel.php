<?php

namespace Models;

class SettingsModel extends ModeleParent
{
    // Récupère les paramètres de base d'un utilisateur (nom, email, rôle)
    public function getSettings($userId)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT name, email, role FROM users WHERE id = ?"); // Prépare la requête
            $stmt->execute([$userId]); // Exécute avec l'ID de l'utilisateur
            return $stmt->fetch(\PDO::FETCH_ASSOC) ?: []; // Retourne les paramètres ou un tableau vide si non trouvé
        } catch (\PDOException $e) {
            error_log("Erreur récupération paramètres: " . $e->getMessage()); // Log l'erreur
            return []; // Retourne un tableau vide si erreur
        }
    }

    // Met à jour les paramètres d'un utilisateur (nom, email) tout en préservant le rôle
    public function updateUserSettings($userId, $name, $email)
    {
        try {
            $currentSettings = $this->getSettings($userId); // Récupère les paramètres actuels
            $role = $currentSettings['role'] ?? 'user'; // Préserve le rôle existant ou utilise "user" par défaut

            $stmt = $this->pdo->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?"); // Prépare la requête
            $success = $stmt->execute([$name, $email, $role, $userId]); // Exécute avec les nouvelles valeurs
            return ['success' => $success, 'message' => $success ? "Mise à jour réussie" : "Échec mise à jour"]; // Retourne le résultat
        } catch (\PDOException $e) {
            error_log("Erreur mise à jour paramètres: " . $e->getMessage()); // Log l'erreur
            return ['success' => false, 'message' => $e->getMessage()]; // Retourne une erreur
        }
    }

    // Vérifie si le mot de passe actuel correspond à celui stocké
    public function verifyPassword($userId, $currentPassword)
    {
        $stmt = $this->pdo->prepare("SELECT password FROM users WHERE id = ?"); // Prépare la requête
        $stmt->execute([$userId]); // Exécute avec l'ID
        $user = $stmt->fetch(\PDO::FETCH_ASSOC); // Récupère le mot de passe haché
        return $user && password_verify($currentPassword, $user['password']); // Vérifie et retourne true/false
    }

    // Met à jour le mot de passe d'un utilisateur
    public function updatePassword($userId, $hashedPassword)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE users SET password = ? WHERE id = ?"); // Prépare la requête
            $success = $stmt->execute([$hashedPassword, $userId]); // Exécute avec le mot de passe haché
            return ['success' => $success, 'message' => $success ? "Mot de passe mis à jour" : "Échec mise à jour"]; // Retourne le résultat
        } catch (\PDOException $e) {
            error_log("Erreur mise à jour mot de passe: " . $e->getMessage()); // Log l'erreur
            return ['success' => false, 'message' => $e->getMessage()]; // Retourne une erreur
        }
    }

    // Supprime le compte d'un utilisateur
    public function deleteAccount($userId)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?"); // Prépare la requête de suppression
            $success = $stmt->execute([$userId]); // Exécute avec l'ID
            return ['success' => $success, 'message' => $success ? "Compte supprimé" : "Échec suppression"]; // Retourne le résultat
        } catch (\PDOException $e) {
            error_log("Erreur suppression compte: " . $e->getMessage()); // Log l'erreur
            return ['success' => false, 'message' => $e->getMessage()]; // Retourne une erreur
        }
    }

    // Met à jour les préférences de notification d'un utilisateur
    public function updateNotificationSettings($userId, $notifyMessages, $notifyOrders, $notifyReservations, $notifyPackReservations, $notifyProductsSoldRented, $siteNotifications, $emailFrequency)
    {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE users SET notify_messages = ?, notify_orders = ?, notify_reservations = ?, 
                notify_pack_reservations = ?, notify_products_sold_rented = ?, site_notifications = ?, 
                email_frequency = ? WHERE id = ?
            "); // Prépare la requête pour mettre à jour les préférences
            $success = $stmt->execute([
                $notifyMessages ? 1 : 0, $notifyOrders ? 1 : 0, $notifyReservations ? 1 : 0,
                $notifyPackReservations ? 1 : 0, $notifyProductsSoldRented ? 1 : 0, $siteNotifications ? 1 : 0,
                $emailFrequency, $userId
            ]); // Exécute avec les valeurs booléennes converties en 1/0
            return ['success' => $success, 'message' => $success ? "Notifications mises à jour" : "Échec mise à jour"]; // Retourne le résultat
        } catch (\PDOException $e) {
            error_log("Erreur mise à jour notifications: " . $e->getMessage()); // Log l'erreur
            return ['success' => false, 'message' => $e->getMessage()]; // Retourne une erreur
        }
    }

    // Récupère tous les utilisateurs avec leurs informations de base
    public function getAllUsers()
    {
        try {
            $stmt = $this->pdo->query("SELECT id, name, email, role FROM users"); // Requête pour tous les utilisateurs
            return $stmt->fetchAll(\PDO::FETCH_ASSOC); // Retourne un tableau associatif
        } catch (\PDOException $e) {
            error_log("Erreur récupération utilisateurs: " . $e->getMessage()); // Log l'erreur
            return []; // Retourne un tableau vide si erreur
        }
    }

    // Récupère tous les produits avec leurs informations de base
    public function getAllProducts()
    {
        try {
            $stmt = $this->pdo->query("SELECT id, name, price, stock FROM products"); // Requête pour tous les produits
            return $stmt->fetchAll(\PDO::FETCH_ASSOC); // Retourne un tableau associatif
        } catch (\PDOException $e) {
            error_log("Erreur récupération produits: " . $e->getMessage()); // Log l'erreur
            return []; // Retourne un tableau vide si erreur
        }
    }

    // Insère un utilisateur importé avec un mot de passe par défaut
    public function insertImportedUser($name, $email, $role)
    {
        try {
            $defaultPassword = password_hash("default123", PASSWORD_BCRYPT); // Génère un mot de passe par défaut haché
            $stmt = $this->pdo->prepare("INSERT INTO users (name, email, role, password) VALUES (?, ?, ?, ?)"); // Prépare la requête
            $success = $stmt->execute([$name, $email, $role, $defaultPassword]); // Exécute avec les valeurs
            return ['success' => $success, 'message' => $success ? "Utilisateur importé" : "Échec import"]; // Retourne le résultat
        } catch (\PDOException $e) {
            error_log("Erreur import utilisateur: " . $e->getMessage()); // Log l'erreur
            return ['success' => false, 'message' => $e->getMessage()]; // Retourne une erreur
        }
    }

    // Enregistre une action dans l'historique des utilisateurs
    public function logAction($userId, $username, $action, $ipAddress)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO user_history (user_id, username, action, ip_address) VALUES (?, ?, ?, ?)"); // Prépare la requête
            return $stmt->execute([$userId, $username, $action, $ipAddress]); // Exécute et retourne true si succès
        } catch (\PDOException $e) {
            error_log("Erreur enregistrement action: " . $e->getMessage()); // Log l'erreur
            return false; // Retourne false si erreur
        }
    }

    // Récupère les 50 dernières actions de l'historique
    public function getActionHistory()
    {
        try {
            $stmt = $this->pdo->query("
                SELECT action_date, username, action, ip_address 
                FROM user_history 
                ORDER BY action_date DESC 
                LIMIT 50
            "); // Requête pour les 50 dernières actions
            return $stmt->fetchAll(\PDO::FETCH_ASSOC); // Retourne un tableau associatif
        } catch (\PDOException $e) {
            error_log("Erreur récupération historique: " . $e->getMessage()); // Log l'erreur
            return []; // Retourne un tableau vide si erreur
        }
    }
}
?>