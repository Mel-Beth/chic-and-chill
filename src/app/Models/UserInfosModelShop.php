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
    public function getUserOrders($userId)
{
    // desc pr afficher la commande la plus recente ds la page profil user
    try {
        $stmt = $this->pdo->prepare("
            SELECT 
                p.order_id AS id,
                p.amount AS total,
                p.created_at AS date,
                p.status
            FROM payement p
            WHERE p.user_id = :user_id
            ORDER BY p.created_at DESC
        ");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
        throw new \PDOException("Erreur lors de la récupération des commandes : " . $e->getMessage());
    }
}

    public function updateUser($userId, $name, $surname, $email, $adresse, $number_phone)
{
    try {
        $stmt = $this->pdo->prepare("
            UPDATE users 
            SET name = :name, surname = :surname, email = :email, 
                adresse = :adresse, number_phone = :number_phone
            WHERE id = :id
        ");
        $stmt->execute([
            'id' => $userId,
            'name' => $name,
            'surname' => $surname,
            'email' => $email,
            'adresse' => $adresse,
            'number_phone' => $number_phone
        ]);
    } catch (\PDOException $e) {
        throw new \Exception("Erreur lors de la mise à jour : " . $e->getMessage());
    }
}


}
