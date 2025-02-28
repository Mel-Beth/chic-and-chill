<?php

namespace Models;

class ExportModel extends ModeleParent
{
    public function getDataForExport($type)
    {
        $validTables = ['reservations' => 'reservations', 'users' => 'users', 'payments' => 'payments'];
        
        if (!array_key_exists($type, $validTables)) {
            return [];
        }

        $stmt = $this->pdo->query("SELECT * FROM " . $validTables[$type]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
