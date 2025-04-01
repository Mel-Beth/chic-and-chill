<?php
namespace Controllers;

use Models\LocationModel;

class LocationAdminController
{
    private $model;

    public function __construct()
    {
        $this->model = new LocationModel();
    }

    public function index()
    {
        $reservations = $this->model->getAllReservations();
        require_once __DIR__ . '/../Views/Admin/location/index.php';
    }
}
