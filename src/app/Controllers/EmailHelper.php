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
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP            $mail->Host = $_ENV['SMTP_HOST'];
            $mail->Host       = $_ENV["SMTP_HOST"];                     //Set the SMTP server to send through
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['SMTP_USER'];
            $mail->Password = $_ENV['SMTP_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port = $_ENV['SMTP_PORT'];

            // Expéditeur et destinataire
            $mail->setFrom($_ENV['SMTP_FROM'], 'Chic & Chill');
            $mail->addAddress($to);

            // Contenu de l'email
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            // Ajouter une pièce jointe (facture PDF)
            if ($attachmentPath && file_exists($attachmentPath)) {
                $mail->addAttachment($attachmentPath);
            }

            // Envoi de l'email
            return $mail->send();
        } catch (Exception $e) {
            error_log("Erreur d'envoi d'email : " . $mail->ErrorInfo);
            return false;
        }
    }
}
?>
