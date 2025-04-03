<?php
namespace Models;

class LocationModel
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

    public function getAllRobeProducts($color = null)
    {
        $sql = "SELECT * FROM products WHERE is_rental = 1";

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
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :id AND is_rental = 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function addReservation($data)
    {
        $sql = "INSERT INTO location_reservations (client_nom, email, produit_id, date_debut, date_fin, statut, created_at)
                VALUES (:client_nom, :email, :produit_id, :date_debut, :date_fin, :statut, NOW())";

        $stmt = $this->pdo->prepare($sql);

        if (!$stmt->execute([
            ':client_nom' => $data['client_nom'],
            ':email' => $data['email'],
            ':produit_id' => $data['produit_id'],
            ':date_debut' => $data['date_debut'],
            ':date_fin' => $data['date_fin'],
            ':statut' => 'en attente'
        ])) {
            die("Erreur lors de l'insertion de la réservation : " . implode(" - ", $stmt->errorInfo()));
        }

        return true;
    }
}