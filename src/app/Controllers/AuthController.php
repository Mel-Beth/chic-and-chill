<?php

namespace Controllers;

use Models\AuthModel;

class AuthController
{
    private $authModel;

    public function __construct()
    {
        $this->authModel = new AuthModel();
    }


    public function logout()
    {
        session_start(); // Démarrer la session
        session_unset(); // Supprime toutes les variables de session
        session_destroy(); // Détruit la session
        
        // Redirection vers la page d'accueil
        header("Location: ../accueil_shop");
        exit;
    }

    public function forgotPassword()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);

            if (!$email) {
                die("Adresse e-mail invalide.");
            }

            $user = $this->authModel->getUserByEmail($email);
            if (!$user) {
                die("Aucun compte trouvé avec cet e-mail.");
            }

            $token = bin2hex(random_bytes(50));
            $this->authModel->storePasswordResetToken($email, $token);

            $resetLink = "http://localhost/reset-password?token=" . $token;
            mail($email, "Réinitialisation de mot de passe", "Cliquez ici pour réinitialiser votre mot de passe : " . $resetLink);

            echo "Un e-mail a été envoyé avec un lien de réinitialisation.";
            exit();
        }

        include 'src/app/Views/Auth/forgot_password.php';
    }

    public function resetPassword()
    {
        if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["token"])) {
            $token = $_GET["token"];
            $resetRequest = $this->authModel->getPasswordResetToken($token);

            if (!$resetRequest) {
                die("Lien invalide ou expiré.");
            }

            include 'src/app/Views/Auth/reset_password.php';
            exit();
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $token = $_POST["token"];
            $newPassword = $_POST["password"];
            $confirmPassword = $_POST["confirm_password"];

            if ($newPassword !== $confirmPassword) {
                die("Les mots de passe ne correspondent pas.");
            }

            $resetRequest = $this->authModel->getPasswordResetToken($token);
            if (!$resetRequest) {
                die("Lien invalide ou expiré.");
            }

            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $this->authModel->updatePasswordByEmail($resetRequest["email"], $hashedPassword);
            $this->authModel->deletePasswordResetToken($resetRequest["email"]);

            echo "Mot de passe réinitialisé avec succès.";
            exit();
        }
    }

    public function sendVerificationEmail($email)
    {
        $token = bin2hex(random_bytes(50));
        $this->authModel->storeVerificationToken($email, $token);

        $verifyLink = "http://localhost/verify-email?token=" . $token;
        mail($email, "Vérifiez votre e-mail", "Cliquez ici pour valider votre compte : " . $verifyLink);
    }

    public function verifyEmail()
    {
        if (isset($_GET["token"])) {
            $token = $_GET["token"];
            if ($this->authModel->verifyEmail($token)) {
                echo "Votre e-mail a été vérifié.";
            } else {
                echo "Lien invalide ou expiré.";
            }
        }
    }

    public function logout()
    {
        session_start(); // Démarrer la session
        session_unset(); // Supprime toutes les variables de session
        session_destroy(); // Détruit la session

        // Redirection vers la page d'accueil
        header("Location: ../accueil_shop");
        exit;
    }
}
