<?php

namespace Controllers;

use Models\SettingsModel;
use Models\UsersModel;

class SettingsController
{
    private $settingsModel;
    private $userModel;

    public function __construct()
    {
        $this->settingsModel = new SettingsModel();
        $this->userModel = new UsersModel();
    }

    public function showSettings()
    {

        if (!isset($_SESSION['user'])) {
            header("Location: login");
            exit();
        }

        $settings = $this->settingsModel->getSettings($_SESSION['user']);
        $history = $this->settingsModel->getActionHistory();

        include 'src/app/Views/Admin/admin_settings.php';
    }

    // Mise à jour des infos du compte
    public function updateSettings()
    {

        if (!isset($_SESSION['user'])) {
            die("Accès refusé.");
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $userId = $_SESSION['user'];
            $username = htmlspecialchars($_POST["username"]);
            $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);

            if (!$email) {
                die("Adresse e-mail invalide.");
            }

            if ($this->settingsModel->updateUserSettings($userId, $username, $email)) {
                header("Location: admin/settings?success=1");
                exit();
            } else {
                die("Erreur lors de la mise à jour.");
            }
        }
    }

    public function updateAppearanceSettings()
    {

        if (!isset($_SESSION['user'])) {
            die("Accès refusé.");
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $userId = $_SESSION['user'];
            $darkMode = $_POST["darkMode"] ?? "disabled";
            $themeColor = $_POST["themeColor"] ?? "blue";
            $fontFamily = $_POST["fontFamily"] ?? "sans-serif";
            $fontSize = $_POST["fontSize"] ?? "normal";
            $showTraffic = $_POST["showTraffic"] ?? "visible";
            $showSales = $_POST["showSales"] ?? "visible";
            $showOrders = $_POST["showOrders"] ?? "visible";

            $this->settingsModel->updateAppearance($userId, $darkMode, $themeColor, $fontFamily, $fontSize, $showTraffic, $showSales, $showOrders);

            header("Location: admin/settings?appearance_updated=1");
            exit();
        }
    }

    public function updatePassword()
    {

        if (!isset($_SESSION['user'])) {
            die("Accès refusé.");
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $userId = $_SESSION['user'];
            $currentPassword = $_POST["current_password"];
            $newPassword = $_POST["new_password"];
            $confirmPassword = $_POST["confirm_password"];

            if ($newPassword !== $confirmPassword) {
                die("Les mots de passe ne correspondent pas.");
            }

            if (!$this->settingsModel->verifyPassword($userId, $currentPassword)) {
                die("Mot de passe actuel incorrect.");
            }

            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $this->settingsModel->updatePassword($userId, $hashedPassword);

            header("Location: admin/settings?password_updated=1");
            exit();
        }
    }

    public function deleteAccount()
    {

        if (!isset($_SESSION['user'])) {
            die("Accès refusé.");
        }

        $userId = $_SESSION['user'];
        $this->settingsModel->deleteAccount($userId);

        session_destroy();
        header("Location: accueil");
        exit();
    }

    public function updateNotifications()
    {

        if (!isset($_SESSION['user'])) {
            die("Accès refusé.");
        }

        $input = json_decode(file_get_contents("php://input"), true);

        if ($input) {
            $userId = $_SESSION['user'];

            $this->settingsModel->updateNotificationSettings(
                $userId,
                $input["notifyMessages"],
                $input["notifyOrders"],
                $input["notifyReservations"],
                $input["notifyPackReservations"],
                $input["notifyProductsSoldRented"],
                $input["siteNotifications"],
                $input["emailFrequency"]
            );

            echo json_encode(["success" => true]);
            exit();
        }

        echo json_encode(["success" => false]);
    }

    public function viewInvoices()
    {

        if (!isset($_SESSION['user'])) {
            die("Accès refusé.");
        }

        $userId = $_SESSION['user'];
        $invoices = $this->settingsModel->getInvoices($userId);
        include 'src/app/Views/Admin/admin_invoices.php';
    }

    public function cancelSubscription()
    {

        if (!isset($_SESSION['user'])) {
            echo json_encode(["success" => false, "message" => "Accès refusé."]);
            exit();
        }

        $userId = $_SESSION['user'];
        $success = $this->settingsModel->cancelUserSubscription($userId);

        echo json_encode(["success" => $success]);
        exit();
    }

    public function applyPromo()
    {

        if (!isset($_SESSION['user'])) {
            echo json_encode(["success" => false, "message" => "Accès refusé."]);
            exit();
        }

        $input = json_decode(file_get_contents("php://input"), true);
        if (!isset($input["promoCode"])) {
            echo json_encode(["success" => false, "message" => "Code promo manquant."]);
            exit();
        }

        $userId = $_SESSION['user'];
        $success = $this->settingsModel->applyPromoCode($userId, $input["promoCode"]);

        echo json_encode(["success" => $success]);
        exit();
    }

    public function updateLanguageSettings()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $language = htmlspecialchars($_POST["language"]);
            $timezone = htmlspecialchars($_POST["timezone"]);
            $country = htmlspecialchars($_POST["country"]);

            $this->settingsModel->updateLanguageSettings($language, $timezone, $country);
            header("Location: admin/settings?success=1");
            exit();
        }
    }

    public function updateIntegrationSettings()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $googleAnalytics = htmlspecialchars($_POST["google_analytics"]);
            $emailApi = htmlspecialchars($_POST["email_api"]);
            $paymentProvider = htmlspecialchars($_POST["payment_provider"]);
            $paymentApi = htmlspecialchars($_POST["payment_api"]);
            $webhookUrl = htmlspecialchars($_POST["webhook_url"]);

            $this->settingsModel->updateIntegrationSettings($googleAnalytics, $emailApi, $paymentProvider, $paymentApi, $webhookUrl);
            header("Location: admin/settings?success=1");
            exit();
        }
    }

    public function getActionHistory()
    {
        $history = $this->settingsModel->getActionHistory();
        echo json_encode($history);
    }

    public function logAction($action)
    {
        if (!isset($_SESSION['user'])) {
            return;
        }

        $userId = $_SESSION['user'];
        $username = $_SESSION['username'];
        $ipAddress = $_SERVER['REMOTE_ADDR'];

        $this->settingsModel->logAction($userId, $username, $action, $ipAddress);
    }

    public function exportUsersCSV()
    {
        $users = $this->settingsModel->getAllUsers();
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="users.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Nom', 'Email', 'Rôle']);

        foreach ($users as $user) {
            fputcsv($output, [$user['id'], $user['name'], $user['email'], $user['role']]);
        }

        fclose($output);
        exit();
    }

    public function exportProductsCSV()
    {
        $products = $this->settingsModel->getAllProducts();
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="products.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Nom', 'Prix', 'Stock']);

        foreach ($products as $product) {
            fputcsv($output, [$product['id'], $product['name'], $product['price'], $product['stock']]);
        }

        fclose($output);
        exit();
    }

    public function importCSV()
    {
        if ($_FILES['importFile']['error'] == 0) {
            $file = fopen($_FILES['importFile']['tmp_name'], 'r');
            fgetcsv($file); // Ignorer la première ligne (en-têtes)

            while (($data = fgetcsv($file)) !== false) {
                $this->settingsModel->insertImportedUser($data[1], $data[2], $data[3]); // Nom, Email, Rôle
            }

            fclose($file);
            header("Location: admin/settings?import_success=1");
            exit();
        }
    }

    public function backupDatabase()
    {
        $backupFile = 'backups/db_backup_' . date("Y-m-d_H-i-s") . '.sql';
        $command = "mysqldump -u root -p database_name > $backupFile";

        system($command);
        header("Location: admin/settings?backup_success=1");
        exit();
    }

    public function restoreDatabase()
    {
        if ($_FILES['backupFile']['error'] == 0) {
            $restoreFile = $_FILES['backupFile']['tmp_name'];
            $command = "mysql -u root -p database_name < $restoreFile";

            system($command);
            header("Location: admin/settings?restore_success=1");
            exit();
        }
    }

    // Réinitialisation du cache
    public function resetCache()
    {
        if (!isset($_SESSION['user'])) {
            die("Accès refusé.");
        }

        // Effacement des fichiers cache
        array_map('unlink', glob("cache/*.cache"));

        echo json_encode(["success" => true]);
        exit();
    }

    // Mise à jour des statistiques
    public function updateStats()
    {
        if (!isset($_SESSION['user'])) {
            die("Accès refusé.");
        }

        $success = $this->settingsModel->refreshStatistics();

        echo json_encode(["success" => $success]);
        exit();
    }

    // Nettoyage des commandes inactives
    public function cleanOldOrders()
    {
        if (!isset($_SESSION['user'])) {
            die("Accès refusé.");
        }

        $days = 7; // Supprimer les commandes non payées après 7 jours
        $success = $this->settingsModel->deleteInactiveOrders($days);

        echo json_encode(["success" => $success]);
        exit();
    }
}
