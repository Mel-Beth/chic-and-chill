<?php

namespace Controllers;

use Models\EventsModel;
use Models\OutfitsModel;
use Models\PacksModel;

class EventsController
{
    private $eventsModel;
    private $outfitsModel;
    private $packsModel;

    public function __construct()
    {
        $this->eventsModel = new EventsModel();
        $this->outfitsModel = new OutfitsModel();
        $this->packsModel = new PacksModel();
    }

    public function index()
    {
        try {
            $events = $this->eventsModel->getAllEvents();
            $pastEvents = $this->eventsModel->getPastEvents();
            $upcomingEvents = $this->eventsModel->getUpcomingEvents();

            // Récupération des packs et suggestions par événement
            $eventPacks = $this->packsModel->getAllPacks();
            $suggestedOutfits = $this->outfitsModel->getAllOutfits();

            include('src/app/Views/Public/events/evenements.php');
        } catch (\Exception $e) {
            error_log($e->getMessage());
            echo "Une erreur est survenue. Veuillez réessayer plus tard.";
        }
    }

    public function showEvent($id)
    {
        try {
            $event = $this->eventsModel->getEventById($id);
            $eventMedia = $this->eventsModel->getEventMedia($id); // Récupère images & vidéos

            $prevEvent = $this->eventsModel->getPrevEvent($event['date_event']);
            $nextEvent = $this->eventsModel->getNextEvent($event['date_event']);

            include('src/app/Views/Public/events/evenement_detail.php');
        } catch (\Exception $e) {
            error_log($e->getMessage());
            echo "Une erreur est survenue. Veuillez réessayer plus tard.";
        }
    }
    

    public function handleImageUpload($file, $existingImage = null)
    {
        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            return $existingImage; // Retourne l'image existante si aucun nouveau fichier n'est uploadé
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        $uploadDir = 'assets/images/events/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = uniqid() . '_' . basename($file['name']);
        $uploadPath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // Si une ancienne image existe et qu'on met à jour, on peut la supprimer
            if ($existingImage && file_exists($uploadDir . $existingImage)) {
                unlink($uploadDir . $existingImage);
            }
            return $fileName;
        }
        return false;
    }

    public function addEvent()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = htmlspecialchars($_POST['title']);
            $description = htmlspecialchars($_POST['description']);
            $date_event = $_POST['date_event'];
            $location = htmlspecialchars($_POST['location']);
            $status = $_POST['status'];

            // Gestion de l'image
            $image = $this->handleImageUpload($_FILES['image']);
            if ($image === false) {
                header("Location: ../evenements?success=0&action=add");
                exit();
            }

            $success = $this->eventsModel->addEvent($title, $description, $date_event, $location, $status, $image);

            if ($success) {
                header("Location: ../evenements?success=1&action=add");
                exit();
            } else {
                header("Location: ../evenements?success=0&action=add");
                exit();
            }
        }
    }

    public function updateEvent($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = htmlspecialchars($_POST['title']);
            $description = htmlspecialchars($_POST['description'] ?? '');
            $date_event = $_POST['date_event'];
            $location = htmlspecialchars($_POST['location'] ?? ''); // Ajout de location
            $status = $_POST['status'];

            // Récupérer l'événement existant pour avoir l'image actuelle
            $currentEvent = $this->eventsModel->getEventById($id);
            $currentImage = $currentEvent['image'] ?? null;

            // Gestion de l'image
            $image = $this->handleImageUpload($_FILES['image'], $currentImage);
            if ($image === false) {
                header("Location: ../?success=0&action=update");
                exit();
            }

            // Appel correct avec tous les paramètres
            $success = $this->eventsModel->updateEvent($id, $title, $description, $date_event, $location, $status, $image);

            if ($success) {
                header("Location: ../?success=1&action=update");
                exit();
            } else {
                header("Location: ../?success=0&action=update");
                exit();
            }
        }
    }

    public function configureEvent($id)
    {
        $event = $this->eventsModel->getEventById($id);

        if (!$event || $event['status'] !== 'active') {
            header("Location: admin/evenements?error=invalid_event");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['media'])) {
            $files = $_FILES['media'];
            $uploadDir = 'assets/images/events/';

            for ($i = 0; $i < count($files['name']); $i++) {
                $fileName = uniqid() . '-' . basename($files['name'][$i]);
                $filePath = $uploadDir . $fileName;
                $fileType = mime_content_type($files['tmp_name'][$i]);
                $type = strpos($fileType, 'image') === 0 ? 'image' : (strpos($fileType, 'video') === 0 ? 'video' : null);

                if ($type && move_uploaded_file($files['tmp_name'][$i], $filePath)) {
                    $this->eventsModel->addEventMedia($id, $filePath, $type);
                }
            }
            header("Location: admin/evenements/configurer/$id?success=media_added");
            exit();
        }

        include('src/app/Views/Admin/events/configure_event.php');
    }

    public function deleteEventMedia($mediaId)
    {
        $this->eventsModel->deleteEventMedia($mediaId);
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit();
    }

    public function deleteEvent($id)
    {
        $success = $this->eventsModel->deleteEvent($id);

        if ($success) {
            header("Location: ../?success=1&action=delete");
            exit();
        } else {
            header("Location: ../?success=0&action=delete");
            exit();
        }
    }

    public function manageEvents()
    {
        try {
            $events = $this->eventsModel->getAllEventsAdmin();

            if (!$events) {
                $events = []; // S'assurer que la variable est bien définie même si la table est vide
            }

            // Vérification du paramètre 'success' dans l'URL
            $success = isset($_GET['success']) ? $_GET['success'] : null;

            // Passer $success à la vue
            include('src/app/Views/Admin/events/admin_events.php');
        } catch (\Exception $e) {
            error_log($e->getMessage());
            echo "Erreur lors du chargement des événements.";
        }
    }
}
