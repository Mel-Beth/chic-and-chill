<?php

namespace Controllers;

use Models\EventsModel;
use Models\PacksModel;
use Models\ReservationModel;

class ReservationController
{
    public function reservationEvenement()
    {
        $eventsModel = new EventsModel();
        $events = $eventsModel->getAllEvents();

        include('src/app/Views/Public/reservation_evenement.php');
    }

    public function reservationPack($pack_id)
    {
        $packsModel = new PacksModel();
        $pack = $packsModel->getPackById($pack_id);

        include('src/app/Views/Public/reservation_pack.php');
    }

    public function processReservation()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $reservationModel = new ReservationModel();

            $name = htmlspecialchars($_POST["name"]);
            $email = htmlspecialchars($_POST["email"]);
            $phone = htmlspecialchars($_POST["phone"]);
            $participants = $_POST["participants"] ?? 1;
            $event_id = $_POST["event_id"] ?? null;
            $pack_id = $_POST["pack_id"] ?? null;

            if ($event_id) {
                $reservationModel->addEventReservation($name, $email, $phone, $participants, $event_id);
            } elseif ($pack_id) {
                $reservationModel->addPackReservation($name, $email, $phone, $pack_id);
            }

            header("Location: " . BASE_URL . "confirmation_reservation");
            exit();
        }
    }
}
