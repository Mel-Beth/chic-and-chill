<?php
namespace Controllers;

use Models\LocationModel;

class LocationController
{
    private $model;

    public function __construct()
    {
        $this->model = new LocationModel();
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
            header("Location: /location");
            exit;
        }
        include 'src/app/Views/Public/details_loc.php';
    }

    public function reserve()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'client_nom' => $_POST['client_nom'],
                'email' => $_POST['email'],
                'produit_id' => $_POST['product_id'],
                'date_debut' => $_POST['date_debut'],
                'date_fin' => $_POST['date_fin']
            ];

            $this->model->addReservation($data);
            header("Location: /site_stage/chic-and-chill/panier_loc");
            exit;
        }
    }

    public function panier()
    {
        include 'src/app/Views/Public/panier_loc.php';
    }
}