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

            // Vérifier si le client est une entreprise ou un particulier
            $customer_type = htmlspecialchars($_POST["customer_type"]);
            $company_name = ($customer_type === "entreprise") ? htmlspecialchars($_POST["company_name"]) : null;
            $siret = ($customer_type === "entreprise") ? htmlspecialchars($_POST["siret"] ?? '') : null;
            $address = ($customer_type === "entreprise") ? htmlspecialchars($_POST["address"]) : null;

            // Coordonnées du client
            $name = htmlspecialchars($_POST["name"]);
            $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
            $phone = htmlspecialchars($_POST["phone"]);

            // Détails de l'événement ou du pack
            $event_type = htmlspecialchars($_POST["event_type"] ?? '');
            $participants = (int) ($_POST["participants"] ?? 1);
            $services = isset($_POST["services"]) ? implode(", ", $_POST["services"]) : ''; // Stocker sous forme de texte
            $comments = htmlspecialchars($_POST["comments"]);
            $event_id = $_POST["event_id"] ?? null;
            $pack_id = $_POST["pack_id"] ?? null;

            // Vérifier que l'email est valide
            if (!$email) {
                die("Adresse e-mail invalide.");
            }

            if ($event_id) {
                // Enregistrement de la réservation d'un événement
                $success = $reservationModel->addEventReservation($customer_type, $company_name, $siret, $address, $name, $email, $phone, $event_type, $participants, $services, $comments, $event_id);
            } elseif ($pack_id) {
                // Enregistrement de la réservation d'un pack
                $success = $reservationModel->addPackReservation($customer_type, $company_name, $siret, $address, $name, $email, $phone, $services, $comments, $pack_id);
            } else {
                die("Aucun événement ou pack sélectionné.");
            }

            if ($success) {
                header("Location: " . BASE_URL . "confirmation_reservation?success=1");
                exit();
            } else {
                die("Erreur lors de l'enregistrement de la réservation.");
            }
        }
    }
}
