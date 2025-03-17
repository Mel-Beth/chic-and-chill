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

        if (!isset($_SESSION['user_role']) || empty($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
<<<<<<< HEAD
            header("Location: ../connexion_shop");
            exit();
=======
           var_dump($_SESSION['user_role']); die();
            header("Location: ../connexion_shop");
            exit(); 
>>>>>>> 3ecd073920515f50cf218defd098309a566a62f2
        } else {

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
            $adminProfileImage = "assets/images/admin/default-avatar.png";

            include 'src/app/Views/Admin/dashboard.php';
        }
    }
}
?>