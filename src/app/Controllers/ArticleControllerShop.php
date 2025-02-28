<?php
namespace Controllers;

use Models\AppelArticleModelShop;
require_once 'src/app/Controllers/DatabaseShop.php'; // Connexion à la BDD



class ArticleControllerShop { // ✅ Assure-toi que le nom est correct
    private $productModel;

    public function __construct() {
        $this->productModel = new AppelArticleModelShop(); // ✅ Instancier le modèle
    }

    // ✅ Vérifie que cette méthode est bien définie
    public function showProducts() {
        $gender = $_GET['gender'] ?? 'femmes'; // Par défaut, afficher les produits femmes
        $id_categories = $_GET['id_categories'] ?? null;
        $id_ss_categories = $_GET['id_ss_categories'] ?? null;
    
        // Récupérer les produits filtrés
        $products = $this->productModel->getProductsFiltered($gender, $id_categories, $id_ss_categories);
    
        include 'src/app/Views/Public/produits_shop.php'; // Charger la vue
    }
}
?>

ConnexionControllerShop.php

<?php

namespace Controllers;

use Models\CoModelShop;
use PDOException;
use Controllers\DatabaseShop;
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
           

            // Récupération des données du formulaire
            $identifier = isset($_POST['identifier']) ? trim($_POST['identifier']) : null;
            $password = isset($_POST['password']) ? trim($_POST['password']) : null;

            // Vérification des champs vides
            if (empty($identifier) || empty($password)) {
                $_SESSION['error'] = "Veuillez remplir tous les champs.";
                header("Location: /site_stage/chic-and-chill/connexion_shop");
                exit;
            }

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
                        header("Location: /site_stage/chic-and-chill/dashboard_admin");
                    }
                    exit;
                } else {
                    // Gestion des identifiants incorrects
                    $_SESSION['error'] = "Identifiant ou mot de passe incorrect.";
                    header("Location: connexion_shop");
                    exit;
                }
            } catch (PDOException $e) {
                // Gestion des erreurs de connexion à la base de données
                $_SESSION['error'] = "Erreur de connexion à la base de données : " . $e->getMessage();
                header("Location: /site_stage/chic-and-chill/connexion_shop");
                exit;
            }
        }
    }
}
?>