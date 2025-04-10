<?php

namespace Models;

class DashboardModel extends ModeleParent
{
    private $contactModel;

    // Constructeur : Initialise le modèle parent et le modèle de contact
    public function __construct()
    {
        parent::__construct(); // Appelle le constructeur parent pour établir la connexion PDO
        $this->contactModel = new ContactModel(); // Initialise le modèle de gestion des messages de contact
    }

    // Récupère les 5 messages de contact les plus récents
    public function getRecentMessages()
    {
        $stmt = $this->pdo->query("
            SELECT name, email, message, created_at 
            FROM contact_messages 
            ORDER BY created_at DESC 
            LIMIT 5
        "); // Requête pour les messages récents
        return $stmt->fetchAll() ?: []; // Retourne les résultats ou un tableau vide si aucun résultat
    }

    // Récupère les 5 réservations d'événements les plus récentes
    public function getRecentEventReservations()
    {
        $stmt = $this->pdo->query("
            SELECT customer_name, participants, event_type, created_at 
            FROM event_reservations 
            ORDER BY created_at DESC 
            LIMIT 5
        "); // Requête pour les réservations d'événements récentes
        return $stmt->fetchAll() ?: []; // Retourne les résultats ou un tableau vide si aucun résultat
    }

    // Récupère les 5 réservations de packs les plus récentes
    public function getRecentPackReservations()
    {
        $stmt = $this->pdo->query("
            SELECT customer_name, pack_id, created_at 
            FROM pack_reservations 
            ORDER BY created_at DESC 
            LIMIT 5
        "); // Requête pour les réservations de packs récentes
        return $stmt->fetchAll() ?: []; // Retourne les résultats ou un tableau vide si aucun résultat
    }

    // Récupère les 5 commandes les plus récentes
    public function getRecentOrders()
    {
        $stmt = $this->pdo->query("
            SELECT id, total_price, status, created_at 
            FROM event_orders 
            ORDER BY created_at DESC 
            LIMIT 5
        "); // Requête pour les commandes récentes
        return $stmt->fetchAll() ?: []; // Retourne les résultats ou un tableau vide si aucun résultat
    }

    // Récupère les statistiques du tableau de bord pour une période donnée
    public function getDashboardStats($period = 'year')
    {
        $queryFilter = match ($period) {
            'quarter' => "WHERE created_at >= DATE_SUB(NOW(), INTERVAL 3 MONTH)", // Filtre pour le dernier trimestre
            'year' => "WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 YEAR)",     // Filtre pour la dernière année
            default => "WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)"    // Par défaut : dernier mois
        };

        $stats = []; // Tableau pour stocker les statistiques
        try {
            $stats['messages_count'] = $this->getTotalMessages($queryFilter); // Nombre total de messages
        } catch (\Exception $e) {
            error_log("Erreur dans getTotalMessages: " . $e->getMessage()); // Log l'erreur
            $stats['messages_count'] = 0; // Valeur par défaut en cas d'erreur
        }

        try {
            $stats['active_events'] = $this->getActiveEvents(); // Nombre d'événements actifs (pas de filtre temporel)
        } catch (\Exception $e) {
            error_log("Erreur dans getActiveEvents: " . $e->getMessage());
            $stats['active_events'] = 0;
        }

        try {
            $stats['total_events'] = $this->getTotalEvents($queryFilter); // Nombre total d'événements
        } catch (\Exception $e) {
            error_log("Erreur dans getTotalEvents: " . $e->getMessage());
            $stats['total_events'] = 0;
        }

        try {
            $stats['pending_reservations'] = $this->getPendingReservations($queryFilter); // Réservations en attente
        } catch (\Exception $e) {
            error_log("Erreur dans getPendingReservations: " . $e->getMessage());
            $stats['pending_reservations'] = 0;
        }

        try {
            $stats['subscribers_count'] = $this->getTotalSubscribers($queryFilter); // Nombre d'abonnés à la newsletter
        } catch (\Exception $e) {
            error_log("Erreur dans getTotalSubscribers: " . $e->getMessage());
            $stats['subscribers_count'] = 0;
        }

        try {
            $stats['reservation_months'] = $this->getReservationMonths($queryFilter); // Mois des réservations
        } catch (\Exception $e) {
            error_log("Erreur dans getReservationMonths: " . $e->getMessage());
            $stats['reservation_months'] = [];
        }

        try {
            $stats['reservation_counts'] = $this->getReservationCounts($queryFilter); // Nombre de réservations par mois
        } catch (\Exception $e) {
            error_log("Erreur dans getReservationCounts: " . $e->getMessage());
            $stats['reservation_counts'] = [];
        }

        try {
            $stats['packs_labels'] = $this->getPackLabels($queryFilter); // Étiquettes des packs réservés
        } catch (\Exception $e) {
            error_log("Erreur dans getPackLabels: " . $e->getMessage());
            $stats['packs_labels'] = [];
        }

        try {
            $stats['packs_counts'] = $this->getPackCounts($queryFilter); // Nombre de réservations par pack
        } catch (\Exception $e) {
            error_log("Erreur dans getPackCounts: " . $e->getMessage());
            $stats['packs_counts'] = [];
        }

        try {
            $stats['message_sources'] = $this->getMessageSources($queryFilter); // Sources des messages
        } catch (\Exception $e) {
            error_log("Erreur dans getMessageSources: " . $e->getMessage());
            $stats['message_sources'] = ['labels' => [], 'counts' => []];
        }

        error_log("getDashboardStats result: " . json_encode($stats)); // Log le résultat final
        return $stats; // Retourne les statistiques
    }

    // Récupère les sources des messages avec leurs comptes
    public function getMessageSources($filter = '')
    {
        $data = $this->contactModel->getMessageSources($filter); // Récupère les données via le modèle de contact
        $result = [
            'labels' => array_column($data, 'source'), // Liste des sources
            'counts' => array_map('intval', array_column($data, 'count')) // Liste des comptes convertis en entiers
        ];
        error_log("DashboardModel getMessageSources result: " . json_encode($result)); // Log le résultat
        return $result; // Retourne les données formatées
    }

    // Récupère la distribution des catégories de messages
    public function getCategoriesDistribution($filter = '')
    {
        $query = "SELECT source, COUNT(*) as count FROM contact_messages $filter GROUP BY source"; // Requête pour compter par source
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll() ?: []; // Retourne les résultats ou un tableau vide
    }

    // Compte les événements actifs
    public function getActiveEvents()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM events WHERE status = 'active'"); // Requête pour les événements actifs
        return $stmt->fetchColumn() ?: 0; // Retourne le compte ou 0 si aucun résultat
    }

    // Compte le nombre total d'événements avec filtre temporel
    public function getTotalEvents($filter = '')
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM events $filter"); // Requête avec filtre
        return $stmt->fetchColumn() ?: 0; // Retourne le compte ou 0 si aucun résultat
    }

    // Compte les réservations en attente (événements et packs combinés)
    private function getPendingReservations($filter = '')
    {
        $timeFilter = $filter ? "AND $filter" : ''; // Ajoute le filtre temporel si présent
        if ($timeFilter && str_starts_with($timeFilter, 'AND WHERE')) {
            $timeFilter = str_replace('AND WHERE', 'AND', $timeFilter); // Nettoie le filtre si mal formé
        }

        $query = "
            SELECT COUNT(*) 
            FROM (
                SELECT id FROM event_reservations WHERE status = 'pending' $timeFilter
                UNION ALL
                SELECT id FROM pack_reservations WHERE status = 'pending' $timeFilter
            ) AS pending_reservations
        "; // Requête UNION pour compter les réservations en attente
        error_log("Requête pour pending_reservations : $query"); // Log la requête
        $stmt = $this->pdo->query($query);
        $count = $stmt->fetchColumn() ?: 0; // Récupère le compte
        error_log("Réservations en attente trouvées : $count"); // Log le résultat
        return $count; // Retourne le nombre
    }

    // Compte le nombre total d'abonnés à la newsletter avec filtre temporel
    public function getTotalSubscribers($filter = '')
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM newsletter_subscribers $filter"); // Requête avec filtre
        return $stmt->fetchColumn() ?: 0; // Retourne le compte ou 0 si aucun résultat
    }

    // Vérifie si un rappel de newsletter existe pour le mois courant
    public function checkNewsletterReminderExists()
    {
        $currentMonth = date('Y-m'); // Mois actuel au format YYYY-MM
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) 
            FROM notifications 
            WHERE message LIKE '%Rappel : Envoyez la newsletter mensuelle%' 
            AND DATE_FORMAT(created_at, '%Y-%m') = ?
        "); // Requête pour vérifier l'existence d'un rappel
        $stmt->execute([$currentMonth]);
        return $stmt->fetchColumn() > 0; // Retourne true si un rappel existe
    }

