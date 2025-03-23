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
}
