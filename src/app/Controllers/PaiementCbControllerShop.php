<?php

namespace Controllers;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Exception;

require 'vendor/autoload.php'; // Charge Stripe PHP

class PaiementCbControllerShop
{
    private $stripeSecretKey;
    private $yourDomain;

    public function __construct()
    {
        
        $this->stripeSecretKey = 'sk_test_51Qee9BBrAcYVY0bNbJcxBCduVDtGK7aw0282iy4WHVNxuFszOCiLmsK8Wt07MoAJdjcsJq2PIQupiQJ1FYktFCQr006fqiHitA'; // ⚠️ Mets ta vraie clé API ici
        $this->yourDomain = 'http://localhost/site_stage/chic-and-chill'; // Change si nécessaire
        Stripe::setApiKey('sk_test_51Qee9KPkuME3YnyVG0Ighto9Gwz7CshqoI1Bnf8ag4OMUzEEwcEbaiwI5uxMg7eHJa0OCRZ5L8zINcSeKPK3pHlN00iObhMwZi'); // Mets bien ta clé secrète test ici

    }

    public function processPaiement()
    {
        ini_set('display_errors', 0); // Désactive l'affichage des erreurs PHP
        ini_set('log_errors', 1); // Log les erreurs au lieu de les afficher
        header('Content-Type: application/json');
    
        if (!isset($_POST['total']) || empty($_POST['total'])) {
            echo json_encode(['error' => 'Montant total manquant.']);
            http_response_code(400);
            exit;
        }
    
        try {
            $checkout_session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Achat sur Chic & Chill',
                        ],
                        'unit_amount' => intval($_POST['total'] * 100), // Convertit en centimes
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $this->yourDomain . '/paiement_succes',
                'cancel_url' => $this->yourDomain . '/paiement_annule',
            ]);
    
            echo json_encode(['id' => $checkout_session->id]);
    
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}