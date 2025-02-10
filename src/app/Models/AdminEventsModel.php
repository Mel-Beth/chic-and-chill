<?php
namespace Models;

class AdminEventsModel extends ModeleParent
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

    public function addEvent($title, $description, $date, $time, $location)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO events (title, description, date_event, time_event, location) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$title, $description, $date, $time, $location]);
            return true;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function updateEvent($id, $title, $description, $date, $time, $location)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE events SET title=?, description=?, date_event=?, time_event=?, location=? WHERE id=?");
            $stmt->execute([$title, $description, $date, $time, $location, $id]);
            return true;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function deleteEvent($id)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM events WHERE id=?");
            $stmt->execute([$id]);
            return true;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
?>
