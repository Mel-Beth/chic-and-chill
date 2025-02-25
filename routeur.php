<?php
// Détecter automatiquement le chemin du projet
define("BASE_URL", "/site_stage/chic-and-chill/");

// Routeur principal de l'application

// Récupération de la route depuis l'URL, suppression des éventuels espaces et des slashes au début/fin
$_GET["route"] = trim($_GET["route"] ?? '', "/");

// Séparation de la route en segments (chaque élément séparé par "/")
$route = explode("/", $_GET["route"]);

// Protection contre les attaques XSS en nettoyant chaque élément de la route
$route = array_map('htmlspecialchars', $route);

// Vérification si aucune route spécifique n'est définie (ex: accès à la racine du site)
if (empty($route[0])) {
    // On charge le contrôleur principal (page d'accueil)
    (new Controllers\HomeController())->index();
} else {
    try {
        // Gestion des différentes routes possibles

        switch ($route[0]) {
            case 'accueil': // Si l'utilisateur accède à "/accueil"
                $controller = new Controllers\HomeController();
                $controller->index();
                break;

            case "evenements": // Si l'utilisateur accède à "/evenements"
                $controller = new Controllers\EventsController();
                // Vérifie si un ID est passé pour afficher un événement en détail
                if (!empty($route[1]) && is_numeric($route[1])) {
                    $controller->showEvent($route[1]);
                } else {
                    $controller->index();
                }
                break;

            case 'evenement_detail':
                $controller = new Controllers\EventsController();
                if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
                    $controller->showEvent($_GET['id']);
                } else {
                    include('src/app/Views/404.php');
                }
                break;

            case 'pack_detail':
                $controller = new Controllers\PackController();
                if (!empty($route[1]) && is_numeric($route[1])) {
                    $controller->showPack($route[1]); // On passe l'ID du pack
                } else {
                    include('src/app/Views/404.php');
                }
                break;

            case 'reservation_evenement':
                $controller = new Controllers\ReservationController();
                $controller->reservationEvenement();
                break;

            case 'reservation_pack':
                $controller = new Controllers\ReservationController();
                if (!empty($_GET['pack_id']) && is_numeric($_GET['pack_id'])) {
                    $controller->reservationPack($_GET['pack_id']);
                } else {
                    include('src/app/Views/404.php');
                }
                break;

            case 'reservation_process':
                $controller = new Controllers\ReservationController();
                $controller->processReservation();
                break;

            case 'confirmation_reservation':
                include('src/app/Views/Public/confirmation_reservation.php');
                break;

            case 'location': // Si l'utilisateur accède à "/location"
                $controller = new Controllers\LocationController();
                $controller->index();
                break;

            case 'magasin': // Si l'utilisateur accède à "/magasin"
                $controller = new Controllers\ShopController();
                $controller->index();
                break;

            case 'contact_magasin':
                include('src/app/Views/Public/contact_magasin.php');
                break;

            case 'contact_location':
                include('src/app/Views/Public/contact_location.php');
                break;

            case 'contact_evenements':
                include('src/app/Views/Public/contact_evenements.php');
                break;

            case 'contact_process':
                $controller = new Controllers\ContactController();
                $controller->processContactForm();
                break;

            case 'newsletter':
                $controller = new Controllers\ContactController();
                $controller->processNewsletter();
                break;

                // 📌 Routes Admin
            case 'admin/dashboard':
                $controller = new Controllers\HomeController();
                $controller->dashboard();
                break;

            case 'admin/payments':
                $controller = new Controllers\AdminEventsController();
                $controller->managePayments();
                break;

            case 'admin/export':
                $controller = new Controllers\AdminEventsController();
                $controller->exportData($_GET['type'] ?? 'reservations');
                break;

            case 'admin/evenements':
                $controller = new Controllers\EventsController();
                $controller->index();
                break;

            case 'admin/evenements/ajouter':
                $controller = new Controllers\EventsController();
                $controller->addEvent();
                break;

            case 'admin/evenements/modifier':
                $controller = new Controllers\EventsController();
                $controller->updateEvent($_GET['id'] ?? null);
                break;

            case 'admin/evenements/supprimer':
                $controller = new Controllers\EventsController();
                $controller->deleteEvent($_GET['id'] ?? null);
                break;

            case 'admin/packs':
                $controller = new Controllers\PackController();
                $controller->managePacks();
                break;

            case 'admin/packs/ajouter':
                $controller = new Controllers\PackController();
                $controller->addPack();
                break;

            case 'admin/packs/modifier':
                $controller = new Controllers\PackController();
                $controller->updatePack($_GET['id'] ?? null);
                break;

            case 'admin/packs/supprimer':
                $controller = new Controllers\PackController();
                $controller->deletePack($_GET['id'] ?? null);
                break;

            case 'admin/reservations':
                $controller = new Controllers\ReservationController();
                $controller->reservations();
                break;

            case 'admin/reservations/detail':
                $controller = new Controllers\ReservationController();
                $controller->showReservation($_GET['id'] ?? null);
                break;

            case 'admin/reservations/modifier':
                $controller = new Controllers\ReservationController();
                $controller->updateReservationStatus($_GET['id'] ?? null, $_GET['status'] ?? null);
                break;

            case 'admin/users':
                $controller = new Controllers\UsersController();
                $controller->users();
                break;

            case 'admin/users/modifier':
                $controller = new Controllers\UsersController();
                $controller->updateUser($_GET['id'] ?? null);
                break;

            case 'admin/users/supprimer':
                $controller = new Controllers\UsersController();
                $controller->deleteUser($_GET['id'] ?? null);
                break;

            case 'admin/messages':
                $controller = new Controllers\ContactController();
                $controller->manageMessages();
                break;

            case 'admin/messages/supprimer':
                $controller = new Controllers\ContactController();
                $controller->deleteMessage($_GET['id'] ?? null);
                break;

            case 'admin/newsletter':
                $controller = new Controllers\NewsletterController();
                $controller->manageNewsletter();
                break;

            case 'admin/newsletter/supprimer':
                $controller = new Controllers\NewsletterController();
                $controller->deleteSubscriber($_GET['id'] ?? null);
                break;

            case 'admin/outfits':
                $controller = new Controllers\OutfitsController();
                $controller->manageOutfits();
                break;

            case 'admin/outfits/ajouter':
                $controller = new Controllers\OutfitsController();
                $controller->addOutfit();
                break;

            case 'admin/outfits/modifier':
                $controller = new Controllers\OutfitsController();
                $controller->updateOutfit($_GET['id'] ?? null);
                break;

            case 'admin/outfits/supprimer':
                $controller = new Controllers\OutfitsController();
                $controller->deleteOutfit($_GET['id'] ?? null);
                break;

            case 'conditions_generales':
                include('src/app/Views/Public/conditions_generales_vente_shop.php');
                break;

            case 'accueil_shop';
                include('src/app/Views/Public/accueil_shop.php');
                break;

                // Afficher la page des produits dans le shop
            case 'produit_shop':
                $controller = new Controllers\ArticleControllerShop(); // ✅ Utilisation du bon contrôleur
                $controller->showProducts();
                break;


                case 'produit_detail_shop':
                    $controller = new Controllers\ProduitDetailControllerShop();
                    $controller->afficherDetailProduit();
                    break;
                



            case 'connexion_shop':
                $controller = new Controllers\ConnexionControllersShop();
                $controller->loginUserShop();

                // Si la requête est GET, on affiche la page de connexion
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    include 'src/app/Views/Public/connexion_shop.php';
                }
                break;



            case 'profil_user_shop':
                $controller = new Controllers\ProfilControllersShop();
                $controller->showUserProfile();
                break;



            case 'inscription_shop':
                $controller = new Controllers\InscriptionControllersShop();
                $controller->registerUserShop();
                break;



            case 'deconnexion_shop':
                $controller = new Controllers\DecoShopController();
                $controller->logout();
                break;


                // Page du panier
                case 'panier_shop':
                    $controller = new Controllers\PanierControllerShop();
                    
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $controller->ajouterAuPanier();
                    } else {
                        $controller->afficherPanier();
                    }
                    break;
                

                // Modifier la quantité d'un produit dans le panier
            case 'modifier_panier':
                $controller = new Controllers\PanierControllerShop();
                $controller->modifierQuantite();
                break;

                // Supprimer un produit du panier
            case 'supprimer_panier':
                $controller = new Controllers\PanierControllerShop();
                $controller->supprimerProduit();
                header("Location: panier_shop"); // ✅ On reste sur la page panier
                exit;
                break;


                // Vider complètement le panier
            case 'vider_panier':
                $controller = new Controllers\PanierControllerShop();
                $controller->viderPanier();
                break;




            // Page du paiement
            case 'paiement_cb_shop':
                $controller = new Controllers\PaiementCbControllerShop();
                $controller->processPaiement();
                break;

            // Page de succès du paiement
            case 'paiement_succes':
                include 'src/app/Views/Public/paiement_valide_shop.php';
                break;

            // Page d'annulation du paiement
            case 'paiement_annule':
                include 'src/app/Views/Public/paiement_annule_shop.php';
                break;





            default:
                // Si la route n'est pas reconnue, on affiche une page 404
                include('src/app/Views/404.php');
        }
    } catch (Exception $e) {
        // Enregistrement de l'erreur dans les logs pour le suivi des erreurs
        error_log($e->getMessage());

        // Inclusion de la page 404 pour informer l'utilisateur
        include('src/app/Views/404.php');
        exit();
    }
}
