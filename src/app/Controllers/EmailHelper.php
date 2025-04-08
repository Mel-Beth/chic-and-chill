<?php

namespace Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class EmailHelper
{
    public static function sendEmail($to, $subject, $body, $attachmentPath = null)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['SMTP_USER'];
            $mail->Password = $_ENV['SMTP_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = $_ENV['SMTP_PORT'];

            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            $mail->AddEmbeddedImage('assets/images/logo_magasin-chic.png', 'logoCID');
            $mail->setFrom($_ENV['SMTP_FROM_EMAIL'], 'Chic & Chill');
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            if ($attachmentPath) {
                error_log("Tentative d’ajout de la pièce jointe : $attachmentPath");
                if (file_exists($attachmentPath)) {
                    $mail->addAttachment($attachmentPath, 'facture.pdf');
                    error_log("Pièce jointe ajoutée avec succès : $attachmentPath");
                } else {
                    error_log("Erreur : Le fichier $attachmentPath n’existe pas ou est inaccessible.");
                }
            } else {
                error_log("Aucune pièce jointe fournie.");
            }

            $mail->send();
            error_log("Email envoyé avec succès à $to");
            return true;

        } catch (Exception $e) {
            error_log("Erreur d’envoi d’email à $to : " . $mail->ErrorInfo);
            return false;
        }
    }

    public static function sendReplyEmail($to, $subject, $body, $originalMessage = null)
    {
        $mail = new PHPMailer(true);

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

            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            // Expéditeur et destinataire
            $mail->AddEmbeddedImage('assets/images/logo_magasin-chic.png', 'logoCID');
            $mail->setFrom($_ENV['SMTP_FROM_EMAIL'], 'Chic & Chill - Support');
            $mail->addAddress($to);

            // Contenu de l’email
            $mail->isHTML(true);
            $mail->Subject = $subject;

            // Ajouter le message original en citation (optionnel)
            $fullBody = $body;
            if ($originalMessage) {
                $fullBody .= "<br><br><hr><blockquote style='color: #555; font-style: italic;'>Message original :<br>" . nl2br(htmlspecialchars($originalMessage)) . "</blockquote>";
            }
            $mail->Body = $fullBody;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Erreur d'envoi de réponse : " . $mail->ErrorInfo);
            return false;
        }
    }
}
