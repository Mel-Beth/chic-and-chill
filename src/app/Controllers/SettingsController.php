<?php

namespace Controllers;

use Models\SettingsModel;

class SettingsController
{
    private $settingsModel;

    // Constructeur : Initialise le modèle pour gérer les paramètres
    public function __construct()
    {
        $this->settingsModel = new SettingsModel(); // Modèle pour gérer les paramètres et logs
    }

    // Affiche la page des paramètres avec les données de l'utilisateur et l'historique
    public function showSettings()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: connexion_shop"); // Redirige si non connecté
            exit();
        }
        $settings = $this->settingsModel->getSettings($_SESSION['user_id']); // Récupère les paramètres de l'utilisateur
        $history = $this->settingsModel->getActionHistory(); // Récupère l'historique des actions
        include 'src/app/Views/admin/admin_settings.php'; // Inclut la vue des paramètres
    }

    // Met à jour les paramètres de l'utilisateur (nom et email) via une requête POST
    public function updateSettings()
    {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Accès refusé']); // Vérifie la connexion
            exit();
        }
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $userId = $_SESSION['user_id'];
            $username = htmlspecialchars($_POST["username"] ?? ''); // Nettoie le nom d'utilisateur
            $email = filter_var($_POST["email"] ?? '', FILTER_VALIDATE_EMAIL); // Valide l'email
            if (!$email) {
                echo json_encode(['success' => false, 'message' => 'Email invalide']); // Erreur si email invalide
                exit();
            }
            $settings = $this->settingsModel->getSettings($userId); // Récupère les paramètres actuels

            // Empêche la modification du rôle
            if (isset($_POST['role']) && $_POST['role'] !== $settings['role']) {
                echo json_encode(['success' => false, 'message' => 'Modification du rôle non autorisée']);
                exit();
            }

            // Met à jour les paramètres et log l'action
            $result = $this->settingsModel->updateUserSettings($userId, $username, $email);
            $this->settingsModel->logAction($userId, $username, "Mise à jour compte", $_SERVER['REMOTE_ADDR']);
            echo json_encode($result); // Retourne le résultat en JSON
            exit();
        }
    }

    // Met à jour le mot de passe de l'utilisateur via une requête POST
    public function updatePassword()
    {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Accès refusé']); // Vérifie la connexion
            exit();
        }
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $userId = $_SESSION['user_id'];
            $currentPassword = $_POST["current_password"] ?? ''; // Mot de passe actuel
            $newPassword = $_POST["new_password"] ?? ''; // Nouveau mot de passe
            if (!$this->settingsModel->verifyPassword($userId, $currentPassword)) {
                echo json_encode(['success' => false, 'message' => 'Mot de passe actuel incorrect']); // Vérifie le mot de passe actuel
                exit();
            }
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT); // Hache le nouveau mot de passe
            $result = $this->settingsModel->updatePassword($userId, $hashedPassword); // Met à jour le mot de passe
            $this->settingsModel->logAction($userId, $_SESSION['username'] ?? 'Utilisateur inconnu', "Mise à jour mot de passe", $_SERVER['REMOTE_ADDR']);
            echo json_encode($result); // Retourne le résultat en JSON
            exit();
        }
    }

    // Supprime le compte de l'utilisateur connecté
    public function deleteAccount()
    {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Accès refusé']); // Vérifie la connexion
            exit();
        }
        $userId = $_SESSION['user_id'];
        $result = $this->settingsModel->deleteAccount($userId); // Supprime le compte
        if ($result['success']) {
            session_destroy(); // Détruit la session si succès
        }
        echo json_encode($result); // Retourne le résultat en JSON
        exit();
    }

    // Met à jour les préférences de notifications via une requête JSON
    public function updateNotifications()
    {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Accès refusé']); // Vérifie la connexion
            exit();
        }
        $input = json_decode(file_get_contents("php://input"), true); // Récupère les données JSON
        if (!$input) {
            echo json_encode(['success' => false, 'message' => 'Données invalides']); // Erreur si données invalides
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
        ); // Met à jour les préférences de notifications
        $this->settingsModel->logAction($userId, $_SESSION['username'] ?? 'Utilisateur inconnu', "Mise à jour notifications", $_SERVER['REMOTE_ADDR']);
        echo json_encode($result); // Retourne le résultat en JSON
        exit();
    }

    // Retourne l'historique des actions au format JSON
    public function getActionHistory()
    {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Accès refusé']); // Vérifie la connexion
            exit();
        }
        $history = $this->settingsModel->getActionHistory(); // Récupère l'historique
        echo json_encode($history); // Retourne l'historique en JSON
        exit();
    }

    // Exporte la liste des utilisateurs au format CSV
    public function exportUsersCSV()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: site_stage/chic-and-chill/login"); // Redirige si non connecté
            exit();
        }
        $users = $this->settingsModel->getAllUsers(); // Récupère tous les utilisateurs
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="users_' . date('Y-m-d_H-i-s') . '.csv"'); // Définit le fichier CSV
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Nom', 'Email', 'Rôle']); // En-tête du CSV
        foreach ($users as $user) {
            fputcsv($output, [$user['id'], $user['name'], $user['email'], $user['role']]); // Ajoute chaque utilisateur
        }
        fclose($output);
        $this->settingsModel->logAction($_SESSION['user_id'], $_SESSION['username'] ?? 'Utilisateur inconnu', "Export utilisateurs CSV", $_SERVER['REMOTE_ADDR']);
        exit();
    }

    // Exporte la liste des produits au format CSV
    public function exportProductsCSV()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: site_stage/chic-and-chill/login"); // Redirige si non connecté
            exit();
        }
        $products = $this->settingsModel->getAllProducts(); // Récupère tous les produits
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="products_' . date('Y-m-d_H-i-s') . '.csv"'); // Définit le fichier CSV
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Nom', 'Prix', 'Stock']); // En-tête du CSV
        foreach ($products as $product) {
            fputcsv($output, [$product['id'], $product['name'], $product['price'], $product['stock']]); // Ajoute chaque produit
        }
        fclose($output);
        $this->settingsModel->logAction($_SESSION['user_id'], $_SESSION['username'] ?? 'Utilisateur inconnu', "Export produits CSV", $_SERVER['REMOTE_ADDR']);
        exit();
    }

    // Importe des utilisateurs depuis un fichier CSV
    public function importCSV()
    {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Accès refusé']); // Vérifie la connexion
            exit();
        }
        if ($_FILES['importFile']['error'] == UPLOAD_ERR_OK) {
            $file = fopen($_FILES['importFile']['tmp_name'], 'r');
            fgetcsv($file); // Ignore la ligne d'en-tête
            $successCount = 0;
            while (($data = fgetcsv($file)) !== false) {
                if (count($data) >= 3) {
                    $result = $this->settingsModel->insertImportedUser($data[1], $data[2], $data[3]); // Insère chaque utilisateur
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

    // Sauvegarde la base de données dans un fichier SQL
    public function backupDatabase()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: site_stage/chic-and-chill/login"); // Redirige si non connecté
            exit();
        }
        $backupDir = __DIR__ . '/../../backups/';
        if (!is_dir($backupDir)) mkdir($backupDir, 0777, true); // Crée le dossier si inexistant
        $backupFile = $backupDir . 'db_backup_' . date("Y-m-d_H-i-s") . '.sql'; // Nom du fichier
        $dbName = 'votre_base_de_donnees'; // À remplacer par le vrai nom
        $dbUser = 'votre_utilisateur'; // À remplacer
        $dbPass = 'votre_mot_de_passe'; // À remplacer
        $command = "mysqldump -u $dbUser -p$dbPass $dbName > " . escapeshellarg($backupFile); // Commande mysqldump
        exec($command, $output, $returnVar);
        if ($returnVar === 0) {
            $this->settingsModel->logAction($_SESSION['user_id'], $_SESSION['username'] ?? 'Utilisateur inconnu', "Sauvegarde BDD", $_SERVER['REMOTE_ADDR']);
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($backupFile) . '"'); // Télécharge le fichier
            readfile($backupFile);
            unlink($backupFile); // Supprime le fichier après téléchargement
            exit();
        } else {
            error_log("Erreur sauvegarde BDD: " . implode("\n", $output));
            header("Location: site_stage/chic-and-chill/admin/settings?backup_error=1");
            exit();
        }
    }

    // Restaure la base de données à partir d'un fichier SQL
    public function restoreDatabase()
    {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Accès refusé']); // Vérifie la connexion
            exit();
        }
        if ($_FILES['backupFile']['error'] == UPLOAD_ERR_OK) {
            $restoreFile = $_FILES['backupFile']['tmp_name'];
            $dbName = 'votre_base_de_donnees'; // À remplacer
            $dbUser = 'votre_utilisateur'; // À remplacer
            $dbPass = 'votre_mot_de_passe'; // À remplacer
            $command = "mysql -u $dbUser -p$dbPass $dbName < " . escapeshellarg($restoreFile); // Commande mysql
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
?>