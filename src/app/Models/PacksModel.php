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

    public function getPackById($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM event_packs WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function getAllPacksAdmin()
    {
        $stmt = $this->pdo->query("SELECT * FROM event_packs ORDER BY title ASC");
        return $stmt->fetchAll();
    }

    public function addPack($name, $description, $price)
    {
        $stmt = $this->pdo->prepare("INSERT INTO packs (name, description, price) VALUES (?, ?, ?)");
        return $stmt->execute([$name, $description, $price]);
    }

    public function updatePack($pack_id, $name, $description, $price)
    {
        $stmt = $this->pdo->prepare("UPDATE packs SET name = ?, description = ?, price = ? WHERE id = ?");
        return $stmt->execute([$name, $description, $price, $pack_id]);
    }

    public function deletePack($pack_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM packs WHERE id = ?");
        return $stmt->execute([$pack_id]);
    }
}
