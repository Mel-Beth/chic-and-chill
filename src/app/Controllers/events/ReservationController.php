<?php

namespace Controllers\Events;

use Models\Events\EventsModel;
use Models\Events\PacksModel;
use Models\Events\ReservationModel;
use Models\NotificationModel;

class ReservationController
{
    private $reservationModel;
    private $notificationModel;

    // Constructeur : Initialise les modèles nécessaires
    public function __construct()
    {
        $this->reservationModel = new ReservationModel();    // Modèle pour gérer les réservations
        $this->notificationModel = new NotificationModel();  // Modèle pour gérer les notifications
    }

    // Affiche la page de réservation d'événements avec la liste des événements disponibles
    public function reservationEvenement()
    {
        $eventsModel = new EventsModel();         // Initialise le modèle des événements
        $events = $eventsModel->getAllEvents();   // Récupère tous les événements
        include('src/app/Views/Public/events/reservation_evenement.php'); // Inclut la vue publique
    }

    // Affiche la page de réservation d'un pack spécifique en fonction de son ID
    public function reservationPack($pack_id)
    {
        $packsModel = new PacksModel();           // Initialise le modèle des packs
        $pack = $packsModel->getPackById($pack_id); // Récupère le pack par son ID
        include('src/app/Views/Public/events/reservation_pack.php'); // Inclut la vue publique
    }

    // Traite les données d'une réservation soumise via un formulaire POST
    public function processReservation()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Récupère et valide les données du formulaire
            $customer_type = htmlspecialchars($_POST["customer_type"]);
            $company_name = ($customer_type === "entreprise") ? htmlspecialchars($_POST["company_name"]) : null;
            $siret = ($customer_type === "entreprise") ? htmlspecialchars($_POST["siret"] ?? '') : null;
            $address = ($customer_type === "entreprise") ? htmlspecialchars($_POST["address"]) : null;

