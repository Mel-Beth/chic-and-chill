<?php
namespace Models;

class ShowroomModel extends ModeleParent
{
    public function checkReservationAvailability($date, $heure)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM reservations WHERE date = :date AND heure = :heure");
        $stmt->execute([':date' => $date, ':heure' => $heure]);
        return $stmt->fetchColumn() > 0;
    }

    public function addReservation($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO reservations (date, heure, client_nom, email, statut) 
                                    VALUES (:date, :heure, :client_nom, :email, :statut)");
        return $stmt->execute($data);
    }
}
