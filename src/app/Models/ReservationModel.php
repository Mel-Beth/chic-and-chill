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

    public function getReservationById($id, $type = null)
    {
        if ($type === 'event' || !$type) {
            $stmt = $this->pdo->prepare("SELECT *, 'event' AS type FROM event_reservations WHERE id = ?");
            $stmt->execute([$id]);
            $reservation = $stmt->fetch();
            if ($reservation && ($type === 'event' || !$type)) {
                return $reservation;
            }
        }

        if ($type === 'pack' || !$type) {
            $stmt = $this->pdo->prepare("SELECT *, 'pack' AS type FROM pack_reservations WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        }

        return false;
    }

    public function updateReservationStatus($id, $status, $type = null)
    {
        try {
            // Si le type est fourni, cibler directement la bonne table
            if ($type === 'event') {
                $table = 'event_reservations';
            } elseif ($type === 'pack') {
                $table = 'pack_reservations';
            } else {
                // Sinon, chercher la réservation pour déterminer la table
                $reservation = $this->getReservationById($id);
                if (!$reservation) {
                    die("Impossible de mettre à jour : réservation avec ID $id introuvable.");
                }
                $table = $reservation['type'] === 'event' ? 'event_reservations' : 'pack_reservations';
            }

            $stmt = $this->pdo->prepare("UPDATE $table SET status = ? WHERE id = ?");
            $success = $stmt->execute([$status, $id]);

            if (!$success) {
                die("Échec de la mise à jour dans $table pour ID $id avec status $status.");
            }
            return true;
        } catch (\PDOException $e) {
            die("Erreur SQL lors de la mise à jour du statut : " . $e->getMessage());
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
