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
