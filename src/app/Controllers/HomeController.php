<?php

namespace Controllers;

use Models\ProductModel;

class HomeController
{
    public function index()
    {
        try {
            include('src/app/Views/Public/accueil.php');
        } catch (\Exception $e) {
            error_log($e->getMessage());
            echo "Une erreur est survenue. Veuillez réessayer plus tard.";
        }
    }
}