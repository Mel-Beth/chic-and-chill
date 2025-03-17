<?php
namespace Controllers;

use Models\ShowroomModel;

class ShowroomController
{
    private $model;

    public function __construct()
    {
        $this->model = new ShowroomModel();
    }

    public function index()
    {
        include('src/app/Views/Public/showroom.php');
    }

    public function addReservation()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['reservation'])) {
            $date = $_POST['date'];
            $heure = $_POST['heure'];
            $client_nom = $_POST['client_nom'];
            $email = $_POST['email'];

            if ($this->model->checkReservationAvailability($date, $heure)) {
                echo json_encode(['success' => false, 'message' => 'Ce créneau est déjà réservé.']);
                exit;
            }

            $data = [
                ':date' => $date,
                ':heure' => $heure,
                ':client_nom' => $client_nom,
                ':email' => $email,
                ':statut' => 'en_attente'
            ];
            $this->model->addReservation($data);
            echo json_encode(['success' => true, 'message' => 'Réservation confirmée !']);
            exit;
        }
    }

    public function checkAvailability()
    {
        if (isset($_GET['check']) && isset($_GET['date'])) {
            $date = $_GET['date'];
            $available = !$this->model->checkReservationAvailability($date, null);
            echo json_encode(['available' => $available]);
            exit;
        }
    }
}
