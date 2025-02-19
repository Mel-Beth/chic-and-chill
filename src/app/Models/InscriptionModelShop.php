<?php
namespace Models;
function registerUser($pseudo, $email, $motdepasse, $nom, $prenom, $adresse, $date_naissance, $numero_tel) {
    $pdo = connectDatabase();

    // Hachage du mot de passe
    $hashedPassword = password_hash($motdepasse, PASSWORD_BCRYPT);

    // Vérification de l'existence de l'utilisateur
    if (userExists($pdo, $pseudo, $email)) {
        die('Pseudo ou email déjà utilisé.');
    }

    // Préparer la requête d'insertion
    $stmt = $pdo->prepare("
        INSERT INTO membres (pseudo, mail_client, mdp, nom_client, prenom_client, adresse_client, date_naissance, numero_tel, role, date_inscription)
        VALUES (:pseudo, :email, :mdp, :nom, :prenom, :adresse, :date_naissance, :numero_tel, :role, NOW())
    ");

    // Exécuter la requête
    $stmt->execute([
        'pseudo' => $pseudo,
        'email' => $email,
        'mdp' => $hashedPassword,
        'nom' => $nom,
        'prenom' => $prenom,
        'adresse' => $adresse,
        'date_naissance' => $date_naissance,
        'numero_tel' => $numero_tel,
        'role' => 2 // ID pour le rôle utilisateur
    ]);

    // Redirection après une inscription réussie
    header('Location: ../views/page_connexion.php');
    exit();
}

?>