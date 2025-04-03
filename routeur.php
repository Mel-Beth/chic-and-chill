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
    // Gestion des différentes routes possibles
    switch ($route[0]) {
        case 'accueil': // Si l'utilisateur accède à "/accueil"
            $controller = new Controllers\HomeController();
            $controller->index();
            break;

        case 'evenements': // Si l'utilisateur accède à "/evenements"
            $controller = new Controllers\Events\EventsController();
            if (!empty($route[1]) && is_numeric($route[1])) {
                $controller->showEvent($route[1]);
            } else {
                $controller->index();
            }
            break;

        // Page d'accueil Location & Showroom
        case 'accueil_loc_show':
            include 'src/app/Views/Public/accueil_loc_show.php';
            break;

        case 'showroom': // Pour les réservations de showroom (ex. /showroom)
            $controller = new Controllers\ShowroomController();
            $controller->index(); // Ou une méthode spécifique comme showroomReservation()
            break;

        // Route Location
        case 'location':
            $controller = new Controllers\LocationController();
            $controller->index();
            break;

        // Route pour afficher le panier
        case 'panier_loc':
            $controller = new Controllers\LocationController();
            $controller->panier();

            break;

        // Route pour gérer la réservation (enregistrement en base)
        case 'reserve':
            $controller = new Controllers\LocationController();
            $routeur->addRoute('/reserve', 'LocationController@reserve');
            $controller->reserve();
            break;

        case 'evenement_detail':
            $controller = new Controllers\Events\EventsController();
            if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
                $controller->showEvent($_GET['id']);
            } else {
                $code_erreur = 404;
                $description_erreur = "Oups... La page que vous cherchez n'existe pas.";
                include('src/app/Views/erreur.php');
            }
            break;

        case 'pack_detail':
            $controller = new Controllers\Events\PacksController();
            if (!empty($route[1]) && is_numeric($route[1])) {
                $controller->showPack($route[1]); // On passe l'ID du pack
            } else {
                $code_erreur = 404;
                $description_erreur = "Oups... La page que vous cherchez n'existe pas.";
                include('src/app/Views/erreur.php');
            }
            break;

        case 'reservation_evenement':
            $controller = new Controllers\Events\ReservationController();
            $controller->reservationEvenement();
            break;

        case 'reservation_pack':
            $controller = new Controllers\Events\ReservationController();
            if (!empty($_GET['pack_id']) && is_numeric($_GET['pack_id'])) {
                $controller->reservationPack($_GET['pack_id']);
            } else {
                $code_erreur = 404;
                $description_erreur = "Oups... La page que vous cherchez n'existe pas.";
                include('src/app/Views/erreur.php');
            }
            break;

        case 'reservation_process':
            $controller = new Controllers\Events\ReservationController();
            $controller->processReservation();
            break;

        case 'confirmation_reservation':
            $controller = new Controllers\Events\EventsController();
            include('src/app/Views/Public/events/confirmation_reservation.php');
            if (!empty($route[1]) && is_numeric($route[1])) {
                $controller->showEvent($route[1]);
            }
            break;

        case 'contact_process':
            $controller = new Controllers\ContactController();
            $controller->processContactForm();
            break;

        case 'contact_location':
            include('src/app/Views/Public/contact_location.php');
            break;

        case 'contact_evenements':
            include('src/app/Views/Public/events/contact_evenements.php');
            break;

        case 'newsletter':
            $controller = new Controllers\NewsletterController();
            if (!isset($route[1])) {
                $controller->processNewsletter();
            } elseif ($route[1] === 'unsubscribe') {
                $controller->unsubscribe();
            } elseif ($route[1] === 'send-monthly' && $_SESSION['user_role'] === 'admin') { // Sécurisé pour admin seulement
                $controller->sendMonthlyNewsletter();
                echo "Newsletter mensuelle envoyée.";
            } elseif ($route[1] === 'supprimer' && isset($route[2]) && ctype_digit($route[2])) {
                $controller->deleteSubscriber((int)$route[2]);
            }
            break;

        case 'localisation':
            include('src/app/Views/Public/events/localisation.php');
            break;

        case 'conditions_generales':
            include('src/app/Views/Public/conditions_generales_vente_shop.php');
            break;

        case 'moyens_paiement':
            include('src/app/Views/Public/Moyens_paiement_shop.php');
            break;

        case 'contact_shop':
            include('src/app/Views/Public/contact_shop.php');
            break;

        case 'mentions_legales':
            include('src/app/Views/Public/mentions_legales_shop.php');
            break;

        case 'accueil_shop':
            include('src/app/Views/Public/accueil_shop.php');
            break;

        // Afficher la page de produits dans le shop
        case 'produit_shop':
            $controller = new Controllers\ArticleControllerShop();
            $controller->showProducts();
            break;

        case 'produit_detail_shop':
            $controller = new Controllers\ProduitDetailControllerShop();
            $controller->afficherDetailProduit();
            break;

        case 'connexion_shop':
            if (isset($_SESSION['user_id'])) {
                header("Location: /site_stage/chic-and-chill/accueil_shop");
                exit;
            }
            $controller = new Controllers\ConnexionControllersShop();
            $controller->loginUserShop();
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                include 'src/app/Views/Public/connexion_shop.php';
            }
            break;

        case 'profil_user_shop':
            $controller = new Controllers\ProfilControllersShop();
            $controller->showUserInfos();
            break;

        case 'update_email_shop':
            $controller = new Controllers\ConnexionControllersShop();
            $controller->updateEmail();
            break;

        case 'inscription_shop':
            if (isset($_SESSION['user_id'])) {
                header("Location: /site_stage/chic-and-chill/accueil_shop");
                exit;
            }
            $controller = new Controllers\InscriptionControllersShop();
            $controller->registerUserShop();
            break;

        case 'profil_infos_shop':
            $controller = new Controllers\ProfilControllersShop();
            $controller->showUserInfos();
            break;

        case 'deconnexion_shop':
            $controller = new Controllers\DecoShopController();
            $controller->logout();
            break;

        case 'panier_shop':
            $controller = new Controllers\PanierControllerShop();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->ajouterAuPanier();
            } else {
                $controller->afficherPanier();
            }
            break;

        case 'modifier_panier':
            $controller = new Controllers\PanierControllerShop();
            $controller->modifierQuantite();
            break;

        case 'supprimer_panier':
            $controller = new Controllers\PanierControllerShop();
            $controller->supprimerProduit();
            header("Location: panier_shop");
            exit;
            break;

        case 'vider_panier':
            $controller = new Controllers\PanierControllerShop();
            $controller->viderPanier();
            break;

        case 'paiement_cb_shop':
            $controller = new Controllers\PaiementCbControllerShop();
            $controller->processPaiement();
            break;

        case 'paiement_succes':
            $controller = new Controllers\PaiementCbControllerShop();
            $controller->paiementSucces();
            break;

        case 'paiement_annule':
            include 'src/app/Views/Public/paiement_annule_shop.php';
            break;

        case 'forgot-password':
            $controller = new Controllers\AuthController();
            $controller->forgotPassword();
            break;

        case 'reset-password':
            $controller = new Controllers\AuthController();
            $controller->resetPassword();
            break;

        case 'verify-email':
            $controller = new Controllers\AuthController();
            $controller->verifyEmail();
            break;

        // 📌 Routes Admin
        case 'admin':
            if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== "admin") {
                header('Location: connexion_shop');
                exit();
            }

            // Gestion des sous-routes Admin
            switch ($route[1]) {
                case '':
                case 'dashboard':
                    $controller = new Controllers\DashboardController();
                    if (isset($route[2]) && $route[2] === 'stats') {
                        $controller->stats();
                    } else {
                        $controller->index();
                    }
                    break;

                // ✅ Routes Admin Location
                case 'location':
                    $controller = new Controllers\LocationAdminController();
                    $controller->index();
                    break;

                // ✅ Routes Admin Showroom
                case 'showroom':
                    $controller = new Controllers\ShowroomAdminController();
                    $controller->index();
                    break;

                // AJOUT ICI : Catégories AJAX
                case 'getCategoriesByGender':
                    $controller = new Controllers\AdminCrudShop();
                    $controller->getCategoriesByGender();
                    exit;
                    break;

                //  AJOUT ICI : Sous-catégories AJAX
                case 'getSubCategories':
                    $controller = new Controllers\AdminCrudShop();
                    $controller->getSubCategories();
                    exit;
                    break;

                case 'notifications':
                    $controller = new Controllers\NotificationController();
                    if (!isset($route[2])) {
                        $controller->getUnreadNotifications();
                    } elseif ($route[2] === 'unread') {
                        $controller->getUnreadNotifications();
                    } elseif ($route[2] === 'read' && isset($route[3]) && ctype_digit($route[3])) {
                        $controller->markAsRead((int) $route[3]);
                    }
                    break;

                // Ajout de la section "Magasin" avec onglet et crud
                case 'crudShop':
                    $controller = new Controllers\AdminCrudShop();
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        if (!empty($_POST['delete'])) {
                            $controller->deleteArticle((int) $_POST['delete']);
                        } elseif (!empty($_POST['action']) && $_POST['action'] === 'update') {
                            $controller->updateArticle();
                        } elseif (!empty($_POST['action']) && $_POST['action'] === 'add') {
                            $controller->addArticle();
                        }
                    } else {
                        $controller->index();
                    }
                    break;

                case 'export':
                    $controller = new Controllers\ExportController();
                    $controller->exportData($_GET['type'] ?? 'reservations');
                    break;

                case 'evenements':
                    $controller = new Controllers\Events\EventsController();
                    if (!isset($route[2])) {
                        $controller->manageEvents();
                    } elseif ($route[2] === 'ajouter') {
                        $controller->addEvent();
                    } elseif ($route[2] === 'modifier' && isset($route[3]) && ctype_digit($route[3])) {
                        $controller->updateEvent((int) $route[3]);
                    } elseif ($route[2] === 'supprimer' && isset($route[3]) && ctype_digit($route[3])) {
                        $controller->deleteEvent((int) $route[3]);
                    } elseif ($route[2] === 'configurer' && isset($route[3]) && ctype_digit($route[3])) {
                        if (isset($route[4]) && $route[4] === 'media' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                            $controller->configureEvent((int) $route[3]);
                        } elseif (isset($route[4]) && $route[4] === 'supprimer_media' && isset($route[5]) && ctype_digit($route[5])) {
                            $controller->deleteEventMedia((int) $route[5]);
                        } else {
                            $controller->configureEvent((int) $route[3]);
                        }
                    }
                    break;

                case 'packs':
                    $controller = new Controllers\Events\PacksController();
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
                    $controller = new Controllers\Events\ReservationController();
                    if (!isset($route[2])) {
                        $controller->reservations();
                    } elseif ($route[2] === 'detail' && isset($route[3]) && ctype_digit($route[3])) {
                        $controller->showReservation((int) $route[3]);
                    } elseif ($route[2] === 'facture' && isset($route[3]) && ctype_digit($route[3])) {
                        $controller->showInvoice((int) $route[3]);
                    } elseif ($route[2] === 'annuler' && isset($route[3]) && ctype_digit($route[3])) {
                        $controller->cancelReservation((int) $route[3]);
                    } elseif ($route[2] === 'modifier' && isset($route[3]) && ctype_digit($route[3]) && isset($_GET['status'])) {
                        $controller->updateReservationStatus((int) $route[3], $_GET['status']);
                    } elseif ($route[2] === 'confirmation') {
                        $controller->showConfirmation();
                    } else {
                        $code_erreur = 404;
                        $description_erreur = "Oups... La page que vous cherchez n'existe pas.";
                        include('src/app/Views/erreur.php');
                    }
                    break;

                case 'users':
                    $controller = new Controllers\UsersController();
                    if (!isset($route[2])) {
                        $controller->users();
                    } elseif ($route[2] === 'historique' && isset($route[3]) && ctype_digit($route[3])) {
                        $controller->viewUserHistory((int) $route[3]);
                    } elseif ($route[2] === 'modifier_status' && isset($route[3]) && ctype_digit($route[3]) && isset($_GET['status'])) {
                        $controller->updateUserStatus((int) $route[3], $_GET['status']);
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
                    } elseif ($route[2] === 'unread_count') {
                        $controller->unreadCount();
                    } elseif ($route[2] === 'mark_as_read' && isset($route[3]) && ctype_digit($route[3])) {
                        $controller->markAsRead((int) $route[3]);
                    } elseif ($route[2] === 'update_status' && isset($route[3]) && ctype_digit($route[3])) {
                        $controller->updateMessageStatus((int) $route[3]);
                    } elseif ($route[2] === 'reply' && isset($route[3]) && ctype_digit($route[3])) {
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $controller->replyToMessage((int) $route[3]);
                        } else {
                            $controller->showReplyForm((int) $route[3]);
                        }
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
                    $controller = new Controllers\Events\OutfitsController();
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

                case 'settings':
                    $controller = new Controllers\SettingsController();
                    if (!isset($route[2])) {
                        $controller->showSettings();
                    } elseif ($route[2] === 'update') {
                        $controller->updateSettings();
                    } elseif ($route[2] === 'update-password') {
                        $controller->updatePassword();
                    } elseif ($route[2] === 'delete-account') {
                        $controller->deleteAccount();
                    } elseif ($route[2] === 'update-notifications') {
                        $controller->updateNotifications();
                    } elseif ($route[2] === 'history') {
                        $controller->getActionHistory();
                    } elseif ($route[2] === 'export_users') {
                        $controller->exportUsersCSV();
                    } elseif ($route[2] === 'export_products') {
                        $controller->exportProductsCSV();
                    } elseif ($route[2] === 'import') {
                        $controller->importCSV();
                    } elseif ($route[2] === 'backup') {
                        $controller->backupDatabase();
                    } elseif ($route[2] === 'restore') {
                        $controller->restoreDatabase();
                    }
                    break;

                case 'logout':
                    $controller = new Controllers\AuthController();
                    $controller->logout();
                    break;

                default:
                    $code_erreur = 404;
                    $description_erreur = "Oups... La page que vous cherchez n'existe pas.";
                    include('src/app/Views/erreur.php');
                    exit();
            }
            break;

        default:
            $code_erreur = 404;
            $description_erreur = "Oups... La page que vous cherchez n'existe pas.";
            include('src/app/Views/erreur.php');
            exit();
            break;
    }
}
