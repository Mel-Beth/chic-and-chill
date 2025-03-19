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
        header('Content-Type: application/json');
        $notifications = $this->notificationModel->getUnreadNotifications();
        echo json_encode($notifications);
        exit();
    }

    public function markAsRead($id)
    {
        header('Content-Type: application/json');
        $this->notificationModel->markAsRead($id);
        echo json_encode(["success" => true]);
        exit();
    }
}