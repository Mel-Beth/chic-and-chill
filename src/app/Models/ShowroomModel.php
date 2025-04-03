<?php
namespace Models;

use PDO;

class ShowroomModel
{
    private $pdo;

    public function __construct()
    {
        try {
            $this->pdo = new PDO("mysql:host=localhost;dbname=chicandchill;charset=utf8", "root", "");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    public function saveReservation($data)
    {
        $sql = "INSERT INTO showroom_reservations (client_nom, email, telephone, date_reservation, heure_reservation, service, message, statut)
                VALUES (:client_nom, :email, :telephone, :date_reservation, :heure_reservation, :service, :message, 'en_attente')";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function getAllReservations()
    {
        $stmt = $this->pdo->query("SELECT * FROM showroom_reservations ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}