<?php

namespace Controllers;

use Models\NewsletterModel;

class NewsletterController
{
    private $newsletterModel;

    public function __construct()
    {
        $this->newsletterModel = new NewsletterModel(); // Initialisation correcte
    }

    public function manageNewsletter()
    {
        $newsletterModel = new NewsletterModel();
        $subscribers = $newsletterModel->getAllSubscribers();
        include('src/app/Views/Admin/admin_newsletter.php');
    }

    public function deleteSubscriber($id)
    {
        $success = $this->newsletterModel->deleteSubscriber($id);

        if ($success) {
            header("Location: ../?success=1&action=delete");
            exit();
        } else {
            header("Location: ../?success=0&action=delete");
            exit();
        }
    }
}
