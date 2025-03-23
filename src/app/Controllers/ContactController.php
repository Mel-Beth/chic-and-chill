<?php

namespace Controllers;

use Models\ContactModel;
use Models\NotificationModel;

class ContactController
{
    private $contactModel;
    private $notificationModel;

    public function __construct()
    {
        $this->contactModel = new ContactModel();
        $this->notificationModel = new NotificationModel(); // Ajout du modèle de notification
    }

    public function processContactForm()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            die("Méthode non autorisée.");
        }

        if (!isset($_POST["name"], $_POST["email"], $_POST["message"], $_POST["source"])) {
            die("Données incomplètes.");
        }

        $name = htmlspecialchars($_POST["name"]);
        $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
        $message = htmlspecialchars($_POST["message"]);
        $source = in_array($_POST["source"], ["magasin", "location", "evenements"]) ? $_POST["source"] : "magasin";

        if (!$email) {
            die("Adresse e-mail invalide.");
        }

        $success = $this->contactModel->addMessage($name, $email, $message, $source);
        error_log("Ajout message contact : " . ($success ? "Succès" : "Échec"));

        if ($success) {
            $notifSuccess = $this->notificationModel->createNotification("Nouveau message de $name via $source");
            error_log("Appel createNotification contact : " . ($notifSuccess ? "Succès" : "Échec"));
            header("Location: " . "contact_" . $source . "?success=1");
            exit();
        } else {
            die("Erreur lors de l'envoi du message.");
        }
    }

    public function manageMessages()
    {
        $messages = $this->contactModel->getAllMessages();
        include 'src/app/Views/admin/admin_messages.php';
    }

    public function deleteMessage($id)
    {
        $success = $this->contactModel->deleteMessage($id);

        if ($success) {
            header("Location: ../?success=1&action=delete");
            exit();
        } else {
            header("Location: ../?success=0&action=delete");
            exit();
        }
    }

    public function unreadCount()
    {
        $unreadCount = $this->contactModel->countUnreadMessages();
        echo json_encode(["unread" => $unreadCount]);
        exit;
    }

    public function markAsRead($id)
    {
        if ($this->contactModel->markMessageAsRead($id)) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false]);
        }
        exit;
    }

    public function updateMessageStatus($id)
    {
        if (!isset($_GET['status']) || !in_array($_GET['status'], ['read', 'unread'])) {
            echo json_encode(['success' => false, 'error' => 'Statut invalide']);
            exit;
        }

        $status = $_GET['status'];

        if ($this->contactModel->updateMessageStatus($id, $status)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Erreur lors de la mise à jour']);
        }
        exit;
    }

    public function replyToMessage($messageId)
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            die("Méthode non autorisée.");
        }

        if (!isset($_POST["reply_body"])) {
            die("Le corps de la réponse est requis.");
        }

        $replyBody = htmlspecialchars($_POST["reply_body"]);

        // Récupérer le message original
        $message = $this->contactModel->getMessageById($messageId);
        if (!$message) {
            die("Message introuvable.");
        }

        $to = $message['email'];
        $subject = "Réponse à votre message - Chic & Chill";

        // Corps de l’email avec un design similaire à updateReservationStatus
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
                <td style="background-color: #8B5A2B; padding: 20px; border-bottom: 1px solid #e0e0e0;">
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="text-align: center;">
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
                    <h2 style="color: #28a745; margin: 0 0 15px;">Réponse à votre message</h2>
                    <p style="margin: 0 0 15px;">Bonjour ' . htmlspecialchars($message['name']) . ',</p>
                    <p style="margin: 0 0 15px;">Merci pour votre message. Voici notre réponse :</p>

                    <!-- Section réponse -->
                    <table width="100%" cellpadding="5" cellspacing="0" style="border: 1px solid #e0e0e0; border-radius: 5px; background-color: #f9f9f9;">
                        <tr>
                            <td style="font-weight: bold; width: 30%;">Votre message :</td>
                            <td>' . nl2br(htmlspecialchars($message['message'])) . '</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Notre réponse :</td>
                            <td>' . nl2br($replyBody) . '</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Source :</td>
                            <td>' . ucfirst(htmlspecialchars($message['source'])) . '</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Date de votre message :</td>
                            <td>' . $message['created_at'] . '</td>
                        </tr>
                    </table>

                    <p style="margin: 15px 0 0;">Pour toute question supplémentaire, n’hésitez pas à nous contacter à l’adresse <a href="mailto:contact@chicandchill.fr" style="color: #8B5A2B; text-decoration: none;">contact@chicandchill.fr</a>.</p>
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

        // Envoyer l'email
        $success = EmailHelper::sendReplyEmail($to, $subject, $body, $message['message']);
        error_log("Envoi email: " . ($success ? "Succès" : "Échec"));

        if ($success) {
            $replied = $this->contactModel->markMessageAsReplied($messageId, $replyBody);
            error_log("markMessageAsReplied: " . ($replied ? "Succès" : "Échec"));
            $notif = $this->notificationModel->createNotification("Réponse envoyée à " . $message['name']);
            error_log("createNotification: " . ($notif ? "Succès" : "Échec"));
            header("Location: ../?success=1&action=reply");
            exit();
        } else {
            die("Erreur lors de l'envoi de la réponse.");
        }
    }

    // Méthode pour afficher le formulaire de réponse
    public function showReplyForm($messageId)
    {
        $message = $this->contactModel->getMessageById($messageId);
        if (!$message) {
            die("Message introuvable.");
        }
        include 'src/app/Views/admin/reply_message.php';
    }
}
