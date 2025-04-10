<?php

namespace Controllers\Events;

use Models\Events\PacksModel;
use Models\NotificationModel;

class PacksController
{
    private $packsModel;
    private $notificationModel;

    // Constructeur : Initialise les modèles nécessaires
    public function __construct()
    {
        $this->packsModel = new PacksModel();              // Modèle pour gérer les packs
        $this->notificationModel = new NotificationModel(); // Modèle pour gérer les notifications
    }

    // Affiche les détails d'un pack spécifique en fonction de son ID
    public function showPack($id)
    {
        try {
            // Initialise une nouvelle instance du modèle PacksModel
            $packsModel = new PacksModel();
            $pack = $packsModel->getPackById($id); // Récupère le pack par son ID

            // Si le pack n'existe pas, affiche une page d'erreur 404
            if (!$pack) {
                $code_erreur = 404;
                $description_erreur = "Oups... Le pack que vous cherchez n'existe pas.";
                include('src/app/Views/erreur.php');
                exit();
            }

            // Inclut la vue publique pour afficher les détails du pack
            include('src/app/Views/Public/events/pack_detail.php');
        } catch (\Exception $e) {
            // En cas d'erreur, log l'exception et affiche un message générique
            error_log($e->getMessage());
            echo "Une erreur est survenue. Veuillez réessayer plus tard.";
        }
    }

    // Gère l'affichage de la liste des packs pour l'administration
    public function managePacks()
    {
        try {
            // Récupère tous les packs pour l'administrateur
            $packs = $this->packsModel->getAllPacksAdmin();

            // Si aucun pack n'est trouvé, initialise un tableau vide
            if (!$packs) {
                $packs = []; // Évite les erreurs si la table est vide
            }

            // Récupère le paramètre 'success' de l'URL pour indiquer une action réussie ou non
            $success = isset($_GET['success']) ? $_GET['success'] : null;

            // Inclut la vue d'administration pour gérer les packs
            include('src/app/Views/Admin/events/admin_packs.php');
        } catch (\Exception $e) {
            // Log l'erreur et affiche un message en cas de problème
            error_log($e->getMessage());
            echo "Erreur lors du chargement des événements.";
        }
    }

    // Ajoute un nouveau pack via une requête POST
    public function addPack()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Nettoie et récupère les données du formulaire
            $title = htmlspecialchars($_POST['title']);
            $description = htmlspecialchars($_POST['description']);
            $price = $_POST['price'];
            $duration = $_POST['duration'];
            $included = $_POST['included'];
            $status = $_POST['status'];

            // Validation : Vérifie que le prix est un nombre positif
            if (!is_numeric($price) || $price < 0) {
                throw new \Exception("Le prix doit être un nombre positif.");
            }

            // Ajoute le pack dans la base de données via le modèle
            $success = $this->packsModel->addPack($title, $description, $price, $duration, $included, $status);

            // Redirige avec un message de succès ou d'échec
            if ($success) {
                header("Location: ../packs?success=1&action=add");
                exit();
            } else {
                header("Location: ../packs?success=0&action=add");
                exit();
            }
        }
    }

    // Met à jour un pack existant via une requête POST
    public function updatePack($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Nettoie et récupère les données du formulaire
            $title = htmlspecialchars($_POST['title']);
            $description = htmlspecialchars($_POST['description']);
            $price = $_POST['price'];
            $duration = $_POST['duration'];
            $included = $_POST['included'];
            $status = $_POST['status'];

            // Met à jour le pack dans la base de données via le modèle
            $success = $this->packsModel->updatePack($id, $title, $description, $price, $duration, $included, $status);

            // Redirige avec un message de succès ou d'échec
            if ($success) {
                header("Location: ../?success=1&action=update");
                exit();
            } else {
                header("Location: ../?success=0&action=update");
                exit();
            }
        }
    }

    // Supprime un pack spécifique en fonction de son ID
    public function deletePack($id)
    {
        // Supprime le pack via le modèle
        $success = $this->packsModel->deletePack($id);

        // Redirige avec un message de succès ou d'échec
        if ($success) {
            header("Location: ../?success=1&action=delete");
            exit();
        } else {
            header("Location: ../?success=0&action=delete");
            exit();
        }
    }
}
?>