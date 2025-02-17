<footer class="footer_shop">
    <!-- Bouton  top pour aller en haut -->
  <div class="back-to-top-footer-shop">
    <!-- il y a cet id dans le header de mon fichier header_shop  -->
    <a href="#top_shop_accueil"><i class="fas fa-arrow-up"></i></a>
  </div>


  <!-- bande avec les icons de reseaux sociaux -->
   <!-- mettre a la place du # les url des sites des reseaux sociaux  ex: <a href="https://www.instagram.com/chicandchillshop" target="_blank" rel="noopener noreferrer" -->
  <div class="footer_shop_top">
    <p>Suivez-nous sur les réseaux sociaux, pour ne rien rater des nouveautés !</p>
    <div class="social-icons_shop_footer">
      <a href="#"><i class="fab fa-tiktok"></i></a>   
      <a href="#"><i class="fab fa-instagram"></i></a>
      <a href="#"><i class="fab fa-facebook"></i></a>
    </div>
  </div>

  <!-- footer ac les colonnes pr le footer -->
  <div class="footer_shop_bottom">
    <!-- Colonne 1 -->
    <div class="footer_shop_column">
      <h4>Les modes et frais de livraison</h4>
      <a href="#">Les moyens de paiement</a>
      <a href="#">Paiement sécurisé</a>
    </div>

    <!-- Colonne 2 -->
    <div class="footer_shop_column">
      <h4>Contactez-nous</h4>
      <a href="#">Contact revendeur</a>
      <a href="#">Contact presse</a>
      <a href="#">Contact clients</a>
      <a href="#">Contact publicité</a>
    </div>

    <!-- Colonne 3 -->
    <div class="footer_shop_column">
      <h4>Informations légales</h4>
      <a href="#">Conditions générales de ventes</a>
      <a href="#">Protection de la vie privée et des cookies</a>
      <a href="#">Mentions légales</a>
      <a href="#">Rappel de produits</a>
    </div>
  </div>
  <script>
  document.querySelector('.back-to-top-footer-shop a').addEventListener('click', function(e) {
    e.preventDefault();
    const target = document.getElementById('top_shop_accueil');
    if (target) {
      target.scrollIntoView({ behavior: 'smooth' });
    }
  });
</script>




</footer>
