<?php

namespace Models;

class EventsModel extends ModeleParent
{
    public function getAllEvents()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM events ORDER BY created_at DESC");
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
            return $stmt->fetchAll(\PDO::FETCH_COLUMN);
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function getPrevEvent($date)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM events WHERE date_event > ? ORDER BY date_event ASC LIMIT 1");
        $stmt->execute([$date]);
        return $stmt->fetch();
    }

    public function getNextEvent($date)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM events WHERE date_event < ? ORDER BY date_event DESC LIMIT 1");
        $stmt->execute([$date]);
        return $stmt->fetch();
    }
    public function getUpcomingEvents()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM events WHERE date_event >= NOW() ORDER BY date_event ASC");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function getAllEventsAdmin()
    {
        $stmt = $this->pdo->query("SELECT * FROM events ORDER BY date_event DESC");
        return $stmt->fetchAll();
    }

    public function addEvent($title, $description, $date_event, $status)
    {
        $stmt = $this->pdo->prepare("INSERT INTO events (title, description, date_event, status) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$title, $description, $date_event, $status]);
    }

    public function updateEvent($event_id, $title, $description, $date_event, $status)
    {
        $stmt = $this->pdo->prepare("UPDATE events SET title = ?, description = ?, date_event = ?, status = ? WHERE id = ?");
        return $stmt->execute([$title, $description, $date_event, $status, $event_id]);
    }

    public function deleteEvent($event_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM events WHERE id = ?");
        return $stmt->execute([$event_id]);
    }

    public function getEventMedia($eventId)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT image_url, type FROM event_images WHERE event_id = ?");
            $stmt->execute([$eventId]);
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }
}
