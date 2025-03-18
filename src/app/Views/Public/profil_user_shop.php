<!DOCTYPE html>
<html lang="en">
<?php include('src/app/Views/includes/head_shop.php'); ?>
<body class="body_profil_shop" id="top_shop_accueil">
<?php include('src/app/Views/includes/header_shop.php'); ?>

<div class="profil_container">

    <!-- Sidebar avec liens -->
    <div class="profil_sidebar">
        <div class="profil_welcome">Bonjour, <?php echo htmlspecialchars($_SESSION['user_name']); ?> !</div>
        <div class="profil_nav">
            <a href="profil_infos_shop">Mes infos personnelles</a>
            <a href="modifier_mdp_shop">Modifier mon mot de passe</a>
            <a href="modifier_email_shop">Modifier mon adresse email</a>
            <a href="historique_commandes_shop">Historique des commandes</a>
            <a href="deconnexion_shop" class="logout">Déconnexion</a>
        </div>
    </div>

    <!-- Contenu principal -->
     <section class="container_histo_nouveautes">
    <div class="profil_main" id="section_historique">
    <h2>Historique des commandes</h2>

    <?php if (!empty($userOrders)): ?>
        <div class="order-list">
            <?php foreach ($userOrders as $order): ?>
                <div class="order-item">
                    <p><strong>Commande #<?php echo $order['id']; ?></strong></p>
                    <p>Date : <?php echo date("d/m/Y", strtotime($order['date'])); ?></p>
                    <p>Total : <?php echo number_format($order['total'], 2, ',', ' ') . "€"; ?></p>
                    <p>Statut : <span class="status-<?php echo strtolower($order['status']); ?>">
                        <?php echo ucfirst($order['status']); ?>
                    </span></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Vous n'avez pas encore passé de commande.</p>
    <?php endif; ?>
</div>


        <!-- Section Nouveautés -->
        <div class="nouveautes_section" id="nouveaute_section">
            <h3>✨ Ne loupez pas nos nouveautés !</h3>
            <a href="produit_shop?categorie=nouveautes">Voir les nouveautés</a>
        </div>

    </div>
    </section>

    <section id="info_perso">
     <!-- Section Infos Personnelles (Dynamique) -->
     <div id="infos_perso_section" style="display: none;">
        <h2>Mes informations personnelles</h2>
        <form action="user_infos_shop" method="POST" id="infos_perso_form">
            <label>Nom :</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" readonly>
            
            <label>Prénom :</label>
            <input type="text" name="surname" value="<?php echo htmlspecialchars($user['surname']); ?>" readonly>

            <label>Email :</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>

            <label>Adresse :</label>
            <input type="text" name="adresse" value="<?php echo htmlspecialchars($user['adresse']); ?>" readonly>

            <label>Téléphone :</label>
            <input type="text" name="number_phone" value="<?php echo htmlspecialchars($user['number_phone']); ?>" readonly>

            <button type="button" id="modifier_infos">Modifier</button>
            <button type="submit" id="valider_modifications" style="display: none;">Valider les modifications</button>
        </form>
    </div>
</div>
</section>
<?php include('src/app/Views/includes/footer_shop.php'); ?>
<script src="src/app/js/info_perso.js"></script>

</body>
</html>