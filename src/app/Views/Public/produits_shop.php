<!DOCTYPE html>
<html lang="fr" class="html_shop">
<?php include('src/app/Views/includes/head_shop.php'); ?>

<body class="body_shop_produits" id="top_shop_accueil">

    <?php include('src/app/Views/includes/header_shop.php'); ?>
    <main class="product-grid">
        <br>
     

        <?php if (!empty($subCategories)) : ?>
            <div class="subcategories-container">
                <?php foreach ($subCategories as $sub) : ?>
                    <a href="produit_shop.php?gender=<?= htmlspecialchars($gender) ?>&id_categories=<?= htmlspecialchars($id_categories) ?>&id_ss_categories=<?= htmlspecialchars($sub['id_ss_categories']) ?>"
                        class="subcategory-button">
                        <?= htmlspecialchars($sub['name_ss_categories']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <p>❌ Aucune sous-catégorie trouvée.</p>
        <?php endif; ?> 
        
      

        <br>







        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                <?php
    $defaultImage = '/site_stage/chic-and-chill/assets/images/default_photo.jpg';
    $imageSrc = (!empty($product['image']) && file_exists($product['image'])) ? htmlspecialchars($product['image']) : $defaultImage;
?>
<img src="<?= $imageSrc ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        <div class="product-info">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p><?php echo htmlspecialchars($product['description']); ?></p>
                        <span class="product-price"><?php echo number_format($product['price'], 2, ',', ' '); ?> €</span><br>
                        <a href="produit_detail_shop?id=<?php echo $product['id']; ?>" class="product-link">Voir le produit</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun produit trouvé.</p>
        <?php endif; ?>
    </main>

    <?php include('src/app/Views/includes/footer_shop.php'); ?>
   
    <script>
        document.addEventListener('scroll', function() {
            const header = document.querySelector('.header_shop');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    </script>

</body>

</html>