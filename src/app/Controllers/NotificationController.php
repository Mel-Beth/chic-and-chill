<?php

namespace Controllers;

use Models\NotificationModel;

class NotificationController
{
    private $notificationModel;

    // Constructeur : Initialise le modèle de notifications
    public function __construct()
    {
        $this->notificationModel = new NotificationModel(); // Modèle pour gérer les notifications
    }

    // Retourne les notifications non lues au format JSON
    public function getUnreadNotifications()
    {
        header('Content-Type: application/json'); // Définit le type de contenu comme JSON
        $notifications = $this->notificationModel->getUnreadNotifications(); // Récupère les notifications non lues
        echo json_encode($notifications); // Retourne les notifications en JSON
        exit(); // Termine l'exécution
    }

    // Marque une notification spécifique comme lue
    public function markAsRead($id)
    {
        header('Content-Type: application/json'); // Définit le type de contenu comme JSON
        $this->notificationModel->markAsRead($id); // Marque la notification comme lue via le modèle
        echo json_encode(["success" => true]); // Retourne un message de succès en JSON
        exit(); // Termine l'exécution
    }
}
?>