<?php

namespace Models;

class ReservationModel extends ModeleParent
{
    public function addEventReservation($name, $email, $phone, $participants, $event_id)
    {
        $stmt = $this->pdo->prepare("INSERT INTO event_reservations (name, email, phone, participants, event_id) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$name, $email, $phone, $participants, $event_id]);
    }

    public function addPackReservation($name, $email, $phone, $pack_id)
    {
        $stmt = $this->pdo->prepare("INSERT INTO pack_reservations (name, email, phone, pack_id) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$name, $email, $phone, $pack_id]);
    }
}
