<?php

namespace Controllers;

use Models\CoModelShop;

require_once 'src/app/Models/CoModelShop.php';

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

            // if (isset($_SESSION['user_id'])) {
            //     header("Location: profil_user_shop");
            //     exit;
            // } else {

                // Récupération des données du formulaire
                $identifier = isset($_POST['identifier']) ? trim($_POST['identifier']) : null;
                $password = isset($_POST['password']) ? trim($_POST['password']) : null;

<<<<<<< HEAD
            try {
                // Appel au modèle pour récupérer l'utilisateur
                $user = $this->coModel->getUserByIdentifierOrEmail($identifier);
                // var_dump($user); die(); // DEBUG : Voir ce que retourne la base de données
         
                
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
                    if ($user['role'] == 'client') {
                        $_SESSION['message'] = "Bonjour, " . htmlspecialchars($user['name']) . "!";
                        header("Location: /site_stage/chic-and-chill/accueil_shop");
                    } elseif ($user['role'] == 'admin') {
                        $_SESSION['message'] = "Bonjour, " . htmlspecialchars($user['name']) . "!";
                        $_SESSION['admin_dashboard'] = true;
                        header("Location: /site_stage/chic-and-chill/admin/dashboard");
                    }
                    exit;
                } else {
                    // Gestion des identifiants incorrects
                    $_SESSION['error'] = "Identifiant ou mot de passe incorrect.";
=======
                // Vérification des champs vides
                if (empty($identifier) || empty($password)) {
                    $_SESSION['error'] = "Veuillez remplir tous les champs.";
                    header("Location: connexion_shop");
                    exit;
                }

                try {
                    // Appel au modèle pour récupérer l'utilisateur
                    $user = $this->coModel->getUserByIdentifierOrEmail($identifier);
                    // var_dump($user); die(); // DEBUG : Voir ce que retourne la base de données


                    // Vérification du mot de passe
                    if ($user && password_verify($password, $user['password'])) {
                    //     var_dump($user['role']);
                    // die();
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
                        if ($user['role'] == 'client') {
                            $_SESSION['message'] = "Bonjour, " . htmlspecialchars($user['name']) . "!";
                            header("Location: accueil_shop");
                        } elseif ($user['role'] == 'admin') {
                            header("Location: admin/dashboard");
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
>>>>>>> ed44300d624c33647369b42203ed15526d849112
                    header("Location: connexion_shop");
                    exit;
                }
            }
        }
    // }
}
