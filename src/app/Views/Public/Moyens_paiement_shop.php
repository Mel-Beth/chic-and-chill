<!DOCTYPE html>
<html lang="fr">
<?php include('src/app/Views/includes/head_shop.php'); ?>
<body class="moyen_paiement_shop" id="top_shop_accueil">

<?php include('src/app/Views/includes/header_shop.php'); ?>

<main class="payment-container">

  <h1 class="payment-title">Moyens de paiement</h1>

  <p class="payment-intro">
    Chez <strong>Chic and Chill</strong>, nous vous proposons plusieurs options de paiement sécurisées pour finaliser vos achats en toute tranquillité.
  </p>

  <section class="payment-method">
    <h2 class="payment-subtitle">💳 Paiement par carte bancaire</h2>
    <p>Nous acceptons les principales cartes bancaires :</p>
    <div class="payment-icons">
        <img src="assets/img/visa.png" alt="Visa">
        <img src="assets/img/mastercard.png" alt="Mastercard">
        <img src="assets/img/amex.png" alt="American Express">
    </div>
    <p>
      Le paiement est entièrement sécurisé grâce à un système de chiffrement avancé, garantissant la protection de vos données bancaires tout au long de la transaction.
    </p>
  </section>

  <section class="payment-method">
    <h2 class="payment-subtitle">🛡 Paiement via PayPal</h2>
    <p>
      Vous pouvez également choisir <strong>PayPal</strong> comme moyen de paiement. En optant pour cette solution, vous serez redirigé vers la plateforme PayPal pour un règlement simple et sécurisé, sans avoir à saisir vos informations bancaires sur notre site.
    </p>
  </section>

  <section class="payment-security">
    <h2 class="payment-subtitle">🔒 Sécurité et confidentialité</h2>
    <p>
      Toutes les transactions sont protégées par des protocoles de sécurité stricts afin de prévenir tout risque de fraude. Aucune information bancaire n'est stockée sur notre site.
    </p>
  </section>

  <p class="payment-contact">
    Si vous avez des questions sur nos moyens de paiement, notre <a href="contact_shop">service client</a> est à votre disposition pour vous accompagner.
  </p>

</main>

<?php include('src/app/Views/includes/footer_shop.php'); ?>

</body>
</html>
