<?php

namespace Controllers;

use Models\UsersModel;

class UsersController
{
    public function users()
    {
        $usersModel = new UsersModel();
        $users = $usersModel->getAllUsers();
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

    public function addUser()
    {
        include('src/app/Views/Admin/admin_users.php');
    }

    public function updateUser($id)
    {
        include('src/app/Views/Admin/admin_users.php');
    }

    public function deleteUser($id)
    {
        $usersModel = new UsersModel();
        $usersModel->deleteUser($id);
        header("Location: admin/users");
        exit();
    }
}
