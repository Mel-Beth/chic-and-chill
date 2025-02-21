<?php

namespace Models;

use PDO;
use PDOException;
use Controllers\DatabaseShop;
require_once 'src/app/Controllers/DatabaseShop.php';

class CoModelShop
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = DatabaseShop::getConnection();
    }

    public function getUserByIdentifierOrEmail($identifier)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, identifiant, name, surname, email, password, role, adresse, number_phone
                FROM users 
                WHERE email = :identifier OR identifiant = :identifier
            ");
            $stmt->execute(['identifier' => $identifier]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException("Erreur lors de la récupération de l'utilisateur : " . $e->getMessage());
        }
    }
}
