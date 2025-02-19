<?php

namespace Controllers;

use PDO;
use PDOException;
use Controllers\DatabaseShop; // ← Ajoute ceci


ini_set('display_errors', 1);
error_reporting(E_ALL);

// Vérifier si l'utilisateur existe déjà
function userExists($pdo, $email) {
    include 'src/app/Views/Public/inscription_shop.php'; // Charge la page d'inscription
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE email = :email');
    $stmt->execute(['email' => $email]);
    return $stmt->fetchColumn() > 0; // Renvoie true si l'email est déjà utilisé
}

// Traitement du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et validation des données
    $name = htmlspecialchars(trim($_POST['name']));
    $surname = htmlspecialchars(trim($_POST['surname']));
    $adresse = htmlspecialchars(trim($_POST['adresse']));
    $number_phone = htmlspecialchars(trim($_POST['number_phone']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));
    $confirm_password = htmlspecialchars(trim($_POST['confirm_password']));

    // Vérifications des champs
    if (empty($name) || empty($surname) || empty($adresse) || empty($number_phone) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = "Tous les champs doivent être remplis.";
        header('Location: /site_stage/chic-and-chill/inscription_shop');
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "L'adresse email est invalide.";
        header('Location: /site_stage/chic-and-chill/inscription_shop');
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
        header('Location: /site_stage/chic-and-chill/inscription_shop');
        exit();
    }

    try {
        $pdo = DatabaseShop::getConnection();

        // Vérification si l'utilisateur existe déjà
        if (userExists($pdo, $email)) {
            $_SESSION['error'] = "Email déjà utilisé.";
            header('Location: /site_stage/chic-and-chill/inscription_shop');
            exit();
        }

        // Hachage du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insertion dans la base de données
        $stmt = $pdo->prepare('
            INSERT INTO users (name, surname, adresse, number_phone, email, password, role, created_at) 
            VALUES (:name, :surname, :adresse, :number_phone, :email, :password, :role, NOW())'
        );
        $stmt->execute([
            'name' => $name,
            'surname' => $surname,
            'adresse' => $adresse,
            'number_phone' => $number_phone,
            'email' => $email,
            'password' => $hashedPassword,
            'role' => 'client' // Par défaut, l'utilisateur est un client
        ]);

        $_SESSION['message'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        header('Location: /site_stage/chic-and-chill/connexion_shop');
        exit();

    } catch (\PDOException $e) {
        $_SESSION['error'] = "Erreur lors de l'inscription : " . $e->getMessage();
        header('Location: /site_stage/chic-and-chill/inscription_shop');
        exit();
    }
}
?>
