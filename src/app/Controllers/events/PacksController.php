<?php

namespace Controllers\Events;

use Models\Events\PacksModel;
use Models\NotificationModel;

class PacksController
{
    private $packsModel;
    private $notificationModel;

    public function __construct()
    {
        $this->packsModel = new PacksModel();
        $this->notificationModel = new NotificationModel(); // Ajout du modèle de notification
    }

    public function showPack($id)
    {
        try {
            $packsModel = new PacksModel();
            $pack = $packsModel->getPackById($id);

            if (!$pack) {
                $code_erreur = 404;
                $description_erreur = "Oups... Le pack que vous cherchez n'existe pas.";
                include('src/app/Views/erreur.php');
                exit();
            }

            include('src/app/Views/Public/events/pack_detail.php');
        } catch (\Exception $e) {
            error_log($e->getMessage());
            echo "Une erreur est survenue. Veuillez réessayer plus tard.";
        }
    }

    public function managePacks()
    {
        try {
            $packs = $this->packsModel->getAllPacksAdmin();

            if (!$packs) {
                $packs = []; // S'assurer que la variable est bien définie même si la table est vide
            }

            // Vérification du paramètre 'success' dans l'URL
            $success = isset($_GET['success']) ? $_GET['success'] : null;

            // Passer $success à la vue
            include('src/app/Views/Admin/events/admin_packs.php');
        } catch (\Exception $e) {
            error_log($e->getMessage());
            echo "Erreur lors du chargement des événements.";
        }
    }

    public function addPack()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = htmlspecialchars($_POST['title']);
            $description = htmlspecialchars($_POST['description']);
            $price = $_POST['price'];
            $duration = $_POST['duration'];
            $included = $_POST['included'];
            $status = $_POST['status'];

            // Validation des données
            if (!is_numeric($price) || $price < 0) {
                throw new \Exception("Le prix doit être un nombre positif.");
            }

            $success = $this->packsModel->addPack($title, $description, $price, $duration, $included, $status);

            if ($success) {
                header("Location: ../packs?success=1&action=add");
                exit();
            } else {
                header("Location: ../packs?success=0&action=add");
                exit();
            }
        }
    }

    public function updatePack($id)
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = htmlspecialchars($_POST['title']);
            $description = htmlspecialchars($_POST['description']);
            $price = $_POST['price'];
            $duration = $_POST['duration'];
            $included = $_POST['included'];
            $status = $_POST['status'];

            $success = $this->packsModel->updatePack($id, $title, $description, $price, $duration, $included, $status);

            if ($success) {
                header("Location: ../?success=1&action=update");
                exit();
            } else {
                header("Location: ../?success=0&action=update");
                exit();
            }
        }
    }

    public function deletePack($id)
    {
        $success = $this->packsModel->deletePack($id);

        if ($success) {
            header("Location: ../?success=1&action=delete");
            exit();
        } else {
            header("Location: ../?success=0&action=delete");
            exit();
        }
    }
}
