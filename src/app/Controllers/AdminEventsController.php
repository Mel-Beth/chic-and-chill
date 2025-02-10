<?php
namespace Controllers;

use Models\AdminEventsModel;

class AdminEventsController
{
    public function index()
    {
        try {
            $eventsModel = new AdminEventsModel();
            $events = $eventsModel->getAllEvents();
            include('src/app/Views/Admin/evenements_admin.php');
        } catch (\Exception $e) {
            error_log($e->getMessage());
            echo "Erreur de chargement des événements.";
        }
    }

    public function addEvent($data)
    {
        try {
            $eventsModel = new AdminEventsModel();
            $eventsModel->addEvent($data['title'], $data['description'], $data['date_event'], $data['time_event'], $data['location']);
            header("Location: /admin_evenements");
            exit();
        } catch (\Exception $e) {
            error_log($e->getMessage());
            echo "Erreur lors de l'ajout de l'événement.";
        }
    }
}
?>
