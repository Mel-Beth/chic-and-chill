<?php

namespace Controllers;

class DecoShopController
{
    public function logout()
    {
        session_start(); // Démarrer la session
        session_unset(); // Supprime toutes les variables de session
        session_destroy(); // Détruit la session
        
        // Redirection vers la page d'accueil
        header("Location: accueil_shop");
        exit;
    }
}
?>
