<!DOCTYPE html>
<html lang="fr">
<?php include('src/app/Views/includes/head_loc.php'); ?>

<body class="body_co_shop" id="top_shop_accueil">

<?php include('src/app/Views/includes/header_loc.php'); ?>

<div class="container_form_co_shop">
    <?php
    if (isset($_SESSION['error'])) {
        echo '<div class="alert_shop">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
    ?>
    <div class="login_container_shop">
        <div class="login_box_shop">
            <h2>Connexion</h2>
            <form action="connexion_showroom" method="POST">
                <!-- Champ Identifiant ou Email -->
                <div class="form_id_shop">
                    <label for="identifier">Identifiant ou Email</label>
                    <input type="text" id="identifier" name="identifier" required>
                </div>

                <!-- Champ Mot de Passe -->
                <div class="form_mdp_shop">
                    <label for="password">Mot de Passe</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <!-- Bouton de Connexion -->
                <button type="submit" class="btn_submit_shop">Connexion</button>
            </form>

            <div class="inscription_btn_shop">
                <p>Pas encore de compte ?</p>
                <a href="inscription_shop">Inscrivez-vous</a>
            </div>
        </div>
    </div>
</div>

<?php include('src/app/Views/includes/footer_loc.php'); ?>

</body>
</html>