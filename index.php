<?php
error_reporting( E_ALL );

error_reporting(E_ALL);
ini_set('display_errors', '1');

// On démarre la session
session_start();

// On appelle l'autoloader de Composer
require_once("./vendor/autoload.php");


// Appel de la bibliothèque Dotenv
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Appel du routeur
require("./routeur.php");
?>