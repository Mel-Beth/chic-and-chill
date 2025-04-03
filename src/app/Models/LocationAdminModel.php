<?php
namespace Models;

class LocationAdminModel
{
    private $pdo;

    public function __construct()
    {
        try {
            $this->pdo = new \PDO("mysql:host=localhost;dbname=chicandchill;charset=utf8", "root", "");
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    // Récupérer toutes les réservations de location
    public function getAllReservations()
    {
        $sql = "SELECT lr.*, p.name AS product_name 
                FROM location_reservations lr 
                JOIN products p ON lr.produit_id = p.id 
                ORDER BY lr.created_at DESC";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Mettre à jour le statut d'une réservation
    public function updateStatus($id, $status)
    {
        $sql = "UPDATE location_reservations SET statut = :status WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

    // Supprimer une réservation
    public function deleteReservation($id)
    {
        $sql = "DELETE FROM location_reservations WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}