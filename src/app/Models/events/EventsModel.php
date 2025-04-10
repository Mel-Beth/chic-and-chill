<?php

namespace Models\Events;

class EventsModel extends \Models\ModeleParent
{
    // Récupère tous les événements, triés par date de création décroissante
    public function getAllEvents()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM events ORDER BY created_at DESC"); // Exécute une requête SQL
            return $stmt->fetchAll(); // Retourne tous les résultats sous forme de tableau
        } catch (\PDOException $e) {
            error_log($e->getMessage()); // Log l'erreur en cas d'échec
            return []; // Retourne un tableau vide si erreur
        }
    }

    // Récupère un événement spécifique par son ID
    public function getEventById($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM events WHERE id = ?"); // Prépare la requête avec un paramètre
            $stmt->execute([$id]); // Exécute avec l'ID fourni
            return $stmt->fetch(); // Retourne une seule ligne (ou false si non trouvé)
        } catch (\PDOException $e) {
            error_log($e->getMessage()); // Log l'erreur en cas d'échec
            return null; // Retourne null si erreur
        }
    }

    // Récupère les événements passés, triés par date décroissante
    public function getPastEvents()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM events WHERE date_event < NOW() ORDER BY date_event DESC"); // Requête pour les événements avant aujourd'hui
            return $stmt->fetchAll(); // Retourne tous les événements passés
        } catch (\PDOException $e) {
            error_log($e->getMessage()); // Log l'erreur en cas d'échec
            return []; // Retourne un tableau vide si erreur
        }
    }

    // Récupère les URLs des images associées à un événement
    public function getEventImages($eventId)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT image_url FROM event_images WHERE event_id = ?"); // Prépare la requête pour les images
            $stmt->execute([$eventId]); // Exécute avec l'ID de l'événement
            return $stmt->fetchAll(\PDO::FETCH_COLUMN); // Retourne un tableau contenant uniquement les URLs
        } catch (\PDOException $e) {
            error_log($e->getMessage()); // Log l'erreur en cas d'échec
            return []; // Retourne un tableau vide si erreur
        }
    }

    // Récupère l'événement suivant après une date donnée
    public function getPrevEvent($date)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM events WHERE date_event > ? ORDER BY date_event ASC LIMIT 1"); // Requête pour le prochain événement
        $stmt->execute([$date]); // Exécute avec la date fournie
        return $stmt->fetch(); // Retourne une seule ligne (ou false si non trouvé)
    }

    // Récupère l'événement précédent avant une date donnée
    public function getNextEvent($date)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM events WHERE date_event < ? ORDER BY date_event DESC LIMIT 1"); // Requête pour l'événement précédent
        $stmt->execute([$date]); // Exécute avec la date fournie
        return $stmt->fetch(); // Retourne une seule ligne (ou false si non trouvé)
    }

    // Récupère les événements à venir, triés par date croissante
    public function getUpcomingEvents()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM events WHERE date_event >= NOW() ORDER BY date_event ASC"); // Requête pour les événements futurs
            return $stmt->fetchAll(); // Retourne tous les événements à venir
        } catch (\PDOException $e) {
            error_log($e->getMessage()); // Log l'erreur en cas d'échec
            return []; // Retourne un tableau vide si erreur
        }
    }

    // Récupère tous les événements pour l'administration, triés par date décroissante
    public function getAllEventsAdmin()
    {
        $stmt = $this->pdo->query("SELECT * FROM events ORDER BY date_event DESC"); // Requête pour tous les événements
        return $stmt->fetchAll(); // Retourne tous les résultats
    }

    // Ajoute un nouvel événement à la base de données
    public function addEvent($title, $description, $date_event, $location, $status, $image = null)
    {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO events (title, description, date_event, location, status, image) 
                VALUES (?, ?, ?, ?, ?, ?)
            "); // Prépare la requête d'insertion
            return $stmt->execute([$title, $description, $date_event, $location, $status, $image]); // Exécute et retourne true si succès
        } catch (\PDOException $e) {
            error_log($e->getMessage()); // Log l'erreur en cas d'échec
            return false; // Retourne false si erreur
        }
    }

    // Met à jour un événement existant dans la base de données
    public function updateEvent($event_id, $title, $description, $date_event, $location, $status, $image = null)
    {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE events 
                SET title = ?, description = ?, date_event = ?, location = ?, status = ?, image = ?
                WHERE id = ?
            "); // Prépare la requête de mise à jour
            return $stmt->execute([$title, $description, $date_event, $location, $status, $image, $event_id]); // Exécute et retourne true si succès
        } catch (\PDOException $e) {
            error_log($e->getMessage()); // Log l'erreur en cas d'échec
            return false; // Retourne false si erreur
        }
    }

    // Supprime un événement spécifique par son ID
    public function deleteEvent($event_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM events WHERE id = ?"); // Prépare la requête de suppression
        return $stmt->execute([$event_id]); // Exécute et retourne true si succès
    }

    // Récupère les médias (images) associés à un événement
    public function getEventMedia($eventId)
    {
        $stmt = $this->pdo->prepare("SELECT id, image_url, type FROM event_images WHERE event_id = ?"); // Prépare la requête pour les médias
        $stmt->execute([$eventId]); // Exécute avec l'ID de l'événement
        return $stmt->fetchAll(\PDO::FETCH_ASSOC); // Retourne un tableau associatif avec id, URL et type
    }

    // Ajoute un nouveau média (image) à un événement
    public function addEventMedia($eventId, $filePath, $type)
    {
        $stmt = $this->pdo->prepare("INSERT INTO event_images (event_id, image_url, type) VALUES (?, ?, ?)"); // Prépare la requête d'insertion
        return $stmt->execute([$eventId, $filePath, $type]); // Exécute et retourne true si succès
    }

    // Supprime un média spécifique par son ID
    public function deleteEventMedia($mediaId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM event_images WHERE id = ?"); // Prépare la requête de suppression
        return $stmt->execute([$mediaId]); // Exécute et retourne true si succès
    }
}
?>