<?php

namespace Models;

class PaymentsModel extends ModeleParent
{
    public function getAllPayments()
    {
        $stmt = $this->pdo->query("SELECT * FROM payments ORDER BY date_created DESC");
        return $stmt->fetchAll();
    }
}
