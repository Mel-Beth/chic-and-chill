<?php
namespace Controllers;

use Models\LocationAdminModel;

class LocationAdminController
{
    private $model;

    public function __construct()
    {
        $this->model = new LocationAdminModel();
    }

    public function index()
    {
        $reservations = $this->model->getAllReservations();
        include('src/app/Views/admin/location/loc_admin.php');
    }
}