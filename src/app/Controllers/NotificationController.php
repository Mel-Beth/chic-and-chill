<?php

namespace Controllers;

use Models\NotificationModel;

class NotificationController
{
    private $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }

    public function getUnreadNotifications()
    {
        $notifications = $this->notificationModel->getUnreadNotifications();
        echo json_encode($notifications);
    }

    public function markAsRead($id)
    {
        $this->notificationModel->markAsRead($id);
        echo json_encode(["success" => true]);
    }
}
