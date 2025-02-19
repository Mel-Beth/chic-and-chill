<?php

namespace Controllers;

use Models\UserModel;
use PDOException;

class InscriptionControllersShop
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function registerUserShop()
    {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupération des données du formulaire
            $name = htmlspecialchars(trim($_POST['name']));
            $surname = htmlspecialchars(trim($_POST['surname']));
            $adresse = htmlspecialchars(trim($_POST['adresse']));
            $number_phone = htmlspecialchars(trim($_POST['number_phone']));
            $email = htmlspecialchars(trim($_POST['email']));
            $password = htmlspecialchars(trim($_POST['password']));
            $confirm_password = htmlspecialchars(trim($_POST['confirm_password']));

            // Vérifications des champs
            if (empty($name) || empty($surname) || empty($adresse) || empty($number_phone) || empty($email) || empty($password) || empty($confirm_password)) {
                $_SESSION['error'] = "Tous les champs doivent être remplis.";
                header('Location: /site_stage/chic-and-chill/inscription_shop');
                exit();
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "L'adresse email est invalide.";
                header('Location: /site_stage/chic-and-chill/inscription_shop');
                exit();
            }

            if ($password !== $confirm_password) {
                $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
                header('Location: /site_stage/chic-and-chill/inscription_shop');
                exit();
            }

            try {
                // Appel du modèle pour enregistrer l'utilisateur
                $result = $this->userModel->registerUser($name, $surname, $adresse, $number_phone, $email, $password);

                if ($result === true) {
                    $_SESSION['message'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
                    header('Location: /site_stage/chic-and-chill/connexion_shop');
                    exit();
                } else {
                    $_SESSION['error'] = $result;
                    header('Location: /site_stage/chic-and-chill/inscription_shop');
                    exit();
                }
            } catch (PDOException $e) {
                $_SESSION['error'] = "Erreur lors de l'inscription : " . $e->getMessage();
                header('Location: /site_stage/chic-and-chill/inscription_shop');
                exit();
            }
        }
    }
}
