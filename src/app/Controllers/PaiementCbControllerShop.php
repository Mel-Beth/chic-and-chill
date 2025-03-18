<?php

namespace Controllers;

// controleur de paiement qui gère le paiement et aussi la redirection, vide le panier puis gere le stock en appelant le model MiseAjourSTockShop 

use Stripe\Stripe;
// recup de la session stripe
use Stripe\Checkout\Session;
use Exception;
use Models\MiseAjourStockShop;

require 'vendor/autoload.php'; // pour charger stripe 

class PaiementCbControllerShop
{
    private $stripeSecretKey;
    private $yourDomain;
    private $stockModel;

    

// creation de ce qu'on appelle des instances
    public function __construct()
    {
        $this->stockModel = new MiseAjourStockShop();
        $stripeSecretKey = $_ENV['PRIVATE_STRIPE_API_KEY'];
        $this->stripeSecretKey = 'sk_test_51Qee9KPkuME3YnyVG0Ighto9Gwz7CshqoI1Bnf8ag4OMUzEEwcEbaiwI5uxMg7eHJa0OCRZ5L8zINcSeKPK3pHlN00iObhMwZi'; // sk ici
        $this->yourDomain = 'http://localhost/site_stage/chic-and-chill'; // A changer au besoin
        Stripe::setApiKey('sk_test_51Qee9KPkuME3YnyVG0Ighto9Gwz7CshqoI1Bnf8ag4OMUzEEwcEbaiwI5uxMg7eHJa0OCRZ5L8zINcSeKPK3pHlN00iObhMwZi'); // mettre la clé secrete ici

    }

    public function processPaiement()
    {
        \ini_set('display_errors', 0); // desactive affichage erreur php pr l'utilisateur
        ini_set('log_errors', 1); // erreurs affichées ici dans un  journal d'erreurs, bonne pratique
        header('Content-Type: application/json');

         // Assure que la session est active et sauvegarde le panier AVANT paiement pr retirer du stock après
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    
// Vérifie si le panier existe et calcule le total
$total_panier = 0;
if (!empty($_SESSION['panier'])) {
    foreach ($_SESSION['panier'] as $produit) {
        $total_panier += $produit['prix'] * $produit['quantite'];
    }
}

// Stocke le total dans la session
$_SESSION['total_panier'] = $total_panier;
    $_SESSION['panier_sauvegarde'] = $_SESSION['panier'] ?? []; 
    error_log('🛒 Panier sauvegardé AVANT paiement : ' . print_r($_SESSION['panier_sauvegarde'], true));

        if (!isset($_POST['total']) || empty($_POST['total'])) {
            echo json_encode(['error' => 'Montant total manquant.']);
            http_response_code(400);
            exit;
        }

        try {
            $checkout_session = Session::create ([
                
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Achat sur Chic & Chill',
                        ],
                        'unit_amount' => intval($_POST['total'] * 100), // Convertit en centimes pr le paiement
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                // note pour plus tard, ne pas oublier ce session_id à moins de vouloir passer 5h à comprendre pourquoi le panier ne se vide pas!!!!!!
                'success_url' => $this->yourDomain . '/paiement_succes?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $this->yourDomain . '/paiement_annule',
            ]);

            echo json_encode(['id' => $checkout_session->id]);
        } catch (Exception $e) {
            error_log("Erreur lors de la validation du paiement Stripe : " . $e->getMessage());
            header('Location: paiement_annule');
            exit;
        }
    }
    public function paiementSucces()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_GET['session_id'])) {
        header('Location: paiement_annule');
        exit;
    }

        // pour voir si la session est ok, regarder dans les logs php, je le laisse au cas où
        // error_log('Contenu du panier avant paiement : ' . print_r($_SESSION['panier'], true));
        // error_log('Contenu du panier_sauvegarde avant paiement : ' . print_r($_SESSION['panier_sauvegarde'], true));
        
        try {
            $session_id = $_GET['session_id'];
            $checkoutSession = Session::retrieve($session_id);
    
            if ($checkoutSession->payment_status === 'paid') {
                $_SESSION['panier'] = [];
                $_SESSION['panier_vide_apres_paiement'] = true;
    
                error_log('Contenu de panier_sauvegarde : ' . print_r($_SESSION['panier_sauvegarde'], true));


                //on recup id des articles ds le panier pr mettre a jour le stock ac le model miseAjourStockShop
                if (isset($_SESSION['panier_sauvegarde'])) {
                    foreach ($_SESSION['panier_sauvegarde'] as $idProduit => $produit) {
                        $quantite = isset($produit['quantite']) ? intval($produit['quantite']) : 1; // Récupère uniquement la quantité
                        error_log("🔄 Mise à jour du stock - Produit ID: $idProduit, Quantité: $quantite");
                    
                        $result = $this->stockModel->mettreAJourStock($idProduit, $quantite);
                        if (!$result) {
                            error_log("⚠️ Problème lors de la mise à jour du stock pour le produit $idProduit");
                        }
                    }
                    unset($_SESSION['panier_sauvegarde']);
                }
                include 'src/app/Views/Public/paiement_valide_shop.php';
                exit;
            } else {
                header('Location: paiement_annule');
                exit;
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }
}