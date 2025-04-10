<?php

namespace Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class EmailHelper
{
    // Envoie un email avec une configuration SMTP et un contenu HTML
    public static function sendEmail($to, $subject, $body, $attachmentPath = null)
    {
        $mail = new PHPMailer(true); // Initialise PHPMailer avec gestion des exceptions

        try {
            // Configuration du débogage (désactivé pour la production)
            $mail->SMTPDebug = 0;
            $mail->isSMTP(); // Utilise le protocole SMTP
            $mail->Host = $_ENV['SMTP_HOST']; // Hôte SMTP depuis les variables d'environnement
            $mail->SMTPAuth = true; // Active l'authentification SMTP
            $mail->Username = $_ENV['SMTP_USER']; // Nom d'utilisateur SMTP
            $mail->Password = $_ENV['SMTP_PASSWORD']; // Mot de passe SMTP
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Utilise SSL/TLS pour la sécurité
            $mail->Port = $_ENV['SMTP_PORT']; // Port SMTP

            // Configuration de l'encodage pour supporter les caractères spéciaux
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            // Ajoute une image intégrée (logo) pour l'email
            $mail->AddEmbeddedImage('assets/images/logo_magasin-chic.png', 'logoCID');
            $mail->setFrom($_ENV['SMTP_FROM_EMAIL'], 'Chic & Chill'); // Définit l'expéditeur
            $mail->addAddress($to); // Ajoute le destinataire

            // Configure l'email comme HTML et définit le sujet et le corps
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            // Ajoute une pièce jointe si fournie
            if ($attachmentPath) {
                error_log("Tentative d’ajout de la pièce jointe : $attachmentPath");
                if (file_exists($attachmentPath)) {
                    $mail->addAttachment($attachmentPath, 'facture.pdf'); // Attache le fichier
                    error_log("Pièce jointe ajoutée avec succès : $attachmentPath");
                } else {
                    error_log("Erreur : Le fichier $attachmentPath n’existe pas ou est inaccessible.");
                }
            } else {
                error_log("Aucune pièce jointe fournie.");
            }

            // Envoie l'email
            $mail->send();
            error_log("Email envoyé avec succès à $to");
            return true; // Retourne vrai si l'envoi réussit
        } catch (Exception $e) {
            // Log l'erreur en cas d'échec
            error_log("Erreur d’envoi d’email à $to : " . $mail->ErrorInfo);
            return false; // Retourne faux si l'envoi échoue
        }
    }

    // Envoie un email de réponse avec un message original en citation (optionnel)
    public static function sendReplyEmail($to, $subject, $body, $originalMessage = null)
    {
        $mail = new PHPMailer(true); // Initialise PHPMailer avec gestion des exceptions

        try {
            // Paramètres SMTP (identiques à sendEmail)
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['SMTP_USER'];
            $mail->Password = $_ENV['SMTP_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = $_ENV['SMTP_PORT'];

            // Configuration de l'encodage
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            // Ajoute une image intégrée (logo) et configure l'expéditeur et le destinataire
            $mail->AddEmbeddedImage('assets/images/logo_magasin-chic.png', 'logoCID');
            $mail->setFrom($_ENV['SMTP_FROM_EMAIL'], 'Chic & Chill - Support');
            $mail->addAddress($to);

            // Configure l'email comme HTML et définit le sujet
            $mail->isHTML(true);
            $mail->Subject = $subject;

            // Ajoute le message original en citation si fourni
            $fullBody = $body;
            if ($originalMessage) {
                $fullBody .= "<br><br><hr><blockquote style='color: #555; font-style: italic;'>Message original :<br>" . nl2br(htmlspecialchars($originalMessage)) . "</blockquote>";
            }
            $mail->Body = $fullBody;

            // Envoie l'email
            $mail->send();
            return true; // Retourne vrai si l'envoi réussit
        } catch (Exception $e) {
            // Log l'erreur en cas d'échec
            error_log("Erreur d'envoi de réponse : " . $mail->ErrorInfo);
            return false; // Retourne faux si l'envoi échoue
        }
    }
}
?>