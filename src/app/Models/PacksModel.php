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
        try {
            $stmt = $this->pdo->query("SELECT * FROM event_packs ORDER BY created_at DESC");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function addPack($title, $description, $price, $duration, $included, $status)
{
    try {
        $stmt = $this->pdo->prepare("INSERT INTO event_packs (title, description, price, duration, included, status) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$title, $description, $price, $duration, $included, $status]);
    } catch (\PDOException $e) {
        error_log($e->getMessage());
        return false;
    }
}

    public function updatePack($pack_id, $title, $description, $price, $duration, $included, $status)
    {
        $stmt = $this->pdo->prepare("UPDATE event_packs SET title = ?, description = ?, price = ?, duration = ?, included = ?, status = ? WHERE id = ?");
        return $stmt->execute([$title, $description, $price, $duration, $included, $status, $pack_id]);
    }

    public function deletePack($pack_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM event_packs WHERE id = ?");
        return $stmt->execute([$pack_id]);
    }
}
