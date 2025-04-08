<?php

namespace Controllers;

use Models\LocationModel;

class LocationController
{
    private $model;

    public function __construct()
    {
        $this->model = new LocationModel();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index()
    {
        $products = $this->model->getAllRobeProducts();
        include 'src/app/Views/Public/location.php';
    }

    public function show($id)
    {
        $product = $this->model->getById($id);
        if (!$product) {
            header("Location: /site_stage/chic-and-chill/location");
            exit;
        }
        include 'src/app/Views/Public/details_loc.php';
    }

    public function addToCart()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['panier'])) {
                $_SESSION['panier'] = [];
            }

            $productId = $_POST['product_id'] ?? null;
            $productName = $_POST['product_name'] ?? 'Nom non disponible';
            $price = (float)($_POST['price'] ?? 0);
            $quantity = (int)($_POST['quantity'] ?? 1);

            if (!$productId) {
                header("Location: /site_stage/chic-and-chill/location?error=missing_product_id");
                exit;
            }

            $_SESSION['panier'][] = [
                'product_id' => $productId,
                'name' => $productName,
                'price' => $price,
                'quantity' => $quantity
            ];

            header("Location: /site_stage/chic-and-chill/panier_loc");
            exit;
        }
    }

    public function panier()
    {
        if (!isset($_SESSION['panier'])) {
            $_SESSION['panier'] = [];
        }
        $panier = $_SESSION['panier'];
        include 'src/app/Views/Public/panier_loc.php';
    }

    public function removeFromCart()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $index = (int)($_POST['index'] ?? -1);

            if ($index >= 0 && isset($_SESSION['panier'][$index])) {
                unset($_SESSION['panier'][$index]);
                $_SESSION['panier'] = array_values($_SESSION['panier']);
            }

            header("Location: /site_stage/chic-and-chill/panier_loc");
            exit;
        }
    }

    public function validateCart()
    {
        if (isset($_SESSION['panier']) && count($_SESSION['panier']) > 0) {
            header("Location: /site_stage/chic-and-chill/reserve");
            exit;
        } else {
            header("Location: /site_stage/chic-and-chill/location");
            exit;
        }
    }

    public function reserve()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $clientNom = $_POST['client_nom'] ?? null;
            $email = $_POST['email'] ?? null;
            $startDate = $_POST['start_date'] ?? null;
            $endDate = $_POST['end_date'] ?? null;

            if (!$clientNom || !$email || !$startDate || !$endDate || !isset($_SESSION['panier'])) {
                header("Location: /site_stage/chic-and-chill/reserve?error=missing_data");
                exit;
            }

            $pdo = new \PDO("mysql:host=localhost;dbname=chicandchill", "root", "");
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            foreach ($_SESSION['panier'] as $produit) {
                $produitId = $produit['product_id'];

                $stmt = $pdo->prepare("INSERT INTO location_reservations (client_nom, email, produit_id, date_debut, date_fin, statut, created_at)
                                       VALUES (?, ?, ?, ?, ?, 'en attente', NOW())");

                $stmt->execute([$clientNom, $email, $produitId, $startDate, $endDate]);
            }

            unset($_SESSION['panier']);

            header("Location: /site_stage/chic-and-chill/confirmation.php");
            exit;
        } else {
            header("Location: /site_stage/chic-and-chill/location");
            exit;
        }
    }
        public function showReservationForm()
    {
        if (!isset($_SESSION['panier']) || count($_SESSION['panier']) === 0) {
            // Si le panier est vide, rediriger vers la page de location
            header("Location: /site_stage/chic-and-chill/location");
            exit;
        }
        include 'src/app/Views/Public/reserve.php';
    }

}