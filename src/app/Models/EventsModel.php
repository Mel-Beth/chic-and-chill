<?php

namespace Models;

class EventsModel extends ModeleParent
{
    public function getAllEvents()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM events ORDER BY date_event ASC");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function getEventById($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM events WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function getPastEvents()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM events WHERE date_event < NOW() ORDER BY date_event DESC");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function getEventImages($eventId)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT image_url FROM event_images WHERE event_id = ?");
            $stmt->execute([$eventId]);
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }
    public function getPrevEvent($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM events WHERE id < ? ORDER BY id DESC LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getNextEvent($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM events WHERE id > ? ORDER BY id ASC LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
