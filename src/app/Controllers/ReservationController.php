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

        include('src/app/Views/Public/events/reservation_evenement.php');
    }

    public function reservationPack($pack_id)
    {
        $packsModel = new PacksModel();
        $pack = $packsModel->getPackById($pack_id);

        include('src/app/Views/Public/events/reservation_pack.php');
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
            $event_type = htmlspecialchars($_POST["event_type"] ?? '');
            $participants = (int) ($_POST["participants"] ?? 1);
            $services = isset($_POST["services"]) ? implode(", ", $_POST["services"]) : '';
            $comments = htmlspecialchars($_POST["comments"]);
            $pack_id = $_POST["pack_id"] ?? null;

            // Vérifications des champs obligatoires communs
            if (empty($name)) {
                die("Le nom est requis.");
            }
            if (!$email) {
                die("Adresse e-mail invalide.");
            }
            if (empty($phone)) {
                die("Le numéro de téléphone est requis.");
            }

            // Vérifier si c'est une réservation d'événement ou de pack
            if (!empty($event_type)) {
                // Réservation d'un événement
                if (empty($event_type)) {
                    die("Le type d'événement est requis.");
                }
                $success = $reservationModel->addEventReservation($customer_type, $company_name, $siret, $address, $name, $email, $phone, $event_type, $participants, $services, $comments, null);
            } elseif (!empty($pack_id)) {
                // Réservation d'un pack
                if (empty($pack_id)) {
                    die("L'ID du pack est requis.");
                }
                $success = $reservationModel->addPackReservation($customer_type, $company_name, $siret, $address, $name, $email, $phone, $services, $comments, $pack_id);
            } else {
                die("Aucun type d'événement ou pack sélectionné.");
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
        include 'src/app/Views/Admin/events/admin_reservations.php';
    }

    public function showReservation($id)
    {
        $reservationModel = new ReservationModel();
        $reservation = $reservationModel->getReservationById($id);
        include('src/app/Views/admin/admin_reservation_detail.php');
    }

    public function updateReservationStatus($id, $status)
    {
        $type = isset($_GET['type']) ? $_GET['type'] : null;
        $reservation = $this->reservationModel->getReservationById($id, $type);

        if ($reservation) {
            // Si c'est une réservation de pack, enrichir avec les détails du pack
            if ($reservation['type'] === 'pack') {
                $packDetails = $this->reservationModel->getPackDetails($reservation['pack_id']);
                $reservation['pack_title'] = $packDetails['title'];
                $reservation['pack_price'] = $packDetails['price'];
            }

            $updateSuccess = $this->reservationModel->updateReservationStatus($id, $status, $type);

            if ($updateSuccess) {
                $attachmentPath = null;
                if ($status === 'confirmed') {
                    require_once 'invoice_generator.php';
                    $attachmentPath = \InvoiceGenerator::generateInvoice($reservation);
                }

                require_once 'EmailHelper.php';

                // Sujet de l’email
                $subject = ($status === 'confirmed') ? "Confirmation de votre réservation - Chic & Chill" : "Notification de refus de votre réservation - Chic & Chill";

                // Corps de l’email avec logo à gauche
                $body = '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>' . $subject . '</title>
            </head>
            <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0;">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 20px auto; border: 1px solid #e0e0e0; border-radius: 5px;">
                    <!-- En-tête avec logo à gauche -->
                    <tr>
                        <td style="background-color: #8B5A2B; padding: 20px; border-bottom: 1px solid #e0e0e0;">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                 <tr>
                                    <td style="background-color: #8B5A2B; padding: 20px; text-align: center; border-bottom: 1px solid #e0e0e0;">
                                        <h1 style="color: #fff; margin: 0;">Chic & Chill</h1>
                                        <p style="color: #fff; font-size: 14px; margin: 5px 0 0;">Votre expérience sur mesure</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- Corps -->
                    <tr>
                        <td style="padding: 20px;">
                            <h2 style="color: ' . ($status === 'confirmed' ? '#28a745' : '#dc3545') . '; margin: 0 0 15px;">' . ($status === 'confirmed' ? 'Réservation confirmée' : 'Réservation refusée') . '</h2>
                            <p style="margin: 0 0 15px;">Bonjour ' . htmlspecialchars($reservation['customer_name']) . ',</p>
                            <p style="margin: 0 0 15px;">Nous vous informons que votre réservation ' . ($reservation['type'] === 'event' ? 'd’événement' : 'de pack') . ' a été ' . ($status === 'confirmed' ? 'confirmée' : 'refusée') . '.</p>
                            
                            <!-- Informations du client -->
                            <h3 style="margin: 20px 0 10px;">Informations du client</h3>
                            <table width="100%" cellpadding="5" cellspacing="0" style="border: 1px solid #e0e0e0; border-radius: 5px; background-color: #f9f9f9;">';

                // Ajouter les informations du client (différentes pour entreprise et particulier)
                if ($reservation['customer_type'] === 'entreprise') {
                    $body .= '
                                <tr>
                                    <td style="font-weight: bold; width: 30%;">Nom de l\'entreprise :</td>
                                    <td>' . htmlspecialchars($reservation['company_name']) . '</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold;">SIRET :</td>
                                    <td>' . htmlspecialchars($reservation['siret']) . '</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold;">Adresse :</td>
                                    <td>' . htmlspecialchars($reservation['address']) . '</td>
                                </tr>';
                }

                $body .= '
                                <tr>
                                    <td style="font-weight: bold;">Nom :</td>
                                    <td>' . htmlspecialchars($reservation['customer_name']) . '</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold;">Email :</td>
                                    <td>' . htmlspecialchars($reservation['email']) . '</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold;">Téléphone :</td>
                                    <td>' . htmlspecialchars($reservation['phone']) . '</td>
                                </tr>
                            </table>

                            <!-- Détails de la réservation -->
                            <h3 style="margin: 20px 0 10px;">Détails de la réservation</h3>
                            <table width="100%" cellpadding="5" cellspacing="0" style="border: 1px solid #e0e0e0; border-radius: 5px; background-color: #f9f9f9;">
                                <tr>
                                    <td style="font-weight: bold; width: 30%;">Date de création :</td>
                                    <td>' . $reservation['created_at'] . '</td>
                                </tr>';

                // Ajouter des détails spécifiques selon le type
                if ($reservation['type'] === 'event') {
                    $body .= '
                                <tr>
                                    <td style="font-weight: bold;">Type d’événement :</td>
                                    <td>' . htmlspecialchars($reservation['event_type']) . '</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold;">Participants :</td>
                                    <td>' . $reservation['participants'] . '</td>
                                </tr>';
                } elseif ($reservation['type'] === 'pack') {
                    $body .= '
                                <tr>
                                    <td style="font-weight: bold;">Services :</td>
                                    <td>' . htmlspecialchars($reservation['services']) . '</td>
                                </tr>';
                }

                $body .= '
                            </table>';

                // Informations supplémentaires (commentaires)
                if (!empty($reservation['comments']) && $reservation['comments'] !== 'Aucun') {
                    $body .= '
                            <h3 style="margin: 20px 0 10px;">Informations supplémentaires</h3>
                            <table width="100%" cellpadding="5" cellspacing="0" style="border: 1px solid #e0e0e0; border-radius: 5px; background-color: #f9f9f9;">
                                <tr>
                                    <td style="font-weight: bold; width: 30%;">Commentaires :</td>
                                    <td>' . htmlspecialchars($reservation['comments']) . '</td>
                                </tr>
                            </table>';
                }

                // Message spécifique pour les événements (confirmation uniquement)
                if ($status === 'confirmed') {
                    if ($reservation['type'] === 'event') {
                        $body .= '
                            <p style="margin: 15px 0 0;">Pour définir le prix de votre événement, veuillez contacter notre gérant à l’adresse <a href="mailto:contact@chicandchill.fr" style="color: #8B5A2B; text-decoration: none;">contact@chicandchill.fr</a>.</p>';
                    } else {
                        $body .= '
                            <p style="margin: 15px 0 0;">Vous trouverez la facture en pièce jointe. Merci de nous avoir choisis !</p>';
                    }
                } else {
                    $body .= '
                        <p style="margin: 15px 0 0;">Nous sommes désolés de ne pas pouvoir donner suite à votre demande.</p>';
                }

                $body .= '
                            <p style="margin: 15px 0 0;">Pour toute question, n’hésitez pas à nous contacter à l’adresse <a href="mailto:contact@chicandchill.fr" style="color: #8B5A2B; text-decoration: none;">contact@chicandchill.fr</a>.</p>
                        </td>
                    </tr>
                    <!-- Pied de page -->
                    <tr>
                        <td style="background-color: #f1f1f1; padding: 15px; text-align: center; border-top: 1px solid #e0e0e0; font-size: 12px; color: #666;">
                            <p style="margin: 0;">Chic & Chill - 10 Rue Irénée Carré, 08000 Charleville-Mézières, France</p>
                            <p style="margin: 5px 0 0;">Tél : +33 7 81 26 64 56 | Email : <a href="mailto:contact@chicandchill.fr" style="color: #8B5A2B; text-decoration: none;">contact@chicandchill.fr</a></p>
                            <p style="margin: 5px 0 0;">© ' . date('Y') . ' Chic & Chill. Tous droits réservés.</p>
                        </td>
                    </tr>
                </table>
            </body>
            </html>';

                $emailSent = EmailHelper::sendEmail($reservation['email'], $subject, $body, $attachmentPath);

                $_SESSION['message'] = [
                    'type' => $emailSent ? 'success' : 'warning',
                    'text' => $emailSent ?
                        "Le statut a été mis à jour et l'email a été envoyé avec succès." :
                        "Le statut a été mis à jour, mais l'email n'a pas pu être envoyé."
                ];
            } else {
                $_SESSION['message'] = [
                    'type' => 'error',
                    'text' => "Échec de la mise à jour du statut pour ID $id (type: " . ($type ?: 'inconnu') . ")."
                ];
            }

            header("Location: ../confirmation");
            exit();
        } else {
            $_SESSION['message'] = [
                'type' => 'error',
                'text' => "Réservation avec ID $id (type: " . ($type ?: 'inconnu') . ") introuvable."
            ];
            header("Location: ../confirmation");
            exit();
        }
    }

    public function showConfirmation()
    {
        // On ne redirige pas immédiatement, on laisse la vue s'afficher
        include 'src/app/Views/Admin/events/admin_reservation_confirmation.php';
        unset($_SESSION['message']);
        // Pas de header() ici, la redirection sera gérée par JavaScript dans la vue
    }

    public function showInvoice($id)
    {
        if (!isset($_SESSION['user_role']) || empty($_SESSION['user_role'])) {
            // Redirection vers page erreur
            $code_erreur = 401;
            $description_erreur = "Vous n'avez pas les droits pour accéder à cette page.";
            include('src/app/Views/erreur.php');
            exit();
        } else if ($_SESSION['user_role'] === 'admin') {
            // 1. Récupérer la réservation
            $reservation = $this->reservationModel->getReservationById($id);
            if (!$reservation) {
                $code_erreur = 404;
                $description_erreur = "Oups... Réservation introuvable.";
                include('src/app/Views/erreur.php');
            } else {
                // Si c'est une réservation de pack, enrichir avec les détails du pack
                if ($reservation['type'] === 'pack') {
                    $packDetails = $this->reservationModel->getPackDetails($reservation['pack_id']);
                    $reservation['pack_title'] = $packDetails['title'];
                    $reservation['pack_price'] = $packDetails['price'];
                }

                // 2. Générer le PDF via ton invoice_generator
                require_once 'invoice_generator.php';
                $filePath = \InvoiceGenerator::generateInvoice($reservation);

                // 3. Forcer le téléchargement ou afficher le PDF dans le navigateur
                header('Content-Type: application/pdf');
                header('Content-Disposition: inline; filename="facture_' . $id . '.pdf"');
                readfile($filePath);
                exit();
            }
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
                    // Si c'est une réservation de pack, enrichir avec les détails du pack
                    if ($reservation['type'] === 'pack') {
                        $packDetails = $this->reservationModel->getPackDetails($reservation['pack_id']);
                        $reservation['pack_title'] = $packDetails['title'];
                        $reservation['pack_price'] = $packDetails['price'];
                    }

                    // 2. Générer le PDF via ton invoice_generator
                    require_once 'invoice_generator.php';
                    $filePath = \InvoiceGenerator::generateInvoice($reservation);

                    // 3. Forcer le téléchargement ou afficher le PDF dans le navigateur
                    header('Content-Type: application/pdf');
                    header('Content-Disposition: inline; filename="facture_' . $id . '.pdf"');
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