            $name = htmlspecialchars($_POST["name"]);
            $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
            $phone = htmlspecialchars($_POST["phone"]);
            $event_type = htmlspecialchars($_POST["event_type"] ?? '');
            $participants = filter_input(INPUT_POST, 'participants', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 1000]]);
            if ($participants === false) die("Nombre de participants invalide."); // Validation des participants
            $services = isset($_POST["services"]) ? implode(", ", $_POST["services"]) : ''; // Concatène les services
            $comments = htmlspecialchars($_POST["comments"]);
            if (strlen($comments) > 1000) die("Les commentaires ne peuvent pas dépasser 1000 caractères."); // Limite des commentaires
            if (preg_match('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/i', $comments)) die("Les commentaires ne peuvent pas contenir de scripts."); // Sécurité contre les scripts
            $pack_id = $_POST["pack_id"] ?? null;

            // Validations de base
            if (empty($name)) die("Le nom est requis.");
            if (!$email) die("Adresse e-mail invalide.");
            if (empty($phone)) die("Le numéro de téléphone est requis.");

            // Ajoute la réservation selon le type (événement ou pack)
            if (!empty($event_type)) {
                $success = $this->reservationModel->addEventReservation(
                    $customer_type,
                    $company_name,
                    $siret,
                    $address,
                    $name,
                    $email,
                    $phone,
                    $event_type,
                    $participants,
                    $services,
                    $comments,
                    null
                );
                error_log("Ajout réservation événement : " . ($success ? "Succès" : "Échec"));
                if ($success) {
                    $this->notificationModel->createNotification("Nouvelle réservation pour un événement par $name ($event_type)");
                }
            } elseif (!empty($pack_id)) {
                $success = $this->reservationModel->addPackReservation(
                    $customer_type,
                    $company_name,
                    $siret,
                    $address,
                    $name,
                    $email,
                    $phone,
                    $services,
                    $comments,
                    $pack_id
                );
                error_log("Ajout réservation pack : " . ($success ? "Succès" : "Échec"));
                if ($success) {
                    $this->notificationModel->createNotification("Nouvelle réservation pour un pack par $name (ID: $pack_id)");
                }
            } else {
                die("Aucun type d'événement ou pack sélectionné.");
            }

            // Redirige vers une page de confirmation si succès
            if ($success) {
                header("Location: confirmation_reservation?success=1");
                exit();
            } else {
                die("Erreur lors de l'enregistrement de la réservation.");
            }
        }
    }

    // Affiche la liste des réservations pour l'administration
    public function reservations()
    {
        $reservations = $this->reservationModel->getAllReservations(); // Récupère toutes les réservations

        // Enrichit les réservations de type "pack" avec les détails du pack
        foreach ($reservations as &$reservation) {
            if ($reservation['type'] === 'pack') {
                $packDetails = $this->reservationModel->getPackDetails($reservation['event_id']);
                $reservation['title'] = $packDetails['title'];
                $reservation['price'] = $packDetails['price'];
            }
        }
        unset($reservation);

        include 'src/app/Views/Admin/events/admin_reservations.php'; // Inclut la vue d'administration
    }

    // Affiche les détails d'une réservation spécifique
    public function showReservation($id)
    {
        $reservationModel = new ReservationModel();
        $reservation = $reservationModel->getReservationById($id); // Récupère la réservation par ID
        include('src/app/Views/admin/admin_reservation_detail.php'); // Inclut la vue détaillée
    }

    // Met à jour le statut d'une réservation et envoie un email de confirmation ou de refus
    public function updateReservationStatus($id, $status)
    {
        $type = isset($_GET['type']) ? $_GET['type'] : null;
        $reservation = $this->reservationModel->getReservationById($id, $type); // Récupère la réservation

        if ($reservation) {
            // Enrichit les réservations de pack avec les détails
            if ($reservation['type'] === 'pack') {
                $packDetails = $this->reservationModel->getPackDetails($reservation['pack_id']);
                $reservation['pack_title'] = $packDetails['title'];
                $reservation['pack_price'] = $packDetails['price'];
            }

            // Met à jour le statut dans la base de données
            $updateSuccess = $this->reservationModel->updateReservationStatus($id, $status, $type);

            if ($updateSuccess) {
                $attachmentPath = null;
                // Génère une facture si le statut est "confirmed" et qu'aucune facture n'existe
                if ($status === 'confirmed' && empty($reservation['invoice_path'])) {
                    require_once 'src/app/Controllers/InvoiceGenerator.php';
                    $attachmentPath = \Controllers\InvoiceGenerator::generateInvoice($reservation);
                    if (file_exists($attachmentPath)) {
                        $this->reservationModel->updateInvoicePath($id, $reservation['type'], $attachmentPath);
                    } else {
                        error_log("Erreur : La facture n’a pas été générée à $attachmentPath");
                        $attachmentPath = null;
                    }
                } elseif ($status === 'confirmed' && !empty($reservation['invoice_path'])) {
                    $attachmentPath = $reservation['invoice_path'];
                }

                require_once 'src/app/Controllers/EmailHelper.php';

                // Définit le sujet de l'email selon le statut
                $subject = ($status === 'confirmed') ? "Confirmation de votre réservation - Chic & Chill"
                    : "Notification de refus de votre réservation - Chic & Chill";

                // Construit le corps de l'email en HTML
                $body = '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>' . $subject . '</title>
            </head>
            <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0;">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 20px auto; border: 1px solid #e0e0e0; border-radius: 5px;">
                    <!-- En-tête -->
                    <tr>
                        <td style="background-color: #8B5A2B; padding: 20px; text-align: center; border-bottom: 1px solid #e0e0e0;">
                            <h1 style="color: #fff; margin: 0;">Chic & Chill</h1>
                            <p style="color: #fff; font-size: 14px; margin: 5px 0 0;">Votre expérience sur mesure</p>
                        </td>
                    </tr>
                    <!-- Corps -->
                    <tr>
                        <td style="padding: 20px;">
                        <div style="text-align: center; margin-bottom: 20px;">
                            <img style="width: 5rem; height: auto;" src="cid:logoCID" alt="Logo">
                        </div>
                            <h2 style="color: ' . ($status === 'confirmed' ? '#28a745' : '#dc3545') . '; margin: 0 0 15px;">' . ($status === 'confirmed' ? 'Réservation confirmée' : 'Réservation refusée') . '</h2>
                            <p style="margin: 0 0 15px;">Bonjour ' . htmlspecialchars($reservation['customer_name']) . ',</p>
                            <p style="margin: 0 0 15px;">Nous vous informons que votre réservation ' . ($reservation['type'] === 'event' ? 'd’événement' : 'de pack') . ' a été ' . ($status === 'confirmed' ? 'confirmée' : 'refusée') . '.</p>
                            <!-- Informations du client -->
                            <h3 style="margin: 20px 0 10px;">Informations du client</h3>
                            <table width="100%" cellpadding="5" cellspacing="0" style="border: 1px solid #e0e0e0; border-radius: 5px; background-color: #f9f9f9;">';

                // Ajoute les informations spécifiques pour les entreprises
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

                // Ajoute des détails spécifiques selon le type de réservation
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

                // Ajoute les commentaires s'ils existent
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

                // Message spécifique selon le statut et le type
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

                // Envoie l'email avec la pièce jointe si applicable
                $emailSent = \Controllers\EmailHelper::sendEmail($reservation['email'], $subject, $body, $attachmentPath);
                error_log("Envoi email pour réservation $id : " . ($emailSent ? "Succès" : "Échec"));

                // Définit un message de session selon le résultat de l'envoi
                $_SESSION['message'] = [
                    'type' => $emailSent ? 'success' : 'warning',
                    'text' => $emailSent ? "Statut mis à jour et email envoyé avec succès."
                        : "Statut mis à jour, mais l’email n’a pas pu être envoyé correctement."
                ];
            } else {
                error_log("Échec mise à jour statut pour réservation ID $id.");
                $_SESSION['message'] = [
                    'type' => 'error',
                    'text' => "Échec de la mise à jour du statut pour ID $id."
                ];
            }

            header("Location: ../confirmation"); // Redirige vers la page de confirmation
            exit();
        }
    }

    // Affiche la page de confirmation après une mise à jour de statut
    public function showConfirmation()
    {
        include 'src/app/Views/Admin/events/admin_reservation_confirmation.php'; // Inclut la vue
        unset($_SESSION['message']); // Supprime le message de session après affichage
    }

    // Affiche ou génère une facture pour une réservation spécifique
    public function showInvoice($id)
    {
        // Vérifie si l'utilisateur est connecté
        if (!isset($_SESSION['user_role']) || empty($_SESSION['user_role'])) {
            $code_erreur = 401;
            $description_erreur = "Vous n'avez pas les droits pour accéder à cette page.";
            include('src/app/Views/erreur.php');
            exit();
        }

        if ($_SESSION['user_role'] === 'admin') {
            $reservation = $this->reservationModel->getReservationById($id); // Récupère la réservation
            if (!$reservation) {
                $code_erreur = 404;
                $description_erreur = "Oups... Réservation introuvable.";
                include('src/app/Views/erreur.php');
                exit();
            }

            // Vérifie si la facture existe, sinon la génère
            if (!empty($reservation['invoice_path']) && file_exists($reservation['invoice_path'])) {
                $filePath = $reservation['invoice_path'];
            } else {
                if ($reservation['type'] === 'pack') {
                    $packDetails = $this->reservationModel->getPackDetails($reservation['pack_id']);
                    $reservation['pack_title'] = $packDetails['title'];
                    $reservation['pack_price'] = $packDetails['price'];
                }
                $filePath = \Controllers\InvoiceGenerator::generateInvoice($reservation);
                $this->reservationModel->updateInvoicePath($id, $reservation['type'], $filePath);
            }

            // Envoie le fichier PDF au navigateur
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="facture_' . $id . '.pdf"');
            readfile($filePath);
            exit();
        } else {
            // Logique pour les utilisateurs non-admin (clients)
            $reservations = $this->reservationModel->getReservationsByCustomerId($_SESSION['id']);
            $id_reservations = array_column($reservations, 'id');
            if (in_array($id, $id_reservations)) {
                $reservation = $this->reservationModel->getReservationById($id);
                if (!$reservation) {
                    $code_erreur = 404;
                    $description_erreur = "Oups... Réservation introuvable.";
                    include('src/app/Views/erreur.php');
                    exit();
                }

                // Vérifie ou génère la facture
                if (!empty($reservation['invoice_path']) && file_exists($reservation['invoice_path'])) {
                    $filePath = $reservation['invoice_path'];
                } else {
                    if ($reservation['type'] === 'pack') {
                        $packDetails = $this->reservationModel->getPackDetails($reservation['pack_id']);
                        $reservation['pack_title'] = $packDetails['title'];
                        $reservation['pack_price'] = $packDetails['price'];
                    }
                    $filePath = \Controllers\InvoiceGenerator::generateInvoice($reservation);
                    $this->reservationModel->updateInvoicePath($id, $reservation['type'], $filePath);
                }

                header('Content-Type: application/pdf');
                header('Content-Disposition: inline; filename="facture_' . $id . '.pdf"');
                readfile($filePath);
                exit();
            } else {
                $code_erreur = 401;
                $description_erreur = "Vous n'avez pas les droits pour accéder à cette page.";
                include('src/app/Views/erreur.php');
                exit();
            }
        }
    }

    // Annule une réservation spécifique
    public function cancelReservation($id)
    {
        $reservation = $this->reservationModel->getReservationById($id); // Récupère la réservation
        if (!$reservation) die("Réservation introuvable.");

        $this->reservationModel->updateReservationStatus($id, 'canceled'); // Met à jour le statut
        header('Location: admin/reservations'); // Redirige vers la liste des réservations
        exit();
    }
}
?>