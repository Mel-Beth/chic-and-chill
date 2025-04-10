<?php

namespace Controllers;

use Models\UsersModel;
use Models\SettingsModel;

class UsersController
{
    private $usersModel;
    private $settingsModel;

    // Constructeur : Initialise les modèles nécessaires pour gérer les utilisateurs
    public function __construct()
    {
        $this->usersModel = new UsersModel();    // Modèle pour gérer les utilisateurs
        $this->settingsModel = new SettingsModel(); // Modèle pour gérer les paramètres et logs
    }

    // Affiche la liste de tous les utilisateurs pour l'administration
    public function users()
    {
        $users = $this->usersModel->getAllUsers(); // Récupère tous les utilisateurs
        include('src/app/Views/admin/admin_users.php'); // Inclut la vue d'administration
    }

    // Affiche les détails d'un utilisateur spécifique en fonction de son ID
    public function showUser($id)
    {
        try {
            $usersModel = new UsersModel();
            $user = $usersModel->getUserById($id); // Récupère l'utilisateur par son ID

            // Si l'utilisateur n'existe pas, affiche une erreur 404
            if (!$user) {
                $code_erreur = 404;
                $description_erreur = "Oups... L'utilisateur que vous cherchez n'existe pas.";
                include('src/app/Views/erreur.php');
                exit();
            }

            include('src/app/Views/Public/user_detail.php'); // Inclut la vue publique des détails
        } catch (\Exception $e) {
            // Log l'erreur et affiche un message générique en cas d'exception
            error_log($e->getMessage());
            echo "Une erreur est survenue. Veuillez réessayer plus tard.";
        }
    }

    // Affiche la page de gestion des utilisateurs (similaire à users(), mais séparée)
    public function manageUsers()
    {
        include('src/app/Views/admin/admin_users.php'); // Inclut la vue d'administration
    }

    // Met à jour le statut d'un utilisateur (actif/inactif) via une requête AJAX
    public function updateUserStatus($id, $status)
    {
        $status = ($status === 'active') ? 'active' : 'inactive'; // Assure que le statut est valide
        $success = $this->usersModel->updateStatus($id, $status); // Met à jour le statut via le modèle

        header('Content-Type: application/json'); // Définit le type de contenu comme JSON
        echo json_encode(['success' => $success]); // Retourne le résultat en JSON
        exit(); // Termine l'exécution
    }

    // Supprime un utilisateur spécifique en fonction de son ID
    public function deleteUser($id)
    {
        $success = $this->usersModel->deleteUser($id); // Supprime l'utilisateur via le modèle
        $success = $this->settingsModel->logAction(
            $_SESSION['user_id'], 
            $_SESSION['username'], 
            "Suppression de l'utilisateur ID: $id", 
            $_SERVER['REMOTE_ADDR']
        ); // Log l'action dans l'historique

        // Redirige avec un message de succès ou d'échec
        if ($success) {
            header("Location: ../?success=1&action=delete");
            exit();
        } else {
            header("Location: ../?success=0&action=delete");
            exit();
        }
    }

    // Affiche l'historique complet d'un utilisateur spécifique
    public function viewUserHistory($id)
    {
        $usersModel = new UsersModel();
        $history = $usersModel->getUserFullHistory($id); // Récupère l'historique de l'utilisateur
        $user = $usersModel->getUserById($id); // Récupère les détails de l'utilisateur

        // Si l'utilisateur n'existe pas, affiche une erreur 404
        if (!$user) {
            $code_erreur = 404;
            $description_erreur = "Oups... L'utilisateur que vous cherchez n'existe pas.";
            include('src/app/Views/erreur.php');
            exit();
        }

        include('src/app/Views/admin/admin_user_history.php'); // Inclut la vue de l'historique
    }
}
?>