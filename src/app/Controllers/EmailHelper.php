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
            // Paramètres du serveur SMTP
            $mail->SMTPDebug = 0; // Désactiver le debug par défaut (0 = off, utiliser SMTP::DEBUG_SERVER en dev si besoin)
            $mail->isSMTP();
            $mail->Host = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['SMTP_USER'];
            $mail->Password = $_ENV['SMTP_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL/TLS selon votre config
            $mail->Port = $_ENV['SMTP_PORT'];

            // Définir l’encodage UTF-8
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64'; // Optionnel, améliore la compatibilité

            // Expéditeur et destinataire
            $mail->setFrom($_ENV['SMTP_FROM_EMAIL'], 'Chic & Chill');
            $mail->addAddress($to);

            // Contenu de l’email
            $mail->isHTML(true);
            $mail->Subject = $subject; // Le sujet est déjà en UTF-8, pas besoin d’encodage supplémentaire ici

            $mail->Body = $body;

            // Ajouter une pièce jointe (facture PDF)
            if ($attachmentPath && file_exists($attachmentPath) && str_starts_with($attachmentPath, 'assets/documents/invoices/')) {
                $mail->addAttachment($attachmentPath);
            }

            // Envoi de l’email
            $mail->send();
            return true; // Retourner explicitement true en cas de succès

        } catch (Exception $e) {
            error_log("Erreur d'envoi d'email : " . $mail->ErrorInfo);
            return false; // Retourner false en cas d’échec
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
