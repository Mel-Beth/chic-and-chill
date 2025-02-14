<?php

namespace Controllers;

use Models\DashboardModel;

class DashboardController {
    private $dashboardModel;

    public function __construct() {
        $this->dashboardModel = new DashboardModel();
    }

    public function index() {
        $dashboardData = [
            'messages' => $this->dashboardModel->getRecentMessages(),
            'event_reservations' => $this->dashboardModel->getRecentEventReservations(),
            'pack_reservations' => $this->dashboardModel->getRecentPackReservations(),
            'orders' => $this->dashboardModel->getRecentOrders(),
        ];

        // Passer les données à la vue
        extract(['dashboardData' => $dashboardData]);
        include 'src/app/Views/Admin/dashboard.php';
    }
}