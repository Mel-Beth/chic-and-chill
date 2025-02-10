<?php

namespace Controllers;

use Models\EventsModel;
use Models\OutfitsModel;
use Models\PacksModel;

class EventsController
{
    public function index()
    {
        try {
            $eventsModel = new EventsModel();
            $outfitsModel = new OutfitsModel();
            $packsModel = new PacksModel();

            $events = $eventsModel->getAllEvents();
            $pastEvents = $eventsModel->getPastEvents();

            // Récupération des packs et suggestions par événement
            $eventPacks = $packsModel->getAllPacks();
            $suggestedOutfits = $outfitsModel->getAllOutfits();

            include('src/app/Views/Public/evenements.php');
        } catch (\Exception $e) {
            error_log($e->getMessage());
            echo "Une erreur est survenue. Veuillez réessayer plus tard.";
        }
    }

    public function showEvent($id)
    {
        try {
            $eventsModel = new EventsModel();
            $event = $eventsModel->getEventById($id);
            $eventImages = $eventsModel->getEventImages($id);

            // Récupère l'événement précédent et suivant
            $prevEvent = $eventsModel->getPrevEvent($id);
            $nextEvent = $eventsModel->getNextEvent($id);

            include('src/app/Views/Public/evenement_detail.php');
        } catch (\Exception $e) {
            error_log($e->getMessage());
            echo "Une erreur est survenue. Veuillez réessayer plus tard.";
        }
    }
}
