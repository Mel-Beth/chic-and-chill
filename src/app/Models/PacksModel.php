<?php

namespace Models;

class PacksModel extends ModeleParent
{
    public function getAllPacks()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM event_packs ORDER BY id ASC");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }
}
?>
