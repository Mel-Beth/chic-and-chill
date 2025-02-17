<?php

namespace Controllers;

use Models\ContactModel;

class ContactController
{
    private $contactModel;

    public function __construct()
    {
        $this->contactModel = new ContactModel();
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

        $contactModel = new ContactModel();
        $success = $contactModel->addMessage($name, $email, $message, $source);

        if ($success) {
            header("Location: " . "contact_" . $source . "?success=1");
            exit();
        } else {
            die("Erreur lors de l'envoi du message.");
        }
    }

    public function processNewsletter()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["email"])) {
            $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);

            if (!$email) {
                die("Adresse e-mail invalide.");
            }

            $contactModel = new ContactModel();
            $success = $contactModel->addNewsletterSubscription($email);

            if ($success) {
                header("Location: " . "evenements?success=1");
                exit();
            } else {
                die("Erreur lors de l'inscription.");
            }
        }
    }

    public function manageMessages()
    {
        $messages = $this->contactModel->getAllMessages();
        include 'src/app/Views/Admin/admin_messages.php';
    }

    public function deleteMessage($id)
    {
        if ($this->contactModel->deleteMessage($id)) {
            header('Location: admin/messages');
            exit();
        } else {
            die("Erreur lors de la suppression du message.");
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
