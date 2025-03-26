<!DOCTYPE html>
<html lang="en">
<?php include("src/app/Views/includes/head_shop.php"); ?>
<body id="top_shop_accueil" class="body_contact_shop">
<?php include("src/app/Views/includes/header_shop.php"); ?>
<h1 class="titre_principal_form_contact_shop">FORMULAIRE DE CONTACT</h1>

<div class="container_form_contact_shop">
    <h2 class="titre_form_contact_shop"> Contact - Magasin</h2>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1) : ?>
        <p class="text-green-600 text-center font-semibold">Votre message a bien été envoyé !</p>
    <?php endif; ?>

    <form action="contact_process" method="post" class="form_container_contact_shop" >
        <input type="hidden" name="source" value="shop">

        <label for="subject">Sujet :</label>
        <input type="text" name="subject" id="subject">

        <label for="name" >Nom :</label>
        <input type="text" name="name" id="name" >

        <label for="email" >Email :</label>
        <input type="email" name="email" id="email">

        <label for="message" >Message :</label>
        <textarea name="message" id="message" rows="5" required class="w-full p-2 border border-gray-300 rounded-md mb-4"></textarea>

        <button type="submit" >Envoyer</button>
    </form>
</div>

<?php include("src/app/Views/includes/footer_shop.php"); ?>
</body>
</html>