<?php

namespace Controllers;

use Models\DashboardModel;
use Models\Events\OutfitsModel;

class DashboardController
{
    private $dashboardModel;
    private $outfitsModel;

    // Constructeur : Initialise les modèles nécessaires pour le tableau de bord
    public function __construct()
    {
        $this->dashboardModel = new DashboardModel();  // Modèle pour récupérer les statistiques du tableau de bord
        $this->outfitsModel = new OutfitsModel();      // Modèle pour gérer les tenues (outfits)
    }

    // Affiche le tableau de bord principal pour les administrateurs
    public function index()
    {
        // Vérifie si l'utilisateur est connecté et a le rôle d'administrateur
        if (!isset($_SESSION['user_role']) || empty($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
            header("Location: ../connexion_shop"); // Redirige vers la page de connexion si non autorisé
            exit();
        } else {
            // Charge les données des tenues pour vérifier les stocks au démarrage
            $this->outfitsModel->getAllOutfitsAdmin();

            // Récupère les statistiques du tableau de bord pour le mois en cours
            $stats = $this->dashboardModel->getDashboardStats('month');
            error_log("Pending reservations: " . ($stats['pending_reservations'] ?? 0)); // Log des réservations en attente
            $notifications = $this->dashboardModel->getUnreadNotifications(); // Récupère les notifications non lues

            // Vérifie si c'est le 1er jour du mois pour ajouter un rappel de newsletter
            if (date('d') === '01') {
                $notificationModel = new \Models\NotificationModel(); // Initialise le modèle de notifications
                $existingNotification = $this->dashboardModel->checkNewsletterReminderExists(); // Vérifie si le rappel existe déjà
                if (!$existingNotification) {
                    // Crée une notification si elle n'existe pas
                    $notificationModel->createNotification("Rappel : Envoyez la newsletter mensuelle aujourd'hui ! Rendez-vous dans Gestion de la Newsletter.");
                }
            }

            // Prépare les données à passer à la vue sous forme de tableau associatif
            $dashboardData = [
                'messages_count' => $stats['messages_count'] ?? 0,         // Nombre de messages reçus
                'active_events' => $stats['active_events'] ?? 0,           // Nombre d'événements actifs
                'total_events' => $stats['total_events'] ?? 0,             // Nombre total d'événements
                'pending_reservations' => $stats['pending_reservations'] ?? 0, // Réservations en attente
                'subscribers_count' => $stats['subscribers_count'] ?? 0,   // Nombre d'abonnés
                'reservation_months' => $stats['reservation_months'] ?? [], // Mois des réservations (pour graphiques)
                'reservation_counts' => $stats['reservation_counts'] ?? [], // Nombre de réservations par mois
                'packs_labels' => $stats['packs_labels'] ?? [],            // Étiquettes des packs (pour graphiques)
                'packs_counts' => $stats['packs_counts'] ?? [],            // Nombre de réservations par pack
                'message_sources' => $stats['message_sources'] ?? ['labels' => [], 'counts' => []], // Sources des messages
                'notifications' => $notifications                          // Liste des notifications
            ];

            // Définit des valeurs par défaut pour l'affichage (nom et image de l'admin)
            $adminName = "Administrateur";
            $adminProfileImage = "assets/images/admin/default-avatar.png";

            // Inclut la vue du tableau de bord avec les données préparées
            include 'src/app/Views/Admin/dashboard.php';
        }
    }

    // Retourne les statistiques du tableau de bord au format JSON pour une période donnée
    public function stats()
    {
        header('Content-Type: application/json'); // Définit le type de contenu de la réponse comme JSON
        $period = $_GET['period'] ?? 'month';     // Récupère la période demandée, par défaut "month"
        $stats = $this->dashboardModel->getDashboardStats($period); // Récupère les statistiques pour la période
        error_log("Stats retournés pour period=$period : " . json_encode($stats)); // Log des stats pour débogage
        echo json_encode($stats);                 // Retourne les stats au format JSON
        exit();                                   // Termine l'exécution
    }
}
?>