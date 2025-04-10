<?php

namespace Controllers\Events;

use Models\Events\EventsModel;
use Models\Events\OutfitsModel;
use Models\Events\PacksModel;
use Models\NotificationModel;

class EventsController
{
    private $eventsModel;
    private $outfitsModel;
    private $packsModel;
    private $notificationModel;

    // Constructeur : Initialise les modèles nécessaires pour interagir avec la base de données
    public function __construct()
    {
        $this->eventsModel = new EventsModel();         // Modèle pour gérer les événements
        $this->outfitsModel = new OutfitsModel();       // Modèle pour gérer les tenues
        $this->packsModel = new PacksModel();           // Modèle pour gérer les packs
        $this->notificationModel = new NotificationModel(); // Modèle pour gérer les notifications
    }

    // Affiche la liste des événements (passés, à venir) et leurs données associées
    public function index()
    {
        try {
            // Récupère tous les événements, passés et à venir
            $events = $this->eventsModel->getAllEvents();
            $pastEvents = $this->eventsModel->getPastEvents();
            $upcomingEvents = $this->eventsModel->getUpcomingEvents();

            // Récupère les packs et suggestions d'outfits associés aux événements
            $eventPacks = $this->packsModel->getAllPacks();
            $suggestedOutfits = $this->outfitsModel->getAllOutfits();

            // Inclut la vue publique pour afficher les événements
            include('src/app/Views/Public/events/evenements.php');
        } catch (\Exception $e) {
            // En cas d'erreur, log l'exception et affiche un message générique
            error_log($e->getMessage());
            echo "Une erreur est survenue. Veuillez réessayer plus tard.";
        }
    }

    // Affiche les détails d'un événement spécifique en fonction de son ID
    public function showEvent($id)
    {
        try {
            // Récupère les informations de l'événement et ses médias (images/vidéos)
            $event = $this->eventsModel->getEventById($id);
            $eventMedia = $this->eventsModel->getEventMedia($id);

            // Récupère les événements précédent et suivant pour la navigation
            $prevEvent = $this->eventsModel->getPrevEvent($event['date_event']);
            $nextEvent = $this->eventsModel->getNextEvent($event['date_event']);

            // Inclut la vue publique pour afficher les détails de l'événement
            include('src/app/Views/Public/events/evenement_detail.php');
        } catch (\Exception $e) {
            // Log l'erreur et affiche un message en cas de problème
            error_log($e->getMessage());
            echo "Une erreur est survenue. Veuillez réessayer plus tard.";
        }
    }

    // Gère l'upload d'une image, avec gestion d'une image existante à remplacer si nécessaire
    public function handleImageUpload($file, $existingImage = null)
    {
        // Si aucun fichier n'est uploadé, retourne l'image existante
        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            return $existingImage;
        }

        // Vérifie s'il y a une erreur lors de l'upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        // Définit le répertoire d'upload et le crée s'il n'existe pas
        $uploadDir = 'assets/images/events/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Génère un nom unique pour le fichier et définit son chemin
        $fileName = uniqid() . '_' . basename($file['name']);
        $uploadPath = $uploadDir . $fileName;

