<?php
// Active le rapport de toutes les erreurs pour le débogage
error_reporting(E_ALL);

// Affiche les erreurs à l’écran (utile en développement, à désactiver en production)
ini_set('display_errors', '1');

// Démarre une session pour gérer les données utilisateur entre les pages
session_start();

// Charge l’autoloader de Composer pour inclure automatiquement les dépendances installées
require_once("./vendor/autoload.php");

// Initialise la bibliothèque Dotenv pour charger les variables d’environnement depuis un fichier .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
// Charge les variables d’environnement dans l’application
$dotenv->load();

// Inclut le fichier du routeur qui gère les requêtes et la navigation dans l’application
require("./routeur.php");
?>