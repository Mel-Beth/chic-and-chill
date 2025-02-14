<?php

namespace Controllers;

use Models\NewsletterModel;

class NewsletterController
{
    public function manageNewsletter()
    {
        $newsletterModel = new NewsletterModel();
        $subscribers = $newsletterModel->getAllSubscribers();
        include('src/app/Views/Admin/admin_newsletter.php');
    }

    public function deleteSubscriber($id)
    {
        $newsletterModel = new NewsletterModel();
        $newsletterModel->deleteSubscriber($id);
        header("Location: admin/newsletter");
        exit();
    }
}
