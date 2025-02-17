<?php

namespace Models;

use PDO;

class SettingsModel extends ModeleParent
{
    public function getSettings()
    {
        try {
            $stmt = $this->pdo->query("SELECT name, email, role FROM users WHERE id = 1 LIMIT 1");
            return $stmt->fetch();
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération des paramètres : " . $e->getMessage());
            return [];
        }
    }

    public function updateUserSettings($name, $email, $role)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = 1");
            return $stmt->execute([$name, $email, $role]);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la mise à jour des paramètres : " . $e->getMessage());
            return false;
        }
    }

    public function updateAppearance($userId, $darkMode, $themeColor, $fontFamily, $fontSize, $showTraffic, $showSales, $showOrders)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE user_settings SET dark_mode = ?, theme_color = ?, font_family = ?, font_size = ?, show_traffic = ?, show_sales = ?, show_orders = ? WHERE user_id = ?");
            return $stmt->execute([$darkMode, $themeColor, $fontFamily, $fontSize, $showTraffic, $showSales, $showOrders, $userId]);
        } catch (\PDOException $e) {
            error_log("Erreur mise à jour apparence: " . $e->getMessage());
            return false;
        }
    }

    public function verifyPassword($userId, $currentPassword)
    {
        $stmt = $this->pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        return password_verify($currentPassword, $user['password']);
    }

    public function updatePassword($userId, $hashedPassword)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            return $stmt->execute([$hashedPassword, $userId]);
        } catch (\PDOException $e) {
            error_log("Erreur mise à jour mot de passe: " . $e->getMessage());
            return false;
        }
    }

    public function deleteAccount($userId)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
            return $stmt->execute([$userId]);
        } catch (\PDOException $e) {
            error_log("Erreur suppression du compte: " . $e->getMessage());
            return false;
        }
    }

    public function updateNotificationSettings($userId, $notifyMessages, $notifyOrders, $notifyReservations, $notifyPackReservations, $notifyProductsSoldRented, $siteNotifications, $emailFrequency)
    {
        try {
            $stmt = $this->pdo->prepare("
            UPDATE users SET
                notify_messages = ?,
                notify_orders = ?,
                notify_reservations = ?,
                notify_pack_reservations = ?,
                notify_products_sold_rented = ?,
                site_notifications = ?,
                email_frequency = ?
            WHERE id = ?
        ");

            return $stmt->execute([
                $notifyMessages ? 1 : 0,
                $notifyOrders ? 1 : 0,
                $notifyReservations ? 1 : 0,
                $notifyPackReservations ? 1 : 0,
                $notifyProductsSoldRented ? 1 : 0,
                $siteNotifications ? 1 : 0,
                $emailFrequency,
                $userId
            ]);
        } catch (\PDOException $e) {
            error_log("Erreur mise à jour notifications: " . $e->getMessage());
            return false;
        }
    }

    public function getInvoices($userId)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM invoices WHERE user_id = ? ORDER BY created_at DESC");
            $stmt->execute([$userId]);
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log("Erreur récupération factures: " . $e->getMessage());
            return [];
        }
    }

    public function cancelUserSubscription($userId)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE users SET subscription_status = 'canceled' WHERE id = ?");
            return $stmt->execute([$userId]);
        } catch (\PDOException $e) {
            error_log("Erreur annulation abonnement: " . $e->getMessage());
            return false;
        }
    }

    public function applyPromoCode($userId, $promoCode)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM promo_codes WHERE code = ? AND expiration_date > NOW()");
            $stmt->execute([$promoCode]);
            $promo = $stmt->fetch();

            if (!$promo) {
                return false; // Code promo invalide ou expiré
            }

            $stmt = $this->pdo->prepare("UPDATE users SET discount = ? WHERE id = ?");
            return $stmt->execute([$promo["discount"], $userId]);
        } catch (\PDOException $e) {
            error_log("Erreur application code promo: " . $e->getMessage());
            return false;
        }
    }

    public function updateLanguageSettings($language, $timezone, $country)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE settings SET language = ?, timezone = ?, country = ? WHERE id = 1");
            return $stmt->execute([$language, $timezone, $country]);
        } catch (\PDOException $e) {
            error_log("Erreur mise à jour settings: " . $e->getMessage());
            return false;
        }
    }

    public function updateIntegrationSettings($googleAnalytics, $emailApi, $paymentProvider, $paymentApi, $webhookUrl)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE settings 
                SET google_analytics = ?, email_api = ?, payment_provider = ?, payment_api = ?, webhook_url = ? 
                WHERE id = 1");
            return $stmt->execute([$googleAnalytics, $emailApi, $paymentProvider, $paymentApi, $webhookUrl]);
        } catch (\PDOException $e) {
            error_log("Erreur mise à jour intégrations: " . $e->getMessage());
            return false;
        }
    }

    public function getActionHistory()
    {
        try {
            $stmt = $this->pdo->query("
                SELECT action_date, username, action, ip_address 
                FROM user_history 
                ORDER BY action_date DESC 
                LIMIT 50
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erreur récupération historique actions: " . $e->getMessage());
            return [];
        }
    }

    public function logAction($userId, $username, $action, $ipAddress)
    {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO user_history (user_id, username, action, ip_address) 
                VALUES (?, ?, ?, ?)
            ");
            return $stmt->execute([$userId, $username, $action, $ipAddress]);
        } catch (\PDOException $e) {
            error_log("Erreur enregistrement action utilisateur: " . $e->getMessage());
            return false;
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
            $stmt = $this->pdo->prepare("INSERT INTO users (name, email, role) VALUES (?, ?, ?)");
            return $stmt->execute([$name, $email, $role]);
        } catch (\PDOException $e) {
            error_log("Erreur import utilisateur: " . $e->getMessage());
            return false;
        }
    }

    // Mise à jour des statistiques
    public function refreshStatistics()
    {
        try {
            $stmt = $this->pdo->query("
                UPDATE statistics
                SET total_users = (SELECT COUNT(*) FROM users),
                    total_orders = (SELECT COUNT(*) FROM orders),
                    total_revenue = (SELECT SUM(amount) FROM payments)
            ");
            return $stmt->execute();
        } catch (\PDOException $e) {
            error_log("Erreur mise à jour statistiques: " . $e->getMessage());
            return false;
        }
    }

    // Suppression des commandes inactives
    public function deleteInactiveOrders($days)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM orders WHERE status = 'pending' AND created_at < NOW() - INTERVAL ? DAY");
            return $stmt->execute([$days]);
        } catch (\PDOException $e) {
            error_log("Erreur suppression commandes inactives: " . $e->getMessage());
            return false;
        }
    }
}
