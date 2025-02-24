<?php

namespace Controllers;

use Models\UsersModel;
use Models\SettingsModel;

class UsersController
{
    private $usersModel;
    private $settingsModel;

    public function __construct()
    {
        $this->usersModel = new UsersModel();
        $this->settingsModel = new SettingsModel();
    }

    public function users()
    {
        $users = $this->usersModel->getAllUsers();
        include('src/app/Views/Admin/admin_users.php');
    }

    public function showUser($id)
    {
        try {
            $usersModel = new UsersModel();
            $user = $usersModel->getUserById($id);

            if (!$user) {
                include('src/app/Views/404.php');
                exit();
            }

            include('src/app/Views/Public/user_detail.php');
        } catch (\Exception $e) {
            error_log($e->getMessage());
            echo "Une erreur est survenue. Veuillez réessayer plus tard.";
        }
    }

    public function manageUsers()
    {
        include('src/app/Views/Admin/admin_users.php');
    }

    public function updateUserStatus($id, $status)
    {
        $status = ($status === 'active') ? 'active' : 'inactive';
        $success = $this->usersModel->updateStatus($id, $status);

        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
        exit();
    }

    public function deleteUser($id)
    {
        $success = $this->usersModel->deleteUser($id);
        $success = $this->settingsModel->logAction($_SESSION['user_id'], $_SESSION['username'], "Suppression de l'utilisateur ID: $id", $_SERVER['REMOTE_ADDR']);

        if ($success) {
            header("Location: ../?success=1&action=delete");
            exit();
        } else {
            header("Location: ../?success=0&action=delete");
            exit();
        }
    }

    public function viewUserHistory($id)
    {
        $usersModel = new UsersModel();
        $history = $usersModel->getUserFullHistory($id);
        $user = $usersModel->getUserById($id);

        if (!$user) {
            include('src/app/Views/404.php');
            exit();
        }

        include('src/app/Views/Admin/admin_user_history.php');
    }
}
