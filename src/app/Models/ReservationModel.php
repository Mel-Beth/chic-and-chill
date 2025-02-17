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

    public function getAllReservations()
    {
        try {
            $stmt = $this->pdo->query("
                SELECT 'event' AS type, id, customer_name, email, phone, event_id, status, created_at 
                FROM event_reservations
                UNION
                SELECT 'pack' AS type, id, customer_name, email, phone, pack_id, status, created_at 
                FROM pack_reservations
                ORDER BY created_at DESC
            ");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function getReservationById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM reservations WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function updateReservationStatus($id, $status)
    {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE event_reservations SET status = ? WHERE id = ?
            ");
            return $stmt->execute([$status, $id]);
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    public function getRecentReservations()
    {
        try {
            $stmt = $this->pdo->query("
            (SELECT 'event' AS type, id, customer_name, email, created_at 
            FROM event_reservations ORDER BY created_at DESC LIMIT 5)
            UNION
            (SELECT 'pack' AS type, id, customer_name, email, created_at 
            FROM pack_reservations ORDER BY created_at DESC LIMIT 5)
            ORDER BY created_at DESC
        ");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }
}
