<?php namespace Controllers;

use Models\ShowroomModel;

class ShowroomAdminController
{
    private $model;

    public function __construct()
    {
        $this->model = new ShowroomModel();
    }

    public function index()
    {
        $reservations = $this->model->getAllReservations();
        include 'src/app/Views/Admin/showroom/show_admin.php';
    }
}