<?php

namespace Controllers;

use Models\LocationModel;

class LocationAdminController
{
    private $locationModel;

    public function __construct()
    {
        $this->locationModel = new LocationModel();
    }

    public function index()
    {
        $locations = $this->locationModel->getAllLocations();
        require_once __DIR__ . '/../Views/admin/location/index.php';
    }

    // Méthodes similaires à create, edit, delete, etc.
    // adaptées à l’interface d’admin
}
