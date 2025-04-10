<?php

namespace Models\Events;

class ReservationModel extends \Models\ModeleParent
{
    // Ajoute une réservation pour un événement dans la base de données
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
            "); // Prépare la requête d'insertion avec statut "pending" et date actuelle
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
            ]); // Exécute avec les paramètres fournis
            return true; // Retourne true si succès
        } catch (\PDOException $e) {
            error_log("Erreur lors de la réservation : " . $e->getMessage()); // Log l'erreur
            die("Erreur SQL : " . $e->getMessage()); // Affiche l'erreur et stoppe l'exécution
            return false; // Retourne false si erreur
        }
    }

    // Ajoute une réservation pour un pack dans la base de données
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
            "); // Prépare la requête d'insertion avec statut "pending" et date actuelle
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
            ]); // Exécute avec les paramètres fournis
            return true; // Retourne true si succès
        } catch (\PDOException $e) {
            error_log("Erreur lors de la réservation du pack : " . $e->getMessage()); // Log l'erreur
            return false; // Retourne false si erreur
        }
    }

    // Récupère toutes les réservations (événements et packs) combinées
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
            "); // Requête UNION pour combiner les réservations d'événements et de packs
            return $stmt->fetchAll(); // Retourne un tableau de toutes les réservations
        } catch (\PDOException $e) {
            error_log($e->getMessage()); // Log l'erreur
            return []; // Retourne un tableau vide si erreur
        }
    }

    // Récupère les détails d'un pack spécifique par son ID
    public function getPackDetails($packId)
    {
        try {
            error_log("getPackDetails called with pack_id: " . $packId); // Log pour débogage

            // Vérifie si l'ID du pack est valide
            if (empty($packId) || !is_numeric($packId)) {
                error_log("Invalid pack_id: " . $packId); // Log si ID invalide
                return [
                    'title' => 'Pack inconnu (ID invalide)',
                    'price' => 0.00,
                ]; // Retourne des valeurs par défaut
            }

            $stmt = $this->pdo->prepare("SELECT title, price FROM event_packs WHERE id = ?"); // Prépare la requête
            $stmt->execute([$packId]); // Exécute avec l'ID du pack
            $pack = $stmt->fetch(); // Récupère les détails du pack

            if ($pack) {
                error_log("Pack found: " . json_encode($pack)); // Log si pack trouvé
                return [
                    'title' => $pack['title'],
                    'price' => $pack['price'],
                ]; // Retourne le titre et le prix
            }

            error_log("No pack found for pack_id: " . $packId); // Log si aucun pack trouvé
            return [
                'title' => 'Pack inconnu (non trouvé)',
                'price' => 0.00,
            ]; // Retourne des valeurs par défaut
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération des détails du pack : " . $e->getMessage()); // Log l'erreur
            return [
                'title' => 'Pack inconnu (erreur DB)',
                'price' => 0.00,
            ]; // Retourne des valeurs par défaut en cas d'erreur
        }
    }

    // Récupère une réservation spécifique par son ID et son type (optionnel)
    public function getReservationById($id, $type = null)
    {
        if ($type === 'event' || !$type) {
            $stmt = $this->pdo->prepare("SELECT *, 'event' AS type FROM event_reservations WHERE id = ?"); // Requête pour les réservations d'événements
            $stmt->execute([$id]);
            $reservation = $stmt->fetch();
            if ($reservation && ($type === 'event' || !$type)) {
                return $reservation; // Retourne la réservation si trouvée
            }
        }

        if ($type === 'pack' || !$type) {
            $stmt = $this->pdo->prepare("SELECT *, 'pack' AS type FROM pack_reservations WHERE id = ?"); // Requête pour les réservations de packs
            $stmt->execute([$id]);
            return $stmt->fetch(); // Retourne la réservation (ou false si non trouvée)
        }

        return false; // Retourne false si aucune réservation trouvée
    }

    // Met à jour le statut d'une réservation
    public function updateReservationStatus($id, $status, $type = null)
    {
        try {
            // Détermine la table en fonction du type ou recherche automatique
            if ($type === 'event') {
                $table = 'event_reservations';
            } elseif ($type === 'pack') {
                $table = 'pack_reservations';
            } else {
                $reservation = $this->getReservationById($id); // Récupère la réservation pour déterminer le type
                if (!$reservation) {
                    die("Impossible de mettre à jour : réservation avec ID $id introuvable."); // Erreur si réservation non trouvée
                }
                $table = $reservation['type'] === 'event' ? 'event_reservations' : 'pack_reservations'; // Définit la table
            }

            $stmt = $this->pdo->prepare("UPDATE $table SET status = ? WHERE id = ?"); // Prépare la requête de mise à jour
            $success = $stmt->execute([$status, $id]); // Exécute avec le nouveau statut et l'ID

            if (!$success) {
                die("Échec de la mise à jour dans $table pour ID $id avec status $status."); // Erreur si mise à jour échoue
            }
            return true; // Retourne true si succès
        } catch (\PDOException $e) {
            die("Erreur SQL lors de la mise à jour du statut : " . $e->getMessage()); // Affiche l'erreur et stoppe
        }
    }

    // Met à jour le chemin de la facture pour une réservation
    public function updateInvoicePath($id, $type, $invoicePath)
    {
        try {
            $table = ($type === 'event') ? 'event_reservations' : 'pack_reservations'; // Définit la table selon le type
            $stmt = $this->pdo->prepare("UPDATE $table SET invoice_path = ? WHERE id = ?"); // Prépare la requête
            $success = $stmt->execute([$invoicePath, $id]); // Exécute avec le chemin de la facture et l'ID

            if (!$success) {
                error_log("Échec de la mise à jour de invoice_path pour ID $id dans $table."); // Log si échec
                return false; // Retourne false
            }
            return true; // Retourne true si succès
        } catch (\PDOException $e) {
            error_log("Erreur SQL lors de la mise à jour de invoice_path : " . $e->getMessage()); // Log l'erreur
            return false; // Retourne false si erreur
        }
    }

    // Récupère les 5 réservations les plus récentes (événements et packs combinés)
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
            "); // Requête UNION pour les réservations récentes
            return $stmt->fetchAll(); // Retourne un tableau des résultats
        } catch (\PDOException $e) {
            error_log($e->getMessage()); // Log l'erreur
            return []; // Retourne un tableau vide si erreur
        }
    }
}
?>