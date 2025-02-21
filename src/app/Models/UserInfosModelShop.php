<?php

namespace Models;

use PDO;
use PDOException;
use Controllers\DatabaseShop;
require_once 'src/app/Controllers/DatabaseShop.php';

class UserInfosModelShop
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = DatabaseShop::getConnection();
    }

    public function getUserById($userId)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, identifiant, name, surname, email, adresse, number_phone, role
                FROM users 
                WHERE id = :id
            ");
            $stmt->execute(['id' => $userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException("Erreur lors de la récupération de l'utilisateur : " . $e->getMessage());
        }
    }
}
