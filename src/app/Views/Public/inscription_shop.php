<!DOCTYPE html>
<html lang="fr">
<?php include('src/app/Views/includes/head_shop.php'); ?>

<body>
    <?php include('src/app/Views/includes/header_shop.php'); ?>

    <div class="container_form_shop">
        <div class="login_container_shop">
            <h2 class="text-center">Inscription</h2>

            <!-- Formulaire -->
            <form action="/site_stage/chic-and-chill/inscription_shop" method="POST">
                <div class="form_group_shop">
                    <label for="name">Nom complet</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form_group_shop">
                    <label for="surname">Prénom</label>
                    <input type="text" id="surname" name="surname" required>
                </div>

                <div class="form_group_shop">
                    <label for="adresse">Adresse</label>
                    <input type="text" id="adresse" name="adresse" required>
                </div>

                <div class="form_group_shop">
                    <label for="number_phone">Numéro de Téléphone</label>
                    <input type="text" id="number_phone" name="number_phone" required>
                </div>

                <div class="form_group_shop">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form_group_shop">
                    <label for="password">Mot de Passe</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form_group_shop">
                    <label for="confirm_password">Confirmer le Mot de Passe</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>

                <button type="submit" class="btn_shop">S'inscrire</button>
            </form>

            <div class="inscription_btn_shop">
                <p>Déjà un compte ?</p>
                <a href="connexion_shop">Connectez-vous</a>
            </div>
        </div>
    </div>

    <?php include('src/app/Views/includes/footer_shop.php'); ?>
</body>
</html>
