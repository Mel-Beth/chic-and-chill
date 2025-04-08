<?php

namespace Models\Events;

class ReservationModel extends \Models\ModeleParent
{
    public function addEventReservation(
        $customer_type,
        $company_name,
        $siret,
        $address,
        $name,
        $email,
        $phone,
        $event_type,
        $participants,
        $services,
        $comments,
        $event_id
    ) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO event_reservations (customer_type, company_name, siret, address, customer_name, 
                email, phone, event_type, participants, services, comments, event_id, status, created_at, invoice_path)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW(), NULL)
            ");
            $stmt->execute([
                $customer_type,
                $company_name,
                $siret,
                $address,
                $name,
                $email,
                $phone,
                $event_type,
                $participants,
                $services,
                $comments,
                $event_id
            ]);
            return true;
        } catch (\PDOException $e) {
            error_log("Erreur lors de la réservation : " . $e->getMessage());
            die("Erreur SQL : " . $e->getMessage());
            return false;
        }
    }

    public function addPackReservation(
        $customer_type,
        $company_name,
        $siret,
        $address,
        $name,
        $email,
        $phone,
        $services,
        $comments,
        $pack_id
    ) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO pack_reservations (customer_type, company_name, siret, address, customer_name, 
                email, phone, services, comments, pack_id, status, created_at, invoice_path)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW(), NULL)
            ");
            $stmt->execute([
                $customer_type,
                $company_name,
                $siret,
                $address,
                $name,
                $email,
                $phone,
                $services,
                $comments,
                $pack_id
            ]);
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
                SELECT 'event' AS type, id, customer_type, company_name, siret, address, customer_name, email, phone, event_type, participants, services, comments, event_id, status, created_at, invoice_path 
                FROM event_reservations
                UNION
                SELECT 'pack' AS type, id, customer_type, company_name, siret, address, customer_name, email, phone, NULL AS event_type, NULL AS participants, services, comments, pack_id AS event_id, status, created_at, invoice_path 
                FROM pack_reservations
                ORDER BY created_at DESC
            ");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function getPackDetails($packId)
    {
        try {
            // Log pour déboguer la valeur de packId
            error_log("getPackDetails called with pack_id: " . $packId);

            // Vérifier si packId est valide
            if (empty($packId) || !is_numeric($packId)) {
                error_log("Invalid pack_id: " . $packId);
                return [
                    'title' => 'Pack inconnu (ID invalide)',
                    'price' => 0.00,
                ];
            }

            $stmt = $this->pdo->prepare("SELECT title, price FROM event_packs WHERE id = ?");
            $stmt->execute([$packId]);
            $pack = $stmt->fetch();

            if ($pack) {
                error_log("Pack found: " . json_encode($pack));
                return [
                    'title' => $pack['title'],
                    'price' => $pack['price'],
                ];
            }

            error_log("No pack found for pack_id: " . $packId);
            return [
                'title' => 'Pack inconnu (non trouvé)',
                'price' => 0.00,
            ];
        } catch (\PDOException $e) {
            // Log de l'erreur
            error_log("Erreur lors de la récupération des détails du pack : " . $e->getMessage());
            return [
                'title' => 'Pack inconnu (erreur DB)',
                'price' => 0.00,
            ];
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
            if ($type === 'event') {
                $table = 'event_reservations';
            } elseif ($type === 'pack') {
                $table = 'pack_reservations';
            } else {
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

    public function updateInvoicePath($id, $type, $invoicePath)
    {
        try {
            $table = ($type === 'event') ? 'event_reservations' : 'pack_reservations';
            $stmt = $this->pdo->prepare("UPDATE $table SET invoice_path = ? WHERE id = ?");
            $success = $stmt->execute([$invoicePath, $id]);

            if (!$success) {
                error_log("Échec de la mise à jour de invoice_path pour ID $id dans $table.");
                return false;
            }
            return true;
        } catch (\PDOException $e) {
            error_log("Erreur SQL lors de la mise à jour de invoice_path : " . $e->getMessage());
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
