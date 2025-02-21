<?php

namespace Controllers;

use Models\UserInfosModelShop;
use PDOException;
require_once 'src/app/Models/UserInfosModelShop.php';

class ProfilControllersShop
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserInfosModelShop();
    }

    public function showUserProfile()
    {
        

        // Vérifie si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: /site_stage/chic-and-chill/connexion_shop');
            exit;
        }

        try {
            // Récupération des informations utilisateur
            $user = $this->userModel->getUserById($_SESSION['user_id']);

            if (!$user) {
                $_SESSION['error'] = "Utilisateur introuvable.";
                header('Location: /site_stage/chic-and-chill/connexion_shop');
                exit;
            }

            // Stockage des infos utilisateur dans la session
            $_SESSION['user_identifiant'] = $user['identifiant'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_surname'] = $user['surname'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_adresse'] = $user['adresse'];
            $_SESSION['user_number_phone'] = $user['number_phone'];
            $_SESSION['user_role'] = $user['role'];

            // Chargement de la vue du profil
            include 'src/app/Views/Public/profil_user_shop.php';

        } catch (PDOException $e) {
            $_SESSION['error'] = "Erreur lors du chargement du profil : " . $e->getMessage();
            header('Location: /site_stage/chic-and-chill/connexion_shop');
            exit;
        }
    }
}
?>