<?php

namespace Controllers;

use Models\DashboardModel;
use Models\Events\OutfitsModel;

class DashboardController
{
    private $dashboardModel;
    private $outfitsModel;

    public function __construct()
    {
        $this->dashboardModel = new DashboardModel();
        $this->outfitsModel = new OutfitsModel();
    }

    public function index()
    {
        if (!isset($_SESSION['user_role']) || empty($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
            header("Location: ../connexion_shop");
            exit();
        } else {
            // Vérifier les stocks des outfits au chargement du tableau de bord
            $this->outfitsModel->getAllOutfitsAdmin();

            $stats = $this->dashboardModel->getDashboardStats('month');
            error_log("Pending reservations: " . ($stats['pending_reservations'] ?? 0));
            $notifications = $this->dashboardModel->getUnreadNotifications();

            // Vérifier si on est le 1er du mois pour ajouter une notification
            if (date('d') === '01') {
                $notificationModel = new \Models\NotificationModel();
                $existingNotification = $this->dashboardModel->checkNewsletterReminderExists();
                if (!$existingNotification) {
                    $notificationModel->createNotification("Rappel : Envoyez la newsletter mensuelle aujourd'hui ! Rendez-vous dans Gestion de la Newsletter.");
                }
            }

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
                'message_sources' => $stats['message_sources'] ?? ['labels' => [], 'counts' => []],
                'notifications' => $notifications
            ];

            $adminName = "Administrateur";
            $adminProfileImage = "assets/images/admin/default-avatar.png";

            include 'src/app/Views/Admin/dashboard.php';
        }
    }

    public function stats()
    {
        header('Content-Type: application/json');
        $period = $_GET['period'] ?? 'month';
        $stats = $this->dashboardModel->getDashboardStats($period);
        error_log("Stats retournés pour period=$period : " . json_encode($stats));
        echo json_encode($stats);
        exit();
    }
}