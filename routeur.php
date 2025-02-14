<?php
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
            case 'admin':
                // // Vérifie si l'utilisateur est admin (id_role = 1)
                // if (!isset($_SESSION['user']) || $_SESSION['user']['id_role'] != 1) {
                //     header('Location: login');
                //     exit();
                // }

                // Gestion des sous-routes Admin
                switch ($route[1] ?? 'dashboard') {

                    case 'dashboard':
                        $controller = new Controllers\HomeController();
                        $controller->dashboard();
                        break;

                    case 'payments':
                        $controller = new Controllers\PaymentsController();
                        $controller->managePayments();
                        break;

                    case 'export':
                        $controller = new Controllers\ExportController();
                        $controller->exportData($_GET['type'] ?? 'reservations');
                        break;

                    case 'evenements':
                        $controller = new Controllers\EventsController();
                        if (!isset($route[2])) {
                            $controller->manageEvents();
                        } elseif ($route[2] === 'ajouter') {
                            $controller->addEvent();
                        } elseif ($route[2] === 'modifier' && isset($route[3]) && ctype_digit($route[3])) {
                            $controller->updateEvent((int) $route[3]);
                        } elseif ($route[2] === 'supprimer' && isset($route[3]) && ctype_digit($route[3])) {
                            $controller->deleteEvent((int) $route[3]);
                        }
                        break;

                    case 'packs':
                        $controller = new Controllers\PackController();
                        if (!isset($route[2])) {
                            $controller->managePacks();
                        } elseif ($route[2] === 'ajouter') {
                            $controller->addPack();
                        } elseif ($route[2] === 'modifier' && isset($route[3]) && ctype_digit($route[3])) {
                            $controller->updatePack((int) $route[3]);
                        } elseif ($route[2] === 'supprimer' && isset($route[3]) && ctype_digit($route[3])) {
                            $controller->deletePack((int) $route[3]);
                        }
                        break;

                    case 'reservations':
                        $controller = new Controllers\ReservationController();
                        if (!isset($route[2])) {
                            $controller->reservations();
                        } elseif ($route[2] === 'detail' && isset($route[3]) && ctype_digit($route[3])) {
                            $controller->showReservation((int) $route[3]);
                        } elseif ($route[2] === 'modifier' && isset($route[3]) && ctype_digit($route[3]) && isset($_GET['status'])) {
                            $controller->updateReservationStatus((int) $route[3], $_GET['status']);
                        }
                        break;

                    case 'users':
                        $controller = new Controllers\UsersController();
                        if (!isset($route[2])) {
                            $controller->users();
                        } elseif ($route[2] === 'modifier' && isset($route[3]) && ctype_digit($route[3])) {
                            $controller->updateUser((int) $route[3]);
                        } elseif ($route[2] === 'supprimer' && isset($route[3]) && ctype_digit($route[3])) {
                            $controller->deleteUser((int) $route[3]);
                        }
                        break;

                    case 'messages':
                        $controller = new Controllers\ContactController();
                        if (!isset($route[2])) {
                            $controller->manageMessages();
                        } elseif ($route[2] === 'supprimer' && isset($route[3]) && ctype_digit($route[3])) {
                            $controller->deleteMessage((int) $route[3]);
                        }
                        break;

                    case 'newsletter':
                        $controller = new Controllers\NewsletterController();
                        if (!isset($route[2])) {
                            $controller->manageNewsletter();
                        } elseif ($route[2] === 'supprimer' && isset($route[3]) && ctype_digit($route[3])) {
                            $controller->deleteSubscriber((int) $route[3]);
                        }
                        break;

                    case 'outfits':
                        $controller = new Controllers\OutfitsController();
                        if (!isset($route[2])) {
                            $controller->manageOutfits();
                        } elseif ($route[2] === 'ajouter') {
                            $controller->addOutfit();
                        } elseif ($route[2] === 'modifier' && isset($route[3]) && ctype_digit($route[3])) {
                            $controller->updateOutfit((int) $route[3]);
                        } elseif ($route[2] === 'supprimer' && isset($route[3]) && ctype_digit($route[3])) {
                            $controller->deleteOutfit((int) $route[3]);
                        }
                        break;

                    default:
                        include('src/app/Views/404.php');
                        exit();
                }
                break;
        }
    } catch (Exception $e) {
        // Enregistrement de l'erreur dans les logs pour le suivi des erreurs
        error_log($e->getMessage());

        // Inclusion de la page 404 pour informer l'utilisateur
        include('src/app/Views/404.php');
        exit();
    }
}
