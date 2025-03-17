<?php

namespace Models;

class UserInfosModelShop extends ModeleParent
{
 
    public function getUserById($userId)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, identifiant, name, surname, email, adresse, number_phone, role
                FROM users 
                WHERE id = :id
            ");
            $stmt->execute(['id' => $userId]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \PDOException("Erreur lors de la récupération de l'utilisateur : " . $e->getMessage());
        }
    }
}
