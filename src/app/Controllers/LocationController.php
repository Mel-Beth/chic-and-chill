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
    $color = isset($_GET['color']) ? $_GET['color'] : null;

    $products = $this->model->getAllRobeProducts($color);
    $colors = $this->model->getColors();

    require_once __DIR__ . '/../Views/Public/location.php';
}


    public function show($id)
    {
        $product = $this->model->getById($id);
        if (!$product) {
            header("Location: /location");
            exit;
        }
        require_once __DIR__ . '/../Views/Public/details_loc.php';
    }

    public function reserve()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'client_nom'   => $_POST['client_nom'],
                'email'        => $_POST['email'],
                'produit_id'   => $_POST['product_id'],
                'date_debut'   => $_POST['date_debut'],
                'date_fin'     => $_POST['date_fin'],
            ];

            $this->model->addReservation($data);
            header("Location: /location/confirmation");
            exit;
        }
    }

    public function confirmation()
    {
        include( 'src/app/Views/Public/confirmation.php');
    }
}
