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
}
?>