        // Déplace le fichier uploadé vers le répertoire cible
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // Si une image existante est fournie, la supprime
            if ($existingImage && file_exists($uploadDir . $existingImage)) {
                unlink($uploadDir . $existingImage);
            }
            return $fileName; // Retourne le nouveau nom de fichier
        }
        return false; // Échec de l'upload
    }

    // Ajoute un nouvel événement via une requête POST
    public function addEvent()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Nettoie et récupère les données du formulaire
            $title = htmlspecialchars($_POST['title']);
            $description = htmlspecialchars($_POST['description']);
            $date_event = $_POST['date_event'];
            $location = htmlspecialchars($_POST['location']);
            $status = $_POST['status'];

            // Gère l'upload de l'image associée à l'événement
            $image = $this->handleImageUpload($_FILES['image']);
            if ($image === false) {
                header("Location: ../evenements?success=0&action=add");
                exit();
            }

            // Ajoute l'événement dans la base de données
            $success = $this->eventsModel->addEvent($title, $description, $date_event, $location, $status, $image);

            // Redirige avec un message de succès ou d'échec
            if ($success) {
                header("Location: ../evenements?success=1&action=add");
                exit();
            } else {
                header("Location: ../evenements?success=0&action=add");
                exit();
            }
        }
    }

    // Met à jour un événement existant via une requête POST
    public function updateEvent($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Nettoie et récupère les données du formulaire
            $title = htmlspecialchars($_POST['title']);
            $description = htmlspecialchars($_POST['description'] ?? '');
            $date_event = $_POST['date_event'];
            $location = htmlspecialchars($_POST['location'] ?? '');
            $status = $_POST['status'];

            // Récupère l'événement actuel pour conserver l'image existante si nécessaire
            $currentEvent = $this->eventsModel->getEventById($id);
            $currentImage = $currentEvent['image'] ?? null;

            // Gère l'upload d'une nouvelle image ou conserve l'ancienne
            $image = $this->handleImageUpload($_FILES['image'], $currentImage);
            if ($image === false) {
                header("Location: ../?success=0&action=update");
                exit();
            }

            // Met à jour l'événement dans la base de données
            $success = $this->eventsModel->updateEvent($id, $title, $description, $date_event, $location, $status, $image);

            // Redirige avec un message de succès ou d'échec
            if ($success) {
                header("Location: ../?success=1&action=update");
                exit();
            } else {
                header("Location: ../?success=0&action=update");
                exit();
            }
        }
    }

    // Configure un événement actif (ajout de médias)
    public function configureEvent($id)
    {
        // Récupère l'événement pour vérifier son statut
        $event = $this->eventsModel->getEventById($id);
        if (!$event || $event['status'] !== 'active') {
            header("Location: admin/evenements?error=invalid_event");
            exit();
        }

        // Gère l'upload de médias (images/vidéos) via une requête POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['media'])) {
            $files = $_FILES['media'];
            $uploadDir = 'assets/images/events/';

            // Boucle sur chaque fichier uploadé
            for ($i = 0; $i < count($files['name']); $i++) {
                $fileName = uniqid() . '-' . basename($files['name'][$i]);
                $filePath = $uploadDir . $fileName;
                $fileType = mime_content_type($files['tmp_name'][$i]);
                $type = strpos($fileType, 'image') === 0 ? 'image' : (strpos($fileType, 'video') === 0 ? 'video' : null);

                // Ajoute le média à l'événement si le type est valide
                if ($type && move_uploaded_file($files['tmp_name'][$i], $filePath)) {
                    $this->eventsModel->addEventMedia($id, $filePath, $type);
                }
            }
            header("Location: admin/evenements/configurer/$id?success=media_added");
            exit();
        }

        // Inclut la vue d'administration pour configurer l'événement
        include('src/app/Views/Admin/events/configure_event.php');
    }

    // Supprime un média associé à un événement
    public function deleteEventMedia($mediaId)
    {
        $this->eventsModel->deleteEventMedia($mediaId);
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit();
    }

    // Supprime un événement spécifique
    public function deleteEvent($id)
    {
        $success = $this->eventsModel->deleteEvent($id);

        // Redirige avec un message de succès ou d'échec
        if ($success) {
            header("Location: ../?success=1&action=delete");
            exit();
        } else {
            header("Location: ../?success=0&action=delete");
            exit();
        }
    }

    // Gère l'affichage de la liste des événements pour l'administration
    public function manageEvents()
    {
        try {
            // Récupère tous les événements pour l'admin
            $events = $this->eventsModel->getAllEventsAdmin();

            // Si aucun événement n'est trouvé, initialise un tableau vide
            if (!$events) {
                $events = [];
            }

            // Récupère le paramètre 'success' de l'URL pour indiquer une action réussie ou non
            $success = isset($_GET['success']) ? $_GET['success'] : null;

            // Inclut la vue d'administration pour gérer les événements
            include('src/app/Views/Admin/events/admin_events.php');
        } catch (\Exception $e) {
            // Log l'erreur et affiche un message en cas de problème
            error_log($e->getMessage());
            echo "Erreur lors du chargement des événements.";
        }
    }
}
?>