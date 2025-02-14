<?php

namespace Controllers;

use Models\AdminEventsModel;

class AdminEventsController
{
    private $adminModel;

    public function __construct()
    {
        $this->adminModel = new AdminEventsModel();
    }

    /** ========== 📌 GESTION DES ÉVÉNEMENTS ========== */

    public function index()
    {
        $events = $this->adminModel->getAllEvents();
        include('src/app/Views/Admin/admin_events.php');
    }

    public function addEvent()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = htmlspecialchars($_POST['title']);
            $description = htmlspecialchars($_POST['description']);
            $date_event = $_POST['date_event'];
            $status = $_POST['status'];

            $success = $this->adminModel->createEvent($title, $description, $date_event, $status);

            header("Location: " . BASE_URL . "admin/evenements?success=" . ($success ? 1 : 0));
            exit();
        }
    }

    public function updateEvent($event_id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = htmlspecialchars($_POST['title']);
            $description = htmlspecialchars($_POST['description']);
            $date_event = $_POST['date_event'];
            $status = $_POST['status'];

            $success = $this->adminModel->updateEvent($event_id, $title, $description, $date_event, $status);

            header("Location: " . BASE_URL . "admin/evenements?success=" . ($success ? 1 : 0));
            exit();
        }
    }

    public function deleteEvent($event_id)
    {
        $success = $this->adminModel->deleteEvent($event_id);
        header("Location: " . BASE_URL . "admin/evenements?success=" . ($success ? 1 : 0));
        exit();
    }

    /** ========== 📌 GESTION DES PACKS ÉVÉNEMENTIELS ========== */

    public function managePacks()
    {
        $packs = $this->adminModel->getAllPacks();
        include('src/app/Views/Admin/admin_packs.php');
    }

    public function addPack()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $event_id = $_POST['event_id'];
            $title = htmlspecialchars($_POST['title']);
            $description = htmlspecialchars($_POST['description']);
            $price = $_POST['price'];
            $stock = $_POST['stock'];

            $success = $this->adminModel->createPack($event_id, $title, $description, $price, $stock);

            header("Location: " . BASE_URL . "admin/packs?success=" . ($success ? 1 : 0));
            exit();
        }
    }

    public function updatePack($pack_id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = htmlspecialchars($_POST['title']);
            $description = htmlspecialchars($_POST['description']);
            $price = $_POST['price'];
            $stock = $_POST['stock'];

            $success = $this->adminModel->updatePack($pack_id, $title, $description, $price, $stock);

            header("Location: " . BASE_URL . "admin/packs?success=" . ($success ? 1 : 0));
            exit();
        }
    }

    public function deletePack($pack_id)
    {
        $success = $this->adminModel->deletePack($pack_id);
        header("Location: " . BASE_URL . "admin/packs?success=" . ($success ? 1 : 0));
        exit();
    }

    /** ========== 📌 GESTION DES RÉSERVATIONS ========== */

    public function manageReservations()
    {
        $reservations = $this->adminModel->getAllReservations();
        include('src/app/Views/Admin/admin_reservations.php');
    }

    public function updateReservationStatus($reservation_id, $status)
    {
        $success = $this->adminModel->updateReservationStatus($reservation_id, $status);
        header("Location: " . BASE_URL . "admin/reservations?success=" . ($success ? 1 : 0));
        exit();
    }

    /** ========== 📌 GESTION DES UTILISATEURS ========== */

    public function manageUsers()
    {
        $users = $this->adminModel->getAllUsers();
        include('src/app/Views/Admin/admin_users.php');
    }

    public function deleteUser($user_id)
    {
        $success = $this->adminModel->deleteUser($user_id);
        header("Location: " . BASE_URL . "admin/utilisateurs?success=" . ($success ? 1 : 0));
        exit();
    }

    /** ========== 📌 GESTION DES MESSAGES DE CONTACT ========== */

    public function manageMessages()
    {
        $messages = $this->adminModel->getAllMessages();
        include('src/app/Views/Admin/admin_messages.php');
    }

    public function deleteMessage($message_id)
    {
        $success = $this->adminModel->deleteMessage($message_id);
        header("Location: " . BASE_URL . "admin/messages?success=" . ($success ? 1 : 0));
        exit();
    }

    /** ========== 📌 GESTION DES ABONNÉS NEWSLETTER ========== */

    public function manageNewsletter()
    {
        $subscribers = $this->adminModel->getAllSubscribers();
        include('src/app/Views/Admin/admin_newsletter.php');
    }

    public function deleteSubscriber($subscriber_id)
    {
        $success = $this->adminModel->deleteSubscriber($subscriber_id);
        header("Location: " . BASE_URL . "admin/newsletter?success=" . ($success ? 1 : 0));
        exit();
    }

    /** ========== 📌 GESTION DES IDÉES DE TENUES ========== */

    public function manageOutfits()
    {
        $outfits = $this->adminModel->getAllOutfits();
        include('src/app/Views/Admin/admin_outfits.php');
    }

    public function addOutfit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = htmlspecialchars($_POST['title']);
            $description = htmlspecialchars($_POST['description']);
            $image = $_POST['image'] ?? null;
            $products = isset($_POST['products']) ? implode(",", $_POST['products']) : '';

            $success = $this->adminModel->createOutfit($title, $description, $image, $products);

            header("Location: " . BASE_URL . "admin/outfits?success=" . ($success ? 1 : 0));
            exit();
        }
    }

    public function updateOutfit($outfit_id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = htmlspecialchars($_POST['title']);
            $description = htmlspecialchars($_POST['description']);
            $image = $_POST['image'] ?? null;
            $products = isset($_POST['products']) ? implode(",", $_POST['products']) : '';

            $success = $this->adminModel->updateOutfit($outfit_id, $title, $description, $image, $products);

            header("Location: " . BASE_URL . "admin/outfits?success=" . ($success ? 1 : 0));
            exit();
        }
    }

    public function deleteOutfit($outfit_id)
    {
        $success = $this->adminModel->deleteOutfit($outfit_id);
        header("Location: " . BASE_URL . "admin/outfits?success=" . ($success ? 1 : 0));
        exit();
    }

    /** ========== 📌 NOTIFICATIONS POUR LES ARTICLES VENDUS ========== */

    public function checkOutfitStock()
    {
        $outfits = $this->adminModel->getAllOutfits();
        $notifications = [];

        foreach ($outfits as $outfit) {
            $product_ids = explode(",", $outfit['products']);
            foreach ($product_ids as $product_id) {
                $stock = $this->adminModel->getProductStock($product_id);
                if ($stock === 0) {
                    $notifications[] = "L'article ID {$product_id} de l'idée de tenue '{$outfit['title']}' est en rupture de stock. Veuillez le remplacer ou supprimer l'idée.";
                }
            }
        }

        return $notifications;
    }

    public function dashboard()
    {
        header('Content-Type: text/plain'); // Empêche l'affichage HTML
        print_r(headers_list()); // Affiche les headers envoyés
        die();
    }

    public function showReservation($id)
    {
        if (!$id || !is_numeric($id)) {
            include 'src/app/Views/404.php';
            return;
        }
        $reservation = $this->model->getReservationById($id);
        include 'src/app/Views/Admin/reservation_detail.php';
    }

    public function updateUser($id)
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $name = $_POST["name"] ?? "";
            $email = $_POST["email"] ?? "";
            $this->model->updateUser($id, $name, $email);
            header("Location: /admin/users");
            exit();
        }
        $user = $this->model->getUserById($id);
        include 'src/app/Views/Admin/user_edit.php';
    }

    public function managePayments()
    {
        $payments = $this->model->getAllPayments();
        include 'src/app/Views/Admin/payments.php';
    }

    public function exportData($type)
    {
        if (!in_array($type, ['reservations', 'users', 'payments'])) {
            include 'src/app/Views/404.php';
            return;
        }
        $data = $this->model->getDataForExport($type);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="export_' . $type . '.csv"');

        $output = fopen('php://output', 'w');
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        fclose($output);
        exit();
    }
}
