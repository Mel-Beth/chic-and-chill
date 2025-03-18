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

    public function showUserInfos()
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: /site_stage/chic-and-chill/connexion_shop');
        exit;
    }

    try {
        // Récupérer les infos de l'utilisateur
        $user = $this->userModel->getUserById($_SESSION['user_id']);

        if (!$user) {
            $_SESSION['error'] = "Utilisateur introuvable.";
            header('Location: /site_stage/chic-and-chill/connexion_shop');
            exit;
        }

        include 'src/app/Views/Public/profil_user_shop.php'; // Assure-toi que ce fichier existe bien
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur lors du chargement des infos : " . $e->getMessage();
        header('Location: /site_stage/chic-and-chill/connexion_shop');
        exit;
    }
}

    
    public function updateUserInfos()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_SESSION['user_id'];
            $name = htmlspecialchars(trim($_POST['name']));
$surname = htmlspecialchars(trim($_POST['surname']));
$email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
$adresse = htmlspecialchars(trim($_POST['adresse']));
$number_phone = htmlspecialchars(trim($_POST['number_phone']));

            try {
                $this->userModel->updateUser($id, $name, $surname, $email, $adresse, $number_phone);
                $_SESSION['message'] = "Informations mises à jour avec succès.";
            } catch (PDOException $e) {
                $_SESSION['error'] = "Erreur lors de la mise à jour : " . $e->getMessage();
            }

            header('Location: profil_user_shop?user_infos_shop');
            exit;
        }
    }
}
?>