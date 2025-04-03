<?php
require_once '../../config/init.php';

use Models\CrudModelShop;

header('Content-Type: application/json');

if (!isset($_GET['id_categories'])) {
    echo json_encode(['subCategories' => []]);
    exit;
}

$id_categories = (int) $_GET['id_categories'];

$stmt = (new CrudModelShop())->pdo->prepare("
    SELECT id_ss_categories, name_ss_categories 
    FROM ss_categories 
    WHERE id_categories = :id_categories
    ORDER BY name_ss_categories
");
$stmt->execute(['id_categories' => $id_categories]);
$subCategories = $stmt->fetchAll(\PDO::FETCH_ASSOC);

echo json_encode(['subCategories' => $subCategories]);
exit;