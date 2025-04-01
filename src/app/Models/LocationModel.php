<?php
namespace Models;

class LocationModel
{
    private $pdo;

    public function __construct()
    {
        try {
            $this->pdo = new \PDO("mysql:host=localhost;dbname=chicandchill;charset=utf8", "root", "");
        } catch (\PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    public function getAllRobeProducts($color = null)
    {
        $sql = "SELECT * FROM products WHERE is_rental = 1 AND (name LIKE '%robe%' OR name LIKE '%robe pull%')";
        
        if ($color) {
            $sql .= " AND color = :color";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':color' => $color]);
        } else {
            $stmt = $this->pdo->query($sql);
        }

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function addReservation($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO location_reservations (client_nom, email, produit_id, date_debut, date_fin) 
                                     VALUES (:client_nom, :email, :produit_id, :date_debut, :date_fin)");
        return $stmt->execute($data);
    }

    public function getColors()
    {
        $stmt = $this->pdo->query("SELECT DISTINCT color FROM products WHERE is_rental = 1 AND color IS NOT NULL");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
