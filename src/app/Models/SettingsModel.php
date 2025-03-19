<?php

namespace Models;

use PDO;

class SettingsModel extends ModeleParent
{
    public function getSettings($userId)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT name, email, role FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        } catch (\PDOException $e) {
            error_log("Erreur récupération paramètres: " . $e->getMessage());
            return [];
        }
    }

    public function updateUserSettings($userId, $name, $email, $role)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
            $success = $stmt->execute([$name, $email, $role, $userId]);
            return ['success' => $success, 'message' => $success ? "Mise à jour réussie" : "Échec mise à jour"];
        } catch (\PDOException $e) {
            error_log("Erreur mise à jour paramètres: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function updateAppearance($userId, $darkMode, $themeColor, $fontFamily, $fontSize, $showTraffic, $showSales, $showOrders)
    {
        try {
            // Débogage des valeurs reçues
            error_log("updateAppearance pour user_id $userId : darkMode=$darkMode, themeColor=$themeColor, fontFamily=$fontFamily, fontSize=$fontSize, showTraffic=$showTraffic, showSales=$showSales, showOrders=$showOrders");

            // Assurer que les booléens sont bien convertis en 0 ou 1 pour MySQL
            $showTraffic = $showTraffic ? 1 : 0;
            $showSales = $showSales ? 1 : 0;
            $showOrders = $showOrders ? 1 : 0;

            $stmt = $this->pdo->prepare("
                REPLACE INTO user_settings 
                (user_id, dark_mode, theme_color, font_family, font_size, show_traffic, show_sales, show_orders) 
                VALUES (:user_id, :dark_mode, :theme_color, :font_family, :font_size, :show_traffic, :show_sales, :show_orders)
            ");
            $success = $stmt->execute([
                ':user_id' => $userId,
                ':dark_mode' => $darkMode,
                ':theme_color' => $themeColor,
                ':font_family' => $fontFamily,
                ':font_size' => $fontSize,
                ':show_traffic' => $showTraffic,
                ':show_sales' => $showSales,
                ':show_orders' => $showOrders
            ]);

            if ($success) {
                error_log("Mise à jour réussie pour user_id $userId");
            } else {
                error_log("Échec de la mise à jour pour user_id $userId");
            }

            return ['success' => $success, 'message' => $success ? "Apparence mise à jour" : "Échec mise à jour"];
        } catch (\PDOException $e) {
            error_log("Erreur mise à jour apparence: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getAppearanceSettings($userId)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT dark_mode, theme_color, font_family, font_size, show_traffic, show_sales, show_orders FROM user_settings WHERE user_id = ?");
            $stmt->execute([$userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            error_log("getAppearanceSettings pour user_id $userId : " . print_r($result, true));

            // Convertir les booléens de la base de données (0/1) en true/false
            if ($result) {
                $result['show_traffic'] = (bool)$result['show_traffic'];
                $result['show_sales'] = (bool)$result['show_sales'];
                $result['show_orders'] = (bool)$result['show_orders'];
            }

            return $result ?: [
                'dark_mode' => 'disabled',
                'theme_color' => 'blue',
                'font_family' => 'sans-serif',
                'font_size' => 'normal',
                'show_traffic' => true,
                'show_sales' => true,
                'show_orders' => true
            ];
        } catch (\PDOException $e) {
            error_log("Erreur récupération paramètres apparence: " . $e->getMessage());
            return [
                'dark_mode' => 'disabled',
                'theme_color' => 'blue',
                'font_family' => 'sans-serif',
                'font_size' => 'normal',
                'show_traffic' => true,
                'show_sales' => true,
                'show_orders' => true
            ];
        }
    }

    public function verifyPassword($userId, $currentPassword)
    {
        $stmt = $this->pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user && password_verify($currentPassword, $user['password']);
    }

    public function updatePassword($userId, $hashedPassword)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $success = $stmt->execute([$hashedPassword, $userId]);
            return ['success' => $success, 'message' => $success ? "Mot de passe mis à jour" : "Échec mise à jour"];
        } catch (\PDOException $e) {
            error_log("Erreur mise à jour mot de passe: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function deleteAccount($userId)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
            $success = $stmt->execute([$userId]);
            return ['success' => $success, 'message' => $success ? "Compte supprimé" : "Échec suppression"];
        } catch (\PDOException $e) {
            error_log("Erreur suppression compte: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function updateNotificationSettings($userId, $notifyMessages, $notifyOrders, $notifyReservations, $notifyPackReservations, $notifyProductsSoldRented, $siteNotifications, $emailFrequency)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE users SET notify_messages = ?, notify_orders = ?, notify_reservations = ?, notify_pack_reservations = ?, notify_products_sold_rented = ?, site_notifications = ?, email_frequency = ? WHERE id = ?");
            $success = $stmt->execute([$notifyMessages ? 1 : 0, $notifyOrders ? 1 : 0, $notifyReservations ? 1 : 0, $notifyPackReservations ? 1 : 0, $notifyProductsSoldRented ? 1 : 0, $siteNotifications ? 1 : 0, $emailFrequency, $userId]);
            return ['success' => $success, 'message' => $success ? "Notifications mises à jour" : "Échec mise à jour"];
        } catch (\PDOException $e) {
            error_log("Erreur mise à jour notifications: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getAllUsers()
    {
        try {
            $stmt = $this->pdo->query("SELECT id, name, email, role FROM users");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erreur récupération utilisateurs: " . $e->getMessage());
            return [];
        }
    }

    public function getAllProducts()
    {
        try {
            $stmt = $this->pdo->query("SELECT id, name, price, stock FROM products");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erreur récupération produits: " . $e->getMessage());
            return [];
        }
    }

    public function insertImportedUser($name, $email, $role)
    {
        try {
            $defaultPassword = password_hash("default123", PASSWORD_BCRYPT);
            $stmt = $this->pdo->prepare("INSERT INTO users (name, email, role, password) VALUES (?, ?, ?, ?)");
            $success = $stmt->execute([$name, $email, $role, $defaultPassword]);
            return ['success' => $success, 'message' => $success ? "Utilisateur importé" : "Échec import"];
        } catch (\PDOException $e) {
            error_log("Erreur import utilisateur: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function logAction($userId, $username, $action, $ipAddress)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO user_history (user_id, username, action, ip_address) VALUES (?, ?, ?, ?)");
            return $stmt->execute([$userId, $username, $action, $ipAddress]);
        } catch (\PDOException $e) {
            error_log("Erreur enregistrement action: " . $e->getMessage());
            return false;
        }
    }

    public function getActionHistory()
    {
        try {
            $stmt = $this->pdo->query("SELECT action_date, username, action, ip_address FROM user_history ORDER BY action_date DESC LIMIT 50");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erreur récupération historique: " . $e->getMessage());
            return [];
        }
    }
}