    // Récupère les mois des réservations combinées
    public function getReservationMonths($filter)
    {
        $stmt = $this->pdo->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count 
            FROM (
                SELECT created_at FROM event_reservations $filter
                UNION ALL
                SELECT created_at FROM pack_reservations $filter
            ) AS combined_reservations
            GROUP BY month
            ORDER BY month ASC
        "); // Requête UNION pour les mois des réservations
        return array_column($stmt->fetchAll(), 'month'); // Retourne la liste des mois
    }

    // Récupère le nombre de réservations par mois
    public function getReservationCounts($filter)
    {
        $stmt = $this->pdo->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count 
            FROM (
                SELECT created_at FROM event_reservations $filter
                UNION ALL
                SELECT created_at FROM pack_reservations $filter
            ) AS combined_reservations
            GROUP BY month
            ORDER BY month ASC
        "); // Requête UNION pour le compte par mois
        return array_column($stmt->fetchAll(), 'count'); // Retourne la liste des comptes
    }

    // Récupère les étiquettes (IDs) des packs réservés
    public function getPackLabels($filter = '')
    {
        $stmt = $this->pdo->query("
            SELECT pack_id, COUNT(*) as count 
            FROM pack_reservations $filter
            GROUP BY pack_id
        "); // Requête pour les IDs des packs
        return array_column($stmt->fetchAll(), 'pack_id'); // Retourne la liste des IDs
    }

    // Récupère le nombre de réservations par pack
    public function getPackCounts($filter = '')
    {
        $stmt = $this->pdo->query("
            SELECT pack_id, COUNT(*) as count 
            FROM pack_reservations $filter
            GROUP BY pack_id
        "); // Requête pour le compte par pack
        return array_column($stmt->fetchAll(), 'count'); // Retourne la liste des comptes
    }

    // Compte le nombre total de réservations d'événements
    public function getTotalReservations()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM event_reservations"); // Requête pour le total
        return $stmt->fetchColumn() ?: 0; // Retourne le compte ou 0 si aucun résultat
    }

    // Compte le nombre total d'utilisateurs
    public function getTotalUsers()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM users"); // Requête pour le total
        return $stmt->fetchColumn() ?: 0; // Retourne le compte ou 0 si aucun résultat
    }

