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
        $stats = $this->dashboardModel->getDashboardStats();
        $notifications = $this->dashboardModel->getUnreadNotifications();
        $dashboardData = [
            'messages_count' => $stats['messages_count'] ?? 0,
            'active_events' => $stats['active_events'] ?? 0,
            'total_events' => $stats['total_events'] ?? 0,
            'pending_reservations' => $stats['pending_reservations'] ?? 0,
            'subscribers_count' => $stats['subscribers_count'] ?? 0,
            'reservation_months' => $stats['reservation_months'] ?? [],
            'reservation_counts' => $stats['reservation_counts'] ?? [],
            'packs_labels' => $stats['packs_labels'] ?? [],
            'packs_counts' => $stats['packs_counts'] ?? [],
            'notifications' => $notifications
        ];

        $adminName = "Admin";
        $adminProfileImage = "assets/images/admin/avatar.jpg";

        include 'src/app/Views/Admin/dashboard.php';
    }
}
