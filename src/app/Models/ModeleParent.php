<?php

namespace Models;

class ModeleParent
{
    protected $pdo; // Propriété protégée pour la connexion PDO

    // Constructeur : Établit la connexion à la base de données avec les variables d'environnement
    public function __construct()
    {
        $dbhost = $_ENV["DB_HOST"];     // Hôte de la base de données
        $dbport = $_ENV["DB_PORT"];     // Port de la base de données
        $dbname = $_ENV["DB_NAME"];     // Nom de la base de données
        $dbuser = $_ENV["DB_USER"];     // Utilisateur de la base de données
        $dbpassword = $_ENV["DB_PASSWORD"]; // Mot de passe de la base de données

        try {
            $dsn = "mysql:host=$dbhost;port=$dbport;dbname=$dbname;"; // Chaîne de connexion DSN
            $this->pdo = new \PDO($dsn, $dbuser, $dbpassword); // Crée une nouvelle instance PDO
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); // Définit le mode d'erreur sur exception
            $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC); // Définit le mode de récupération par défaut
        } catch (\PDOException $e) {
            die("Erreur de connexion ou de requête : " . $e->getMessage()); // Affiche l'erreur et stoppe si échec
        }
    }
}
?>