    // Compte le nombre total de messages avec filtre temporel
    public function getTotalMessages($filter = '')
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM contact_messages $filter"); // Requête avec filtre
        return $stmt->fetchColumn() ?: 0; // Retourne le compte ou 0 si aucun résultat
    }

    // Récupère les notifications non lues
    public function getUnreadNotifications()
    {
        $stmt = $this->pdo->query("
            SELECT id, message, created_at 
            FROM notifications 
            WHERE status = 'unread' 
            ORDER BY created_at DESC
        "); // Requête pour les notifications non lues
        return $stmt->fetchAll() ?: []; // Retourne les résultats ou un tableau vide
    }

    // Récupère le nombre de réservations par jour
    public function getReservationsPerDay()
    {
        $stmt = $this->pdo->query(
            "SELECT DATE(created_at) as date, COUNT(*) as count 
             FROM event_reservations 
             GROUP BY DATE(created_at)"
        ); // Requête pour le compte par jour
        return $stmt->fetchAll() ?: []; // Retourne les résultats ou un tableau vide
    }

    // Récupère les 5 actions récentes (exemple basé sur les réservations)
    public function getRecentActions()
    {
        $stmt = $this->pdo->query("
            SELECT 'Réservation créée' as description, created_at as date 
            FROM event_reservations 
            ORDER BY created_at DESC 
            LIMIT 5
        "); // Requête pour les actions récentes (alternative si table admin_actions inexistante)
        return $stmt->fetchAll() ?: []; // Retourne les résultats ou un tableau vide
    }
}
?>