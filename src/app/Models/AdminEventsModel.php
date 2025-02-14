<?php

namespace Models;

class AdminEventsModel extends ModeleParent
{
    /** ========== 📌 GESTION DES ÉVÉNEMENTS ========== */

    public function getAllEvents()
    {
        $stmt = $this->pdo->query("SELECT * FROM events ORDER BY date_event DESC");
        return $stmt->fetchAll();
    }

    public function createEvent($title, $description, $date_event, $status)
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

    /** ========== 📌 GESTION DES PACKS ========== */

    public function getAllPacks()
    {
        $stmt = $this->pdo->query("SELECT * FROM event_packs ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function createPack($event_id, $title, $description, $price, $stock)
    {
        $stmt = $this->pdo->prepare("INSERT INTO event_packs (event_id, title, description, price, stock) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$event_id, $title, $description, $price, $stock]);
    }

    public function updatePack($pack_id, $title, $description, $price, $stock)
    {
        $stmt = $this->pdo->prepare("UPDATE event_packs SET title = ?, description = ?, price = ?, stock = ? WHERE id = ?");
        return $stmt->execute([$title, $description, $price, $stock, $pack_id]);
    }

    public function deletePack($pack_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM event_packs WHERE id = ?");
        return $stmt->execute([$pack_id]);
    }

    /** ========== 📌 GESTION DES RÉSERVATIONS ========== */

    public function getAllReservations()
    {
        $stmt = $this->pdo->query("SELECT * FROM event_reservations ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function updateReservationStatus($reservation_id, $status)
    {
        $stmt = $this->pdo->prepare("UPDATE event_reservations SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $reservation_id]);
    }

    /** ========== 📌 GESTION DES UTILISATEURS ========== */

    public function getAllUsers()
    {
        $stmt = $this->pdo->query("SELECT * FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function deleteUser($user_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$user_id]);
    }

    /** ========== 📌 GESTION DES MESSAGES DE CONTACT ========== */

    public function getAllMessages()
    {
        $stmt = $this->pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function deleteMessage($message_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
        return $stmt->execute([$message_id]);
    }

    /** ========== 📌 GESTION DES ABONNÉS NEWSLETTER ========== */

    public function getAllSubscribers()
    {
        $stmt = $this->pdo->query("SELECT * FROM newsletter_subscribers ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function deleteSubscriber($subscriber_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM newsletter_subscribers WHERE id = ?");
        return $stmt->execute([$subscriber_id]);
    }
    /** ========== 📌 GESTION DES IDÉES DE TENUES ========== */

    public function getAllOutfits()
    {
        $stmt = $this->pdo->query("SELECT * FROM outfits ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function createOutfit($title, $description, $image, $products)
    {
        $stmt = $this->pdo->prepare("INSERT INTO outfits (title, description, image, products) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$title, $description, $image, $products]);
    }

    public function updateOutfit($outfit_id, $title, $description, $image, $products)
    {
        $stmt = $this->pdo->prepare("UPDATE outfits SET title = ?, description = ?, image = ?, products = ? WHERE id = ?");
        return $stmt->execute([$title, $description, $image, $products, $outfit_id]);
    }

    public function deleteOutfit($outfit_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM outfits WHERE id = ?");
        return $stmt->execute([$outfit_id]);
    }

    /** ========== 📌 NOTIFICATIONS POUR LES ARTICLES VENDUS ========== */

    public function getProductStock($product_id)
    {
        $stmt = $this->pdo->prepare("SELECT stock FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        return $stmt->fetchColumn();
    }
    public function getReservationById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM reservations WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function updateUser($id, $name, $email)
    {
        $stmt = $this->db->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        return $stmt->execute([$name, $email, $id]);
    }

    public function getAllPayments()
    {
        $stmt = $this->db->query("SELECT * FROM payments");
        return $stmt->fetchAll();
    }

    public function getDataForExport($type)
    {
        $table = ($type === "reservations") ? "reservations" : (($type === "users") ? "users" : "payments");
        $stmt = $this->db->query("SELECT * FROM " . $table);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
