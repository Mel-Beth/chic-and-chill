<?php
namespace Models;

class LocationAdminModel extends ModeleParent
{
    public function getAllRentals()
    {
        $sql = "SELECT rentals.*, products.name AS product_name, users.name AS user_name 
                FROM rentals 
                JOIN products ON rentals.product_id = products.id 
                JOIN users ON rentals.user_id = users.id 
                ORDER BY rentals.start_date DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status)
    {
        $stmt = $this->pdo->prepare("UPDATE rentals SET status = :status WHERE id = :id");
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

    public function deleteRental($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM rentals WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
