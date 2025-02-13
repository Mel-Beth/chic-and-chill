<?php

namespace Controllers;

use Models\ContactModel;

class ContactController {
    public function processContactForm() {
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
            header("Location: " . BASE_URL . "contact_" . $source . "?success=1");
            exit();
        } else {
            die("Erreur lors de l'envoi du message.");
        }
    }

    public function processNewsletter() {
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["email"])) {
            $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);

            if (!$email) {
                die("Adresse e-mail invalide.");
            }

            $contactModel = new ContactModel();
            $success = $contactModel->addNewsletterSubscription($email);

            if ($success) {
                header("Location: " . BASE_URL . "evenements?success=1");
                exit();
            } else {
                die("Erreur lors de l'inscription.");
            }
        }
    }
}
