<?php
namespace Models;

use PDO;
use PDOException;
use Controllers\DatabaseShop;

class UserModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = DatabaseShop::getConnection();
    }

    // Vérifie si l'utilisateur existe déjà avec cet email
    public function userExists($email)
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        return $stmt->fetchColumn() > 0; // Retourne true si l'email existe déjà
    }

    // Enregistre un nouvel utilisateur
    public function registerUser($name, $surname, $adresse, $number_phone, $email, $password)
    {
        // Vérifie si l'utilisateur existe déjà
        if ($this->userExists($email)) {
            return "Email déjà utilisé.";
        }

        try {
            // Hachage du mot de passe
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Insérer l'utilisateur dans la base de données
            $stmt = $this->pdo->prepare("
                INSERT INTO users (name, surname, adresse, number_phone, email, password, role, created_at)
                VALUES (:name, :surname, :adresse, :number_phone, :email, :password, :role, NOW())
            ");

            $stmt->execute([
                'name' => $name,
                'surname' => $surname,
                'adresse' => $adresse,
                'number_phone' => $number_phone,
                'email' => $email,
                'password' => $hashedPassword,
                'role' => 'client' // Par défaut, les utilisateurs sont des clients
            ]);

            return true; // Succès
        } catch (PDOException $e) {
            return "Erreur lors de l'inscription : " . $e->getMessage();
        }
    }
}
