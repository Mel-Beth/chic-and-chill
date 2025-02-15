<?php

namespace Controllers;

use Models\DashboardModel;

class DashboardController
{
    private $dashboardModel;

    public function __construct()
    {
        $this->dashboardModel = new DashboardModel();
    }

    public function index()
    {
        $dashboardData = [
            'messages' => $this->dashboardModel->getRecentMessages(),
            'event_reservations' => $this->dashboardModel->getRecentEventReservations(),
            'pack_reservations' => $this->dashboardModel->getRecentPackReservations(),
            'orders' => $this->dashboardModel->getRecentOrders()
        ];

        error_log("Données envoyées au dashboard : " . print_r($dashboardData, true));

        include 'src/app/Views/Admin/dashboard.php';
    }
}
