<?php

namespace Controllers;

use Models\CoModelShop;

class ConnexionControllersShop
{
    private $coModel;

    public function __construct()
    {
        $this->coModel = new CoModelShop();
    }

    public function loginUserShop()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Récupération des données du formulaire
            $identifier = isset($_POST['identifier']) ? trim($_POST['identifier']) : null;
            $password = isset($_POST['password']) ? trim($_POST['password']) : null;

            // Vérification des champs vides
            if (empty($identifier) || empty($password)) {
                $_SESSION['error'] = "Veuillez remplir tous les champs.";
                header("Location: connexion_shop");
                exit;
            }

            try {
                // Appel au modèle pour récupérer l'utilisateur
                $user = $this->coModel->getUserByIdentifierOrEmail($identifier);

                // Vérification du mot de passe
                if ($user && password_verify($password, $user['password'])) {
                    // Initialisation des données utilisateur dans la session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_identifiant'] = $user['identifiant'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_surname'] = $user['surname'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['user_adresse'] = $user['adresse'];
                    $_SESSION['user_number_phone'] = $user['number_phone'];
                    
                    // Redirection selon le rôle
                    $_SESSION['message'] = "Bonjour, " . htmlspecialchars($user['name']) . "!";

                    if ($user['role'] == 'client') {
                        header("Location: /site_stage/chic-and-chill/accueil_shop");
                    } elseif ($user['role'] == 'admin') {
                        $_SESSION['admin_dashboard'] = true;
                        header("Location: /site_stage/chic-and-chill/admin/dashboard");
                    }
                    exit;
                } else {
                    // Gestion des identifiants incorrects
                    $_SESSION['error'] = "Identifiant ou mot de passe incorrect.";
                    header("Location: connexion_shop");
                    exit;
                }
            } catch (\PDOException $e) {
                // Gestion des erreurs de connexion à la base de données
                $_SESSION['error'] = "Erreur de connexion à la base de données : " . $e->getMessage();
                header("Location: connexion_shop");
                exit;
            }
        }
    }
}
