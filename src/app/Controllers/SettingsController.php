<?php

namespace Controllers;

use Models\SettingsModel;

class SettingsController
{
    private $settingsModel;

    public function __construct()
    {
        $this->settingsModel = new SettingsModel();
    }

    public function showSettings()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: connexion_shop");
            exit();
        }
        $settings = $this->settingsModel->getSettings($_SESSION['user_id']);
        $history = $this->settingsModel->getActionHistory();
        include 'src/app/Views/admin/admin_settings.php';
    }

    public function updateSettings()
    {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Accès refusé']);
            exit();
        }
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $userId = $_SESSION['user_id'];
            $username = htmlspecialchars($_POST["username"] ?? '');
            $email = filter_var($_POST["email"] ?? '', FILTER_VALIDATE_EMAIL);
            if (!$email) {
                echo json_encode(['success' => false, 'message' => 'Email invalide']);
                exit();
            }
            $settings = $this->settingsModel->getSettings($userId);

            // Vérifier si une tentative de modification du rôle a été faite
            if (isset($_POST['role']) && $_POST['role'] !== $settings['role']) {
                echo json_encode(['success' => false, 'message' => 'Modification du rôle non autorisée']);
                exit();
            }

            // Mettre à jour les paramètres sans permettre la modification du rôle
            $result = $this->settingsModel->updateUserSettings($userId, $username, $email);
            $this->settingsModel->logAction($userId, $username, "Mise à jour compte", $_SERVER['REMOTE_ADDR']);
            echo json_encode($result);
            exit();
        }
    }

    public function updatePassword()
    {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Accès refusé']);
            exit();
        }
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $userId = $_SESSION['user_id'];
            $currentPassword = $_POST["current_password"] ?? '';
            $newPassword = $_POST["new_password"] ?? '';
            if (!$this->settingsModel->verifyPassword($userId, $currentPassword)) {
                echo json_encode(['success' => false, 'message' => 'Mot de passe actuel incorrect']);
                exit();
            }
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $result = $this->settingsModel->updatePassword($userId, $hashedPassword);
            $this->settingsModel->logAction($userId, $_SESSION['username'] ?? 'Utilisateur inconnu', "Mise à jour mot de passe", $_SERVER['REMOTE_ADDR']);
            echo json_encode($result);
            exit();
        }
    }

    public function deleteAccount()
    {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Accès refusé']);
            exit();
        }
        $userId = $_SESSION['user_id'];
        $result = $this->settingsModel->deleteAccount($userId);
        if ($result['success']) {
            session_destroy();
        }
        echo json_encode($result);
        exit();
    }

    public function updateNotifications()
    {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Accès refusé']);
            exit();
        }
        $input = json_decode(file_get_contents("php://input"), true);
        if (!$input) {
            echo json_encode(['success' => false, 'message' => 'Données invalides']);
            exit();
        }
        $userId = $_SESSION['user_id'];
        $result = $this->settingsModel->updateNotificationSettings(
            $userId,
            $input["notifyMessages"] ?? false,
            $input["notifyOrders"] ?? false,
            $input["notifyReservations"] ?? false,
            $input["notifyPackReservations"] ?? false,
            $input["notifyProductsSoldRented"] ?? false,
            $input["siteNotifications"] ?? false,
            $input["emailFrequency"] ?? 'immediate'
        );
        $this->settingsModel->logAction($userId, $_SESSION['username'] ?? 'Utilisateur inconnu', "Mise à jour notifications", $_SERVER['REMOTE_ADDR']);
        echo json_encode($result);
        exit();
    }

    public function getActionHistory()
    {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Accès refusé']);
            exit();
        }
        $history = $this->settingsModel->getActionHistory();
        echo json_encode($history);
        exit();
    }

    public function exportUsersCSV()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: site_stage/chic-and-chill/login");
            exit();
        }
        $users = $this->settingsModel->getAllUsers();
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="users_' . date('Y-m-d_H-i-s') . '.csv"');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Nom', 'Email', 'Rôle']);
        foreach ($users as $user) {
            fputcsv($output, [$user['id'], $user['name'], $user['email'], $user['role']]);
        }
        fclose($output);
        $this->settingsModel->logAction($_SESSION['user_id'], $_SESSION['username'] ?? 'Utilisateur inconnu', "Export utilisateurs CSV", $_SERVER['REMOTE_ADDR']);
        exit();
    }

    public function exportProductsCSV()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: site_stage/chic-and-chill/login");
            exit();
        }
        $products = $this->settingsModel->getAllProducts();
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="products_' . date('Y-m-d_H-i-s') . '.csv"');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Nom', 'Prix', 'Stock']);
        foreach ($products as $product) {
            fputcsv($output, [$product['id'], $product['name'], $product['price'], $product['stock']]);
        }
        fclose($output);
        $this->settingsModel->logAction($_SESSION['user_id'], $_SESSION['username'] ?? 'Utilisateur inconnu', "Export produits CSV", $_SERVER['REMOTE_ADDR']);
        exit();
    }

    public function importCSV()
    {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Accès refusé']);
            exit();
        }
        if ($_FILES['importFile']['error'] == UPLOAD_ERR_OK) {
            $file = fopen($_FILES['importFile']['tmp_name'], 'r');
            fgetcsv($file); // Ignorer en-têtes
            $successCount = 0;
            while (($data = fgetcsv($file)) !== false) {
                if (count($data) >= 3) {
                    $result = $this->settingsModel->insertImportedUser($data[1], $data[2], $data[3]);
                    if ($result['success']) $successCount++;
                }
            }
            fclose($file);
            $this->settingsModel->logAction($_SESSION['user_id'], $_SESSION['username'] ?? 'Utilisateur inconnu', "Import utilisateurs ($successCount réussis)", $_SERVER['REMOTE_ADDR']);
            echo json_encode(['success' => true, 'message' => "$successCount utilisateurs importés avec succès"]);
            exit();
        }
        echo json_encode(['success' => false, 'message' => 'Erreur lors du téléchargement du fichier']);
        exit();
    }

    public function backupDatabase()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: site_stage/chic-and-chill/login");
            exit();
        }
        $backupDir = __DIR__ . '/../../backups/';
        if (!is_dir($backupDir)) mkdir($backupDir, 0777, true);
        $backupFile = $backupDir . 'db_backup_' . date("Y-m-d_H-i-s") . '.sql';
        $dbName = 'votre_base_de_donnees';
        $dbUser = 'votre_utilisateur';
        $dbPass = 'votre_mot_de_passe';
        $command = "mysqldump -u $dbUser -p$dbPass $dbName > " . escapeshellarg($backupFile);
        exec($command, $output, $returnVar);
        if ($returnVar === 0) {
            $this->settingsModel->logAction($_SESSION['user_id'], $_SESSION['username'] ?? 'Utilisateur inconnu', "Sauvegarde BDD", $_SERVER['REMOTE_ADDR']);
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($backupFile) . '"');
            readfile($backupFile);
            unlink($backupFile);
            exit();
        } else {
            error_log("Erreur sauvegarde BDD: " . implode("\n", $output));
            header("Location: site_stage/chic-and-chill/admin/settings?backup_error=1");
            exit();
        }
    }

    public function restoreDatabase()
    {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Accès refusé']);
            exit();
        }
        if ($_FILES['backupFile']['error'] == UPLOAD_ERR_OK) {
            $restoreFile = $_FILES['backupFile']['tmp_name'];
            $dbName = 'votre_base_de_donnees';
            $dbUser = 'votre_utilisateur';
            $dbPass = 'votre_mot_de_passe';
            $command = "mysql -u $dbUser -p$dbPass $dbName < " . escapeshellarg($restoreFile);
            exec($command, $output, $returnVar);
            if ($returnVar === 0) {
                $this->settingsModel->logAction($_SESSION['user_id'], $_SESSION['username'] ?? 'Utilisateur inconnu', "Restauration BDD", $_SERVER['REMOTE_ADDR']);
                echo json_encode(['success' => true, 'message' => 'Restauration réussie']);
            } else {
                error_log("Erreur restauration BDD: " . implode("\n", $output));
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la restauration']);
            }
            exit();
        }
        echo json_encode(['success' => false, 'message' => 'Erreur lors du téléchargement du fichier']);
        exit();
    }
}
