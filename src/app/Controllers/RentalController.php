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

    /**
     * Affiche la page rental (vue)
     */
    public function index()
    {
        // On suppose que le routage est géré et que cette méthode inclut la vue
        include('src/app/Views/Public/rental.php');
    }

    /**
     * Traite le formulaire de location (AJAX)
     */
    public function process()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupération des champs
            $product_id = $_POST['product_id'];
            $start_date = $_POST['start_date'];
            $end_date   = $_POST['end_date'];
            
            // Exemple : user_id dans la session (ou par un autre moyen)
            $user_id = $_SESSION['user_id'] ?? 1;

            // Récupération du produit pour calculer le prix total
            $product = $this->rentalModel->getById($product_id);
            $pricePerDay = $product['price'] ?? 0;

            // Calcul de la différence en jours
            $days = (strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24);
            if ($days < 1) {
                $days = 1; // Minimum 1 jour
            }

            $total_price = $pricePerDay * $days;

            $data = [
                'user_id'     => $user_id,
                'product_id'  => $product_id,
                'start_date'  => $start_date,
                'end_date'    => $end_date,
                'total_price' => $total_price,
                'status'      => 'pending'
            ];

            // Insère la location en DB
            $this->rentalModel->addRental($data);

            echo json_encode([
                'success' => true, 
                'message' => 'Location enregistrée !'
            ]);
            exit;
        }
    }
}
