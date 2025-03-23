<?php

namespace Controllers;

use Models\NewsletterModel;
use Models\NotificationModel;

class NewsletterController
{
    private $newsletterModel;
    private $notificationModel;

    public function __construct()
    {
        $this->newsletterModel = new NewsletterModel();
        $this->notificationModel = new NotificationModel();
    }

    public function manageNewsletter()
    {
        $newsletterModel = new NewsletterModel();
        $subscribers = $newsletterModel->getAllSubscribers();
        include('src/app/Views/admin/admin_newsletter.php');
    }

    public function deleteSubscriber($id)
    {
        $success = $this->newsletterModel->deleteSubscriber($id);

        if ($success) {
            header("Location: ../?success=1&action=delete");
            exit();
        } else {
            header("Location: ../?success=0&action=delete");
            exit();
        }
    }

    public function processNewsletter()
    {
        error_log("Début processNewsletter"); // Log initial
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["email"])) {
            $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);

            if (!$email) {
                error_log("Inscription newsletter : Email invalide");
                die("Adresse e-mail invalide.");
            }

            error_log("Avant ajout abonné - Email : $email");
            $success = $this->newsletterModel->addNewsletterSubscription($email);
            error_log("Ajout abonné newsletter : " . ($success ? "Succès" : "Échec") . " - Email : $email");

            if ($success) {
                error_log("Avant création notification - Email : $email");
                $notifSuccess = $this->notificationModel->createNotification("Nouvel abonné à la newsletter : $email");
                error_log("Appel createNotification newsletter : " . ($notifSuccess ? "Succès" : "Échec") . " - Email : $email");
                if (!$notifSuccess) {
                    error_log("Échec création notification : Vérification supplémentaire nécessaire");
                }
                header("Location: evenements?success=1");
                exit();
            } else {
                error_log("Erreur inscription newsletter : Échec ajout abonné");
                die("Erreur lors de l'inscription.");
            }
        } else {
            error_log("Inscription newsletter : Requête invalide ou email manquant");
            die("Requête invalide.");
        }
    }
}
