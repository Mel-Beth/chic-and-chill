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

            include('src/app/Views/Public/evenements.php');
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

            include('src/app/Views/Public/evenement_detail.php');
        } catch (\Exception $e) {
            error_log($e->getMessage());
            echo "Une erreur est survenue. Veuillez réessayer plus tard.";
        }
    }

    public function addEvent()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = htmlspecialchars($_POST['title']);
            $description = htmlspecialchars($_POST['description']);
            $date_event = $_POST['date_event'];
            $status = $_POST['status'];

            $success = $this->eventsModel->addEvent($title, $description, $date_event, $status);

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
            $description = htmlspecialchars($_POST['description']);
            $date_event = $_POST['date_event'];
            $status = $_POST['status'];

            $success = $this->eventsModel->updateEvent($id, $title, $description, $date_event, $status);

            if ($success) {
                header("Location: ../?success=1&action=update");
                exit();
            } else {
                header("Location: ../?success=0&action=update");
                exit();
            }
        }
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
            include('src/app/Views/Admin/admin_events.php');
        } catch (\Exception $e) {
            error_log($e->getMessage());
            echo "Erreur lors du chargement des événements.";
        }
    }
}
