<!DOCTYPE html>
<html lang="en">
<?php include('src/app/Views/includes/head_shop.php'); ?>
<body class="body_co_shop">

<?php include('src/app/Views/includes/header_shop.php'); ?>

<div class="container_form_shop">
    <?php

    if (isset($_SESSION['error'])) {
        echo '<div class="alert_shop">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
    ?>
    <div class="login_container_shop">
        <div class="login_box_shop">
            <h2>Connexion</h2>
            <form action="/site_stage/chic-and-chill/connexion_shop" method="POST">
                <div class="form_id_shop">
                    <label for="pseudo">Identifiant</label>
                    <input type="text" id="pseudo_co_shop" name="pseudo" required>
                </div>
                <div class="form_mdp_shop">
                    <label for="mot_de_passe">Mot de Passe</label>
                    <input type="password" id="mot_de_passe" name="mot_de_passe" required>
                </div>
                <button type="submit">Connexion</button>
            </form>
            <script>
document.querySelector("form").addEventListener("submit", function(event) {
    event.preventDefault(); // Empêche l’envoi automatique
    console.log("Données envoyées :", new FormData(this));

    // Envoie manuellement
    this.submit();
});
</script>
            <div class="inscription_btn_shop">
                <p>Pas encore de compte ?</p>
                <a href="inscription_shop">Inscrivez-vous</a>
            </div>
        </div>
    </div>
</div>

<?php include('src/app/Views/includes/footer_shop.php'); ?>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<?php include('src/app/Views/includes/head_shop.php'); ?>
<body class="body_co_shop">

<?php include('src/app/Views/includes/header_shop.php'); ?>

<div class="container_form_shop">
    <?php

    if (isset($_SESSION['error'])) {
        echo '<div class="alert_shop">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
    ?>
    <div class="login_container_shop">
        <div class="login_box_shop">
            <h2>Connexion</h2>
            <form action="/site_stage/chic-and-chill/connexion_shop" method="POST">
                <div class="form_id_shop">
                    <label for="pseudo">Identifiant</label>
                    <input type="text" id="pseudo_co_shop" name="pseudo" required>
                </div>
                <div class="form_mdp_shop">
                    <label for="mot_de_passe">Mot de Passe</label>
                    <input type="password" id="mot_de_passe" name="mot_de_passe" required>
                </div>
                <button type="submit">Connexion</button>
            </form>
            <script>
document.querySelector("form").addEventListener("submit", function(event) {
    event.preventDefault(); // Empêche l’envoi automatique
    console.log("Données envoyées :", new FormData(this));

    // Envoie manuellement
    this.submit();
});
</script>
            <div class="inscription_btn_shop">
                <p>Pas encore de compte ?</p>
                <a href="/projet2/back-office/views/inscription.php">Inscrivez-vous</a>
            </div>
        </div>
    </div>
</div>

<?php include('src/app/Views/includes/footer_shop.php'); ?>

</body>
</html>
