<?php

namespace Controllers;

use Models\PacksModel;

class PackController
{
    public function showPack($id)
    {
        try {
            $packsModel = new PacksModel();
            $pack = $packsModel->getPackById($id);

            if (!$pack) {
                include('src/app/Views/404.php');
                exit();
            }

            include('src/app/Views/Public/pack_detail.php');
        } catch (\Exception $e) {
            error_log($e->getMessage());
            echo "Une erreur est survenue. Veuillez réessayer plus tard.";
        }
    }

    public function managePacks()
    {
        $packsModel = new PacksModel();
        $packs = $packsModel->getAllPacksAdmin();
        include('src/app/Views/Admin/admin_packs.php');
    }

    public function addPack()
    {
        include('src/app/Views/Admin/admin_packs.php');
    }

    public function updatePack($id)
    {
        include('src/app/Views/Admin/admin_packs.php');
    }

    public function deletePack($id)
    {
        $packsModel = new PacksModel();
        $packsModel->deletePack($id);
        header("Location: admin/packs");
        exit();
    }
}
