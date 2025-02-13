<?php

namespace Models;

class ReservationModel extends ModeleParent
{
    public function addEventReservation($customer_type, $company_name, $siret, $address, $name, $email, $phone, $event_type, $participants, $services, $comments, $event_id)
    {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO event_reservations (customer_type, company_name, siret, address, customer_name, email, phone, event_type, participants, services, comments, event_id, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
            ");
            $stmt->execute([$customer_type, $company_name, $siret, $address, $name, $email, $phone, $event_type, $participants, $services, $comments, $event_id]);
            return true;
        } catch (\PDOException $e) {
            error_log("Erreur lors de la réservation : " . $e->getMessage());
            return false;
        }
    }

    public function addPackReservation($customer_type, $company_name, $siret, $address, $name, $email, $phone, $services, $comments, $pack_id)
    {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO pack_reservations (customer_type, company_name, siret, address, customer_name, email, phone, services, comments, pack_id, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
            ");
            $stmt->execute([$customer_type, $company_name, $siret, $address, $name, $email, $phone, $services, $comments, $pack_id]);
            return true;
        } catch (\PDOException $e) {
            error_log("Erreur lors de la réservation du pack : " . $e->getMessage());
            return false;
        }
    }
}
