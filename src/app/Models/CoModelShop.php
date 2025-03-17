<?php

namespace Models;



class CoModelShop extends ModeleParent
{


    public function getUserByIdentifierOrEmail($identifier)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, identifiant, name, surname, email, password, role, adresse, number_phone
                FROM users 
                WHERE email = :identifier OR identifiant = :identifier
            ");
            $stmt->execute(['identifier' => $identifier]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \PDOException("Erreur lors de la récupération de l'utilisateur : " . $e->getMessage());
        }
    }
}
