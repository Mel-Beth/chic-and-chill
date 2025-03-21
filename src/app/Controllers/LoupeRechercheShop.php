<?php

namespace Controllers;
require_once 'services/database.php'; // Connexion à la BDD

header('Content-Type: application/json');

if (isset($_GET['q'])) {
    $query = trim($_GET['q']);
    $stmt = $pdo->prepare("SELECT id, name FROM products WHERE name LIKE :query LIMIT 5");
    $stmt->execute(['query' => "%$query%"]);
    echo json_encode($stmt->fetchAll(\PDO::FETCH_ASSOC));
}
