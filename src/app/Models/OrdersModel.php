<?php

namespace Models;

class OrdersModel extends ModeleParent
{
    public function getRecentOrders()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM event_orders ORDER BY created_at DESC LIMIT 5");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }
}
