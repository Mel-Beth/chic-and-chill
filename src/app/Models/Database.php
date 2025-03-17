<?php
namespace Models;

use PDO;
use PDOException;

class Database {
    protected $pdo; // On s'en tient à $pdo

    public function __construct() {
        $host = '127.0.0.1';
        $dbname = 'chicandchill';
        $username = 'root';
        $password = '';

        try {
            // On remplace $this->conn par $this->pdo
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->pdo; 
    }
}
