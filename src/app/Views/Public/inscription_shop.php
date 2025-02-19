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
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" required>
                </div>

                <div class="form_group_shop">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" required>
                </div>

                <div class="form_group_shop">
                    <label for="adresse">Adresse</label>
                    <input type="text" id="adresse" name="adresse" required>
                </div>

                <div class="form_group_shop">
                    <label for="numero_tel">Numéro de Téléphone</label>
                    <input type="text" id="numero_tel" name="numero_tel" required>
                </div>

                <div class="form_group_shop">
                    <label for="date_naissance">Date de Naissance</label>
                    <input type="date" id="date_naissance" name="date_naissance" required>
                </div>

                <div class="form_group_shop">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form_group_shop">
                    <label for="pseudo">Pseudo</label>
                    <input type="text" id="pseudo" name="pseudo" required>
                </div>

                <div class="form_group_shop">
                    <label for="motdepasse">Mot de Passe</label>
                    <input type="password" id="motdepasse" name="motdepasse" required>
                </div>

                <div class="form_group_shop">
                    <label for="confirm_motdepasse">Confirmer le Mot de Passe</label>
                    <input type="password" id="confirm_motdepasse" name="confirm_motdepasse" required>
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
