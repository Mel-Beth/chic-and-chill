<!DOCTYPE html>
<html lang="fr">
<?php include('src/app/Views/includes/head_shop.php'); ?>

<body class="body_profil_shop" id="top_shop_accueil">

<?php include('src/app/Views/includes/header_shop.php'); ?>

<div class="container_profil_shop">
    <div class="profil_card_shop">
        <h2 class="profil_titre_shop">Bonjour, <?php echo htmlspecialchars($_SESSION['user_name']) . " " . htmlspecialchars($_SESSION['user_surname']); ?> !</h2>

        <!-- Menu de profil -->
        <ul class="profil_menu_shop">
            <li><strong>Identifiant :</strong> <?php echo htmlspecialchars($_SESSION['user_identifiant']); ?></li>
            <li><strong>Email :</strong> <?php echo htmlspecialchars($_SESSION['user_email']); ?></li>
            <li><strong>Adresse :</strong> <?php echo htmlspecialchars($_SESSION['user_adresse']); ?></li>
            <li><strong>Téléphone :</strong> <?php echo htmlspecialchars($_SESSION['user_number_phone']); ?></li>
        </ul>

        <div class="profil_liens_shop">
            <a href="profil_infos_shop" class="profil_btn_shop">Modifier mes informations</a>
            <a href="historique_commandes_shop" class="profil_btn_shop">Historique des commandes</a>
            <a href="modifier_mdp_shop" class="profil_btn_shop">Modifier mon mot de passe</a>
            <a href="modifier_email_shop" class="profil_btn_shop">Modifier mon adresse email</a>
        </div>

        <!-- Bouton de déconnexion -->
        <a href="deconnexion_shop" class="btn_deconnexion_shop">Déconnexion</a>
    </div>
</div>

<?php include('src/app/Views/includes/footer_shop.php'); ?>

</body>
</html>
