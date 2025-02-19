<?php

namespace Controllers;

use PDO;
use PDOException;
require_once 'src/app/Controllers/DatabaseShop.php'; // Connexion à la BDD

class ConnexionControllersShop
{
    public function loginUserShop()
    {
        include 'src/app/Views/Public/connexion_shop.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo('ok step 1 ');
            // Récupération des données du formulaire
            $pseudo = isset($_POST['pseudo']) ? trim($_POST['pseudo']) : null;
            $mot_de_passe = isset($_POST['mot_de_passe']) ? trim($_POST['mot_de_passe']) : null;

            // Vérification des champs vides
            if (empty($pseudo) || empty($mot_de_passe)) {
                $_SESSION['error'] = "Veuillez remplir tous les champs.";
                header("Location: /src/app/Views/Public/connexion_shop.php");
                exit;
            }

            try {
                $pdo = DatabaseShop::getConnection(); // Connexion à la base de données

                // Requête SQL pour récupérer l'utilisateur
                $stmt = $pdo->prepare("
                    SELECT id_membre, pseudo, mdp, role, prenom_client, nom_client, adresse_client, date_inscription, date_naissance
                    FROM membres 
                    WHERE pseudo = ?
                ");
                $stmt->execute([$pseudo]);
                $membre = $stmt->fetch(PDO::FETCH_ASSOC);

                // Vérification du mot de passe
                if ($membre && password_verify($mot_de_passe, $membre['mdp'])) {
                    // Initialisation des données utilisateur dans la session
                    $_SESSION['membre_id'] = $membre['id_membre'];
                    $_SESSION['membre_pseudo'] = $membre['pseudo'];
                    $_SESSION['membre_prenom'] = $membre['prenom_client'];
                    $_SESSION['membre_nom'] = $membre['nom_client'];
                    $_SESSION['membre_adresse'] = $membre['adresse_client'];
                    $_SESSION['membre_inscription'] = $membre['date_inscription'];
                    $_SESSION['membre_naissance'] = $membre['date_naissance'];
                    $_SESSION['membre_role'] = $membre['role'];

                    // Redirection selon le rôle
                    if ($membre['role'] == 2) { // Utilisateur lambda
                        $_SESSION['message'] = "Bonjour, " . htmlspecialchars($membre['prenom_client']) . "!";
                        header("Location: /src/app/Views/Public/accueil_shop.php");
                    } elseif ($membre['role'] == 1) { // Administrateur
                        $_SESSION['message'] = "Bonjour, " . htmlspecialchars($membre['prenom_client']) . "!";
                        $_SESSION['admin_dashboard'] = true;
                        header("Location: /src/app/Views/Admin/dashboard.php");
                    }
                    exit;
                } else {
                    // Gestion des identifiants incorrects
                    $_SESSION['error'] = "Pseudo ou mot de passe incorrect.";
                    header("Location: /src/app/Views/Public/connexion_shop.php");
                    exit;
                }
            } catch (PDOException $e) {
                // Gestion des erreurs de connexion à la base de données
                $_SESSION['error'] = "Erreur de connexion à la base de données : " . $e->getMessage();
                header("Location: /src/app/Views/Public/connexion_shop.php");
                exit;
                
                
            }
        }
    }
}
