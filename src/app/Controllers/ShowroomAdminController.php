<?php
namespace Controllers;

use Models\ShowroomModel;

class ShowroomAdminController
{
    private $model;

    public function __construct()
    {
        $this->model = new ShowroomModel();
    }

    public function list()
    {
        $reservations = $this->model->getAllReservations();
        require_once __DIR__ . '/../Views/Admin/showroom/showroom_list.php';
    }
}
