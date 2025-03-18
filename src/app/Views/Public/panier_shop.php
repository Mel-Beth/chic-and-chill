<!DOCTYPE html>
<html lang="fr">
<?php include('src/app/Views/includes/head_shop.php'); ?>

<body class="body_panier_shop">
    <?php include('src/app/Views/includes/header_shop.php'); ?>
    <!-- Conteneur principal du panier et du paiement -->
    <div class="wrapper_panier_paiement">

        <!-- colonne 2 gauche : Panier -->
        <div class="container_panier_shop">
            <h2>🛒 Votre Panier</h2>

            <?php
            if (isset($_SESSION['error'])) {
                echo '<div class="alert_shop error">' . htmlspecialchars($_SESSION['error']) . '</div>';
                unset($_SESSION['error']);
            }

            if (isset($_SESSION['message'])) {
                echo '<div class="alert_shop success">' . htmlspecialchars($_SESSION['message']) . '</div>';
                unset($_SESSION['message']);
            }

            if (empty($_SESSION['panier'])) {
                echo "<p>Votre panier est vide.</p>";
            } else {
                echo '<table class="table_panier">';
                echo '<thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix</th>
                        <th>Quantité</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                  </thead>';
                echo '<tbody>';

                $total = 0;
                foreach ($_SESSION['panier'] as $id => $produit) {
                    $prixFinal = ($produit['discount_price'] > 0) ? $produit['discount_price'] : $produit['price'];
                    $totalProduit = $prixFinal * $produit['quantite'];
                    $total += $totalProduit;
            ?>
                    <tr>
                        <td><?php echo htmlspecialchars($produit['name']); ?></td>
                        <td><?php echo number_format($prixFinal, 2, ',', ' ') . "€"; ?></td>
                        <td>
                            <form action="modifier_panier" method="POST">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                                <input type="number" name="quantite" value="<?php echo htmlspecialchars($produit['quantite']); ?>" min="1">
                                <button type="submit" class="btn_modif_shop">📝 Modifier</button>
                            </form>
                        </td>
                        <td><?php echo number_format($totalProduit, 2, ',', ' ') . "€"; ?></td>
                        <td>
                            <form action="supprimer_panier" method="POST">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                                <button type="submit" class="btn-supprimer">Supprimer</button>
                            </form>
                        </td>
                    </tr>
            <?php
                }

                echo "</tbody>";
                echo "</table>";

                echo "<p class='total_panier'>Total : <strong>" . number_format($total, 2, ',', ' ') . "€</strong></p>";
            }
            ?>

            <div class="actions_panier">
                <a href="produit_shop" class="btn">🛍 Continuer mes achats</a>
                <?php if (!empty($_SESSION['panier'])): ?>
                    <form action="vider_panier" method="POST">
                        <button type="submit" class="btn-danger">🗑 Vider le panier</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <!-- 💳 Colonne droite : Paiement -->
        <?php if (!empty($_SESSION['panier'])): ?>
            <div class="container_paiement">

                
<form id="paiement-form" action="paiement_cb_shop" method="POST">
                    <input type="hidden" name="total" value="<?= isset($_SESSION['panier_total']) ? $_SESSION['panier_total'] : '0' ?>">

                    <h2>Moyens de Paiement</h2>

                    <label>
                        <input type="radio" name="payment_method" value="card" checked> Carte bancaire (via Stripe)
                    </label>

                    <button id="checkout-button" type="submit">Valider et Payer</button>
                </form>



                </form>
            </div>
        <?php endif; ?>
    </div>


    <?php include('src/app/Views/includes/footer_shop.php'); ?>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="src/app/js/paiement_cb_shop.js"></script>

</body>

</html>