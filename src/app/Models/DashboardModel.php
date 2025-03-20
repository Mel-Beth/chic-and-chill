<?php

namespace Models;

class DashboardModel extends ModeleParent
{
    private $contactModel;

    public function __construct()
    {
        parent::__construct();
        $this->contactModel = new ContactModel();
    }

    public function getRecentMessages()
    {
        $stmt = $this->pdo->query("
            SELECT name, email, message, created_at 
            FROM contact_messages 
            ORDER BY created_at DESC 
            LIMIT 5
        ");
        return $stmt->fetchAll() ?: [];
    }

    public function getRecentEventReservations()
    {
        $stmt = $this->pdo->query("
            SELECT customer_name, participants, event_type, created_at 
            FROM event_reservations 
            ORDER BY created_at DESC 
            LIMIT 5
        ");
        return $stmt->fetchAll() ?: [];
    }

    public function getRecentPackReservations()
    {
        $stmt = $this->pdo->query("
            SELECT customer_name, pack_id, created_at 
            FROM pack_reservations 
            ORDER BY created_at DESC 
            LIMIT 5
        ");
        return $stmt->fetchAll() ?: [];
    }

    public function getRecentOrders()
    {
        $stmt = $this->pdo->query("
            SELECT id, total_price, status, created_at 
            FROM event_orders 
            ORDER BY created_at DESC 
            LIMIT 5
        ");
        return $stmt->fetchAll() ?: [];
    }

    public function getDashboardStats($period = 'year')
    {
        $queryFilter = match ($period) {
            'quarter' => "WHERE created_at >= DATE_SUB(NOW(), INTERVAL 3 MONTH)",
            'year' => "WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 YEAR)",
            default => "WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)"
        };

        $stats = [];
        try {
            $stats['messages_count'] = $this->getTotalMessages($queryFilter);
        } catch (\Exception $e) {
            error_log("Erreur dans getTotalMessages: " . $e->getMessage());
            $stats['messages_count'] = 0;
        }

        try {
            $stats['active_events'] = $this->getActiveEvents(); // Pas de filtre temporel ici
        } catch (\Exception $e) {
            error_log("Erreur dans getActiveEvents: " . $e->getMessage());
            $stats['active_events'] = 0;
        }

        try {
            $stats['total_events'] = $this->getTotalEvents($queryFilter);
        } catch (\Exception $e) {
            error_log("Erreur dans getTotalEvents: " . $e->getMessage());
            $stats['total_events'] = 0;
        }

        try {
            $stats['pending_reservations'] = $this->getPendingReservations($queryFilter);
        } catch (\Exception $e) {
            error_log("Erreur dans getPendingReservations: " . $e->getMessage());
            $stats['pending_reservations'] = 0;
        }

        try {
            $stats['subscribers_count'] = $this->getTotalSubscribers($queryFilter);
        } catch (\Exception $e) {
            error_log("Erreur dans getTotalSubscribers: " . $e->getMessage());
            $stats['subscribers_count'] = 0;
        }

        try {
            $stats['reservation_months'] = $this->getReservationMonths($queryFilter);
        } catch (\Exception $e) {
            error_log("Erreur dans getReservationMonths: " . $e->getMessage());
            $stats['reservation_months'] = [];
        }

        try {
            $stats['reservation_counts'] = $this->getReservationCounts($queryFilter);
        } catch (\Exception $e) {
            error_log("Erreur dans getReservationCounts: " . $e->getMessage());
            $stats['reservation_counts'] = [];
        }

        try {
            $stats['packs_labels'] = $this->getPackLabels($queryFilter);
        } catch (\Exception $e) {
            error_log("Erreur dans getPackLabels: " . $e->getMessage());
            $stats['packs_labels'] = [];
        }

        try {
            $stats['packs_counts'] = $this->getPackCounts($queryFilter);
        } catch (\Exception $e) {
            error_log("Erreur dans getPackCounts: " . $e->getMessage());
            $stats['packs_counts'] = [];
        }

        try {
            $stats['message_sources'] = $this->getMessageSources($queryFilter);
        } catch (\Exception $e) {
            error_log("Erreur dans getMessageSources: " . $e->getMessage());
            $stats['message_sources'] = ['labels' => [], 'counts' => []];
        }

        error_log("getDashboardStats result: " . json_encode($stats));
        return $stats;
    }

    public function getMessageSources($filter = '')
    {
        $data = $this->contactModel->getMessageSources($filter);
        $result = [
            'labels' => array_column($data, 'source'),
            'counts' => array_map('intval', array_column($data, 'count')) // Convertir en entiers
        ];
        error_log("DashboardModel getMessageSources result: " . json_encode($result));
        return $result;
    }

    public function getCategoriesDistribution($filter = '')
    {
        $query = "SELECT source, COUNT(*) as count FROM contact_messages $filter GROUP BY source";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll() ?: [];
    }

    public function getActiveEvents()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM events WHERE status = 'active'");
        return $stmt->fetchColumn() ?: 0;
    }

    public function getTotalEvents($filter = '')
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM events $filter");
        return $stmt->fetchColumn() ?: 0;
    }

    private function getPendingReservations($filter = '')
    {
        // Ajuster le filtre pour éviter deux WHERE
        $timeFilter = $filter ? "AND $filter" : ''; // Si $filter existe, le transformer en condition supplémentaire
        if ($timeFilter && str_starts_with($timeFilter, 'AND WHERE')) {
            $timeFilter = str_replace('AND WHERE', 'AND', $timeFilter); // Nettoyer si $filter commence par WHERE
        }

        $query = "
        SELECT COUNT(*) 
        FROM (
            SELECT id FROM event_reservations WHERE status = 'pending' $timeFilter
            UNION ALL
            SELECT id FROM pack_reservations WHERE status = 'pending' $timeFilter
        ) AS pending_reservations
    ";

        error_log("Requête pour pending_reservations : $query");
        $stmt = $this->pdo->query($query);
        $count = $stmt->fetchColumn() ?: 0;
        error_log("Réservations en attente trouvées : $count");
        return $count;
    }

    public function getTotalSubscribers($filter = '')
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM newsletter_subscribers $filter");
        return $stmt->fetchColumn() ?: 0;
    }

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
    ");
        return array_column($stmt->fetchAll(), 'month');
    }

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
    ");
        return array_column($stmt->fetchAll(), 'count');
    }

    public function getPackLabels($filter = '')
    {
        $stmt = $this->pdo->query("
            SELECT pack_id, COUNT(*) as count 
            FROM pack_reservations $filter
            GROUP BY pack_id
        ");
        return array_column($stmt->fetchAll(), 'pack_id');
    }

    public function getPackCounts($filter = '')
    {
        $stmt = $this->pdo->query("
            SELECT pack_id, COUNT(*) as count 
            FROM pack_reservations $filter
            GROUP BY pack_id
        ");
        return array_column($stmt->fetchAll(), 'count');
    }

    public function getTotalReservations()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM event_reservations");
        return $stmt->fetchColumn() ?: 0;
    }

    public function getTotalUsers()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM users");
        return $stmt->fetchColumn() ?: 0;
    }

    public function getTotalMessages($filter = '')
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM contact_messages $filter");
        return $stmt->fetchColumn() ?: 0;
    }

    public function getUnreadNotifications()
    {
        $stmt = $this->pdo->query("
        SELECT id, message, created_at 
        FROM notifications 
        WHERE status = 'unread' 
        ORDER BY created_at DESC
    ");
        return $stmt->fetchAll() ?: [];
    }


    public function getReservationsPerDay()
    {
        $stmt = $this->pdo->query(
            "SELECT DATE(created_at) as date, COUNT(*) as count 
             FROM event_reservations 
             GROUP BY DATE(created_at)"
        );
        return $stmt->fetchAll() ?: [];
    }

    public function getRecentActions()
    {
        // Si la table admin_actions n'existe pas, utilisez une alternative
        $stmt = $this->pdo->query("
        SELECT 'Réservation créée' as description, created_at as date 
        FROM event_reservations 
        ORDER BY created_at DESC 
        LIMIT 5
    ");
        return $stmt->fetchAll() ?: [];
    }
}
