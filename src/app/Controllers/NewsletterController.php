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
        $subscribers = $this->newsletterModel->getAllSubscribers();
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
        error_log("Début processNewsletter");
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
                // Envoi de l'email de confirmation
                $subject = "Bienvenue chez Chic & Chill !";
                $body = "
                    <html>
                    <body style='font-family: Arial, sans-serif; color: #333; background-color: #f5f5f5; padding: 20px;'>
                        <div style='max-width: 600px; margin: 0 auto; background-color: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);'>
                            <h1 style='color: #8B5A2B; text-align: center;'>Bienvenue dans l'univers Chic & Chill</h1>
                            <p style='font-size: 16px; line-height: 1.5;'>Merci de vous être inscrit(e) à notre newsletter ! Vous êtes désormais prêt(e) à découvrir nos trésors de friperie chic et chill : des vêtements uniques, des événements exclusifs et bien plus encore.</p>
                            <p style='font-size: 16px; line-height: 1.5;'>Restez à l’affût de nos prochaines nouveautés et profitez d’une mode élégante et responsable.</p>
                            <p style='text-align: center; margin: 20px 0;'>
                                <a href='" . BASE_URL . "accueil' style='background-color: #8B5A2B; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Découvrir nos événements</a>
                            </p>
                            <p style='font-size: 14px; color: #777;'>Si vous ne souhaitez plus recevoir nos emails, vous pouvez vous <a href='" . BASE_URL . "newsletter/unsubscribe?email=" . urlencode($email) . "' style='color: #8B5A2B;'>désinscrire ici</a>.</p>
                        </div>
                    </body>
                    </html>
                ";
                $emailSent = EmailHelper::sendEmail($email, $subject, $body);

                if ($emailSent) {
                    error_log("Email de confirmation envoyé avec succès à : $email");
                } else {
                    error_log("Échec de l'envoi de l'email de confirmation à : $email");
                }

                $notifSuccess = $this->notificationModel->createNotification("Nouvel abonné à la newsletter : $email");
                error_log("Appel createNotification newsletter : " . ($notifSuccess ? "Succès" : "Échec") . " - Email : $email");

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

    // Nouvelle méthode pour gérer la désinscription
    public function unsubscribe()
    {
        if (isset($_GET['email'])) {
            $email = filter_var($_GET['email'], FILTER_VALIDATE_EMAIL);
            if ($email && $this->newsletterModel->deleteSubscriberByEmail($email)) {
                echo "<p style='text-align: center; padding: 20px;'>Vous avez été désinscrit(e) avec succès de la newsletter Chic & Chill.</p>";
            } else {
                echo "<p style='text-align: center; padding: 20px;'>Erreur lors de la désinscription. Veuillez réessayer ou contacter le support.</p>";
            }
        }
        exit();
    }

    public function sendMonthlyNewsletter()
    {
        $subscribers = $this->newsletterModel->getAllSubscribers();
        if (empty($subscribers)) {
            error_log("Aucun abonné pour la newsletter mensuelle.");
            return;
        }

        // Tableau des mois en français
        $moisFrancais = [
            1 => 'janvier',
            2 => 'février',
            3 => 'mars',
            4 => 'avril',
            5 => 'mai',
            6 => 'juin',
            7 => 'juillet',
            8 => 'août',
            9 => 'septembre',
            10 => 'octobre',
            11 => 'novembre',
            12 => 'décembre'
        ];

        // Récupérer le mois actuel en français et l'année
        $moisActuel = $moisFrancais[(int)date('n')]; // 'n' donne le numéro du mois sans zéro initial
        $anneeActuelle = date('Y');
        $subject = "Chic & Chill - Votre Newsletter Mensuelle de $moisActuel $anneeActuelle";

        // Contenu de la newsletter
        $body = "
            <html>
            <body style='font-family: Arial, sans-serif; color: #333; background-color: #f5f5f5; padding: 20px;'>
                <div style='max-width: 600px; margin: 0 auto; background-color: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);'>
                    <h1 style='color: #8B5A2B; text-align: center;'>Chic & Chill - Newsletter $moisActuel $anneeActuelle</h1>
                    <p style='font-size: 16px; line-height: 1.5;'>Bonjour,</p>
                    <p style='font-size: 16px; line-height: 1.5;'>Découvrez les dernières nouveautés de notre friperie chic et chill : vêtements uniques, locations élégantes et événements à ne pas manquer.</p>

                    <h2 style='color: #8B5A2B; margin-top: 20px;'>🛍️ Achat</h2>
                    <p style='font-size: 16px;'>Nouvelles pépites dans notre boutique : robes vintage, vestes oversized et plus encore. <a href='" . BASE_URL . "accueil_shop' style='color: #8B5A2B;'>Visitez le shop</a>.</p>

                    <h2 style='color: #8B5A2B; margin-top: 20px;'>📍 Location</h2>
                    <p style='font-size: 16px;'>Louez une tenue pour votre prochain événement sans casser votre tirelire. <a href='" . BASE_URL . "location' style='color: #8B5A2B;'>Découvrez nos options</a>.</p>

                    <h2 style='color: #8B5A2B; margin-top: 20px;'>🎉 Événements & Showroom</h2>
                    <p style='font-size: 16px;'>Rejoignez-nous pour nos prochains événements exclusifs et visitez notre showroom. <a href='" . BASE_URL . "evenements' style='color: #8B5A2B;'>Voir le calendrier</a>.</p>

                    <p style='text-align: center; margin: 20px 0;'>
                        <a href='" . BASE_URL . "evenements' style='background-color: #8B5A2B; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Explorer maintenant</a>
                    </p>
                    <p style='font-size: 14px; color: #777;'>Ne plus recevoir cette newsletter ? <a href='" . BASE_URL . "newsletter/unsubscribe?email={{EMAIL}}' style='color: #8B5A2B;'>Se désinscrire</a></p>
                </div>
            </body>
            </html>
        ";

        foreach ($subscribers as $subscriber) {
            $email = $subscriber['email'];
            $personalizedBody = str_replace('{{EMAIL}}', urlencode($email), $body);
            $success = EmailHelper::sendEmail($email, $subject, $personalizedBody);
            error_log("Newsletter mensuelle envoyée à $email : " . ($success ? "Succès" : "Échec"));
        }
    }
}
