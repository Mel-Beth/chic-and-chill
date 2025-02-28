<?php

namespace Models;

class NewsletterModel extends ModeleParent
{
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
}
