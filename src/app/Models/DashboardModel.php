<?php

namespace Models;

class DashboardModel extends ModeleParent
{
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

    public function getDashboardStats()
    {
        return [
            'messages_count' => $this->getTotalMessages(),
            'active_events' => $this->getActiveEvents(),
            'total_events' => $this->getTotalEvents(),
            'pending_reservations' => $this->getPendingReservations(),
            'subscribers_count' => $this->getTotalSubscribers(),
            'reservation_months' => $this->getReservationMonths(),
            'reservation_counts' => $this->getReservationCounts(),
            'packs_labels' => $this->getPackLabels(),
            'packs_counts' => $this->getPackCounts()
        ];
    }

    private function getActiveEvents()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM events WHERE status = 'active'");
        return $stmt->fetchColumn() ?: 0;
    }

    private function getTotalEvents()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM events");
        return $stmt->fetchColumn() ?: 0;
    }

    private function getPendingReservations()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM event_reservations WHERE status = 'pending'");
        return $stmt->fetchColumn() ?: 0;
    }

    private function getTotalSubscribers()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM newsletter_subscribers");
        return $stmt->fetchColumn() ?: 0;
    }

    private function getReservationMonths()
    {
        $stmt = $this->pdo->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count 
            FROM event_reservations 
            GROUP BY month
            ORDER BY month ASC
        ");
        return array_column($stmt->fetchAll(), 'month');
    }

    private function getReservationCounts()
    {
        $stmt = $this->pdo->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count 
            FROM event_reservations 
            GROUP BY month
            ORDER BY month ASC
        ");
        return array_column($stmt->fetchAll(), 'count');
    }

    private function getPackLabels()
    {
        $stmt = $this->pdo->query("
            SELECT pack_id, COUNT(*) as count 
            FROM pack_reservations 
            GROUP BY pack_id
        ");
        return array_column($stmt->fetchAll(), 'pack_id');
    }

    private function getPackCounts()
    {
        $stmt = $this->pdo->query("
            SELECT pack_id, COUNT(*) as count 
            FROM pack_reservations 
            GROUP BY pack_id
        ");
        return array_column($stmt->fetchAll(), 'count');
    }

    private function getTotalReservations()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM event_reservations");
        return $stmt->fetchColumn() ?: 0;
    }

    private function getTotalUsers()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM users");
        return $stmt->fetchColumn() ?: 0;
    }

    private function getTotalMessages()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM contact_messages");
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


    private function getReservationsPerDay()
    {
        $stmt = $this->pdo->query(
            "SELECT DATE(created_at) as date, COUNT(*) as count 
             FROM event_reservations 
             GROUP BY DATE(created_at)"
        );
        return $stmt->fetchAll() ?: [];
    }

    private function getCategoriesDistribution()
    {
        $stmt = $this->pdo->query(
            "SELECT source, COUNT(*) as count 
             FROM contact_messages 
             GROUP BY source"
        );
        return $stmt->fetchAll() ?: [];
    }
}
