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
            $eventImages = $this->eventsModel->getEventImages($id);

            // Récupérer l'événement précédent et suivant
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

            $success = $this->eventsModel->createEvent($title, $description, $date_event, $status);

            header("Location: admin/evenements?success=" . ($success ? 1 : 0));
            exit();
        }
    }

    public function updateEvent($id)
    {
        include('src/app/Views/Admin/admin_events.php');
    }

    public function deleteEvent($id)
    {
        $this->eventsModel->deleteEvent($id);
        header("Location: admin/evenements");
        exit();
    }

    public function manageEvents()
    {
        try {
            $events = $this->eventsModel->getAllEventsAdmin();
            include('src/app/Views/Admin/admin_events.php');
        } catch (\Exception $e) {
            error_log($e->getMessage());
            echo "Erreur lors du chargement des événements.";
        }
    }
}
