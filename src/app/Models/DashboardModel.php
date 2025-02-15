<?php

namespace Models;

class DashboardModel extends ModeleParent {
    public function getRecentMessages() {
        $stmt = $this->pdo->query("
            SELECT name, email, message, created_at 
            FROM contact_messages 
            ORDER BY created_at DESC 
            LIMIT 5
        ");
        return $stmt->fetchAll() ?: [];
    }

    public function getRecentEventReservations() {
        $stmt = $this->pdo->query("
            SELECT customer_name, participants, event_type, created_at 
            FROM event_reservations 
            ORDER BY created_at DESC 
            LIMIT 5
        ");
        return $stmt->fetchAll() ?: [];
    }

    public function getRecentPackReservations() {
        $stmt = $this->pdo->query("
            SELECT customer_name, pack_id, created_at 
            FROM pack_reservations 
            ORDER BY created_at DESC 
            LIMIT 5
        ");
        return $stmt->fetchAll() ?: [];
    }

    public function getRecentOrders() {
        $stmt = $this->pdo->query("
            SELECT id, total_price, status, created_at 
            FROM event_orders 
            ORDER BY created_at DESC 
            LIMIT 5
        ");
        return $stmt->fetchAll() ?: [];
    }
}
