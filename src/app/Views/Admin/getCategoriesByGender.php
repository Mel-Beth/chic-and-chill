<?php
require_once '../../config/init.php'; // ou ton autoloader selon ton projet

use Models\CrudModelShop;

header('Content-Type: application/json');

if (!isset($_GET['gender'])) {
    echo json_encode(['categories' => []]);
    exit;
}

$model = new CrudModelShop();
$categories = $model->getAllCategoriesByGender($_GET['gender']);

echo json_encode(['categories' => $categories]);
exit;