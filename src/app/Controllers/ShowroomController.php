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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $required = ['client_nom', 'email', 'telephone', 'date_reservation', 'heure_reservation', 'service'];

            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    $message = 'Veuillez remplir tous les champs obligatoires.';
                    include('src/app/Views/Public/showroom.php');
                    return;
                }
            }

            $data = [
                'client_nom' => $_POST['client_nom'],
                'email' => $_POST['email'],
                'telephone' => $_POST['telephone'],
                'date_reservation' => $_POST['date_reservation'],
                'heure_reservation' => $_POST['heure_reservation'],
                'service' => $_POST['service'],
                'message' => $_POST['message'] ?? ''
            ];

            $this->model->saveReservation($data);
            $message = "Réservation enregistrée avec succès.";
        }

        include('src/app/Views/Public/showroom.php');
    }
}