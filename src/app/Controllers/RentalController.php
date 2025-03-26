<?php
namespace Controllers;

use Models\RentalModel;

class RentalController
{
    private $rentalModel;

    public function __construct()
    {
        $this->rentalModel = new RentalModel();
    }

    public function index()
    {
        $products = $this->rentalModel->getAllLocation();
        include 'src/app/Views/Public/rental.php';
    }

    public function show($id)
    {
        $product = $this->rentalModel->getById($id);
        if (!$product) {
            echo "Produit introuvable";
            return;
        }
        include 'src/app/Views/Public/rental_show.php';
    }

    public function process()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product_id = $_POST['product_id'];
            $start_date = $_POST['start_date'];
            $end_date   = $_POST['end_date'];
            $user_id    = $_SESSION['user_id'] ?? 1;

            $product = $this->rentalModel->getById($product_id);
            $price   = $product['price'] ?? 0;

            $days = (strtotime($end_date) - strtotime($start_date)) / (606024);
            if ($days < 1) $days = 1;

            $total_price = $price * $days;

            $data = [
                'user_id' => $user_id,
                'product_id' => $product_id,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'total_price' => $total_price,
                'status' => 'pending'
            ];
            $this->rentalModel->addRental($data);

            echo json_encode(['success' => true, 'message' => 'Location enregistrée !']);
            exit;
        }
    }
}
