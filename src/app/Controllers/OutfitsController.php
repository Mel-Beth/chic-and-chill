<?php

namespace Controllers;

use Models\OutfitsModel;

class OutfitsController
{
    public function manageOutfits()
    {
        $outfitsModel = new OutfitsModel();
        $outfits = $outfitsModel->getAllOutfits();
        include('src/app/Views/Admin/admin_outfits.php');
    }

    public function addOutfit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = htmlspecialchars($_POST['title']);
            $description = htmlspecialchars($_POST['description']);
            $image = $_POST['image'] ?? null;
            $products = isset($_POST['products']) ? implode(",", $_POST['products']) : '';

            $outfitsModel = new OutfitsModel();
            $success = $outfitsModel->createOutfit($title, $description, $image, $products);

            header("Location: admin/outfits?success=" . ($success ? 1 : 0));
            exit();
        }
    }

    public function updateOutfit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = htmlspecialchars($_POST['title']);
            $description = htmlspecialchars($_POST['description']);
            $image = $_POST['image'] ?? null;
            $products = isset($_POST['products']) ? implode(",", $_POST['products']) : '';

            $outfitsModel = new OutfitsModel();
            $success = $outfitsModel->updateOutfit($id, $title, $description, $image, $products);

            header("Location: admin/outfits?success=" . ($success ? 1 : 0));
            exit();
        }
    }

    public function deleteOutfit($id)
    {
        $outfitsModel = new OutfitsModel();
        $outfitsModel->deleteOutfit($id);
        header("Location: admin/outfits");
        exit();
    }
}
