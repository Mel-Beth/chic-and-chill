<?php

namespace Controllers;

use Models\EventsModel;
use Models\PacksModel;
use Models\ReservationModel;

class ReservationController
{
    private $reservationModel;

    public function __construct()
    {
        $this->reservationModel = new ReservationModel();
    }

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
                header("Location: confirmation_reservation?success=1");
                exit();
            } else {
                die("Erreur lors de l'enregistrement de la réservation.");
            }
        }
    }

    public function reservations()
    {
        $reservations = $this->reservationModel->getAllReservations();
        include 'src/app/Views/Admin/admin_reservations.php';
    }

    public function showReservation($id)
    {
        $reservationModel = new ReservationModel();
        $reservation = $reservationModel->getReservationById($id);
        include('src/app/Views/Admin/admin_reservation_detail.php');
    }

    public function updateReservationStatus($id, $status)
    {
        $reservation = $this->reservationModel->getReservationById($id);

        if ($reservation) {
            $this->reservationModel->updateReservationStatus($id, $status);

            // Générer la facture si accepté
            $attachmentPath = null;
            if ($status === 'confirmed') {
                require_once 'invoice_generator.php';
                $attachmentPath = \InvoiceGenerator::generateInvoice($reservation);
            }

            // Envoi d’email
            require_once 'EmailHelper.php';
            $subject = ($status === 'confirmed') ? "Votre réservation a été acceptée !" : "Votre réservation a été refusée";
            $body = ($status === 'confirmed') ? "<p>Votre réservation a été confirmée.</p>" : "<p>Nous sommes désolés, votre réservation a été refusée.</p>";

            EmailHelper::sendEmail($reservation['email'], $subject, $body, $attachmentPath);

            header('Location: admin/reservations');
            exit();
        } else {
            die("Réservation introuvable.");
        }
    }

    public function showInvoice($id)
    {
        // var_dump($_SESSION);
        // die();
        if (!isset($_SESSION['user_role']) || empty($_SESSION['user_role'])) {

            // Redirection vers page erreur
            $code_erreur = 401;
            $description_erreur = "Vous n'avez pas les droits pour accéder à cette page.";
            include('src/app/Views/erreur.php');
            exit();
            // Sinon si on est connecté en tant qu'admin
        } else if ($_SESSION['user_role'] === 'admin') {
            // 1. Récupérer la réservation
            $reservation = $this->reservationModel->getReservationById($id);
            if (!$reservation) {
                $code_erreur = 404;
                $description_erreur = "Oups... Réservation introuvable.";
                include('src/app/Views/erreur.php');
            } else {

                // 2. Générer le PDF via ton invoice_generator
                require_once 'invoice_generator.php';
                $filePath = \InvoiceGenerator::generateInvoice($reservation);

                // 3. Forcer le téléchargement ou afficher le PDF dans le navigateur
                header('Content-Type: application/pdf');
                // -> Pour un affichage direct : inline
                header('Content-Disposition: inline; filename="facture_' . $id . '.pdf"');
                // -> Pour un téléchargement direct : attachment
                // header('Content-Disposition: attachment; filename="facture_'.$id.'.pdf"');
                readfile($filePath);
                exit();
            }
            // Sinon si on est connecté en tant que client
        } else {
            // On va récupérer tous les id de commandes dans la bdd liée à l'id du client qui vaut $_SESSION['id']
            $reservations = $this->reservationModel->getReservationsByCustomerId($_SESSION['id']);
            // On va vérifier si l'id de la commande demandée est bien dans le tableau des id de commandes
            $id_reservations = array_column($reservations, 'id');
            if (in_array($id, $id_reservations)) {
                // 1. Récupérer la réservation
                $reservation = $this->reservationModel->getReservationById($id);
                if (!$reservation) {
                    $code_erreur = 404;
                    $description_erreur = "Oups... Réservation introuvable.";
                    include('src/app/Views/erreur.php');
                } else {

                    // 2. Générer le PDF via ton invoice_generator
                    require_once 'invoice_generator.php';
                    $filePath = \InvoiceGenerator::generateInvoice($reservation);

                    // 3. Forcer le téléchargement ou afficher le PDF dans le navigateur
                    header('Content-Type: application/pdf');
                    // -> Pour un affichage direct : inline
                    header('Content-Disposition: inline; filename="facture_' . $id . '.pdf"');
                    // -> Pour un téléchargement direct : attachment
                    // header('Content-Disposition: attachment; filename="facture_'.$id.'.pdf"');
                    readfile($filePath);
                    exit();
                }
            } else {
                // Redirection vers page erreur
                $code_erreur = 401;
                $description_erreur = "Vous n'avez pas les droits pour accéder à cette page.";
                include('src/app/Views/erreur.php');
                exit();
            }
        }
    }

    public function cancelReservation($id)
    {
        $reservation = $this->reservationModel->getReservationById($id);
        if (!$reservation) {
            die("Réservation introuvable.");
        }

        // Mettre un champ status = 'canceled'
        $this->reservationModel->updateReservationStatus($id, 'canceled');

        // Eventuellement envoyer un email pour prévenir de l’annulation
        // ...

        header('Location: admin/reservations');
        exit();
    }
}
