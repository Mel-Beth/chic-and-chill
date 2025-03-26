<?php
namespace Models;

class RentalModel extends ModeleParent
{
    public function getAllLocation()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE category = 'location'");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id=:id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function addRental($data)
    {
        $sql = "INSERT INTO rentals (user_id, product_id, start_date, end_date, total_price, status)
                VALUES (:user_id, :product_id, :start_date, :end_date, :total_price, :status)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }
}