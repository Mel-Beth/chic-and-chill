<?php include __DIR__ . '/../includes/header_loc.php'; ?>

<style>
    .modal {
        transition: all 0.3s ease;
        z-index: 50;
    }

    .modal.active {
        opacity: 1;
        pointer-events: auto;
    }

    .modal-content {
        max-width: 800px;
        background-color: white;
        border-radius: 1rem;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    }

    @media (min-width: 768px) {
        .modal-content {
            flex-direction: row;
        }
    }

    .card-hover {
        border: 2px solid transparent;
        transition: border 0.3s;
    }

    .card-hover:hover {
        border-image: linear-gradient(to right, #8e2de2, #c31432) 1;
    }

    #overlay {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(2px);
        opacity: 1;
        pointer-events: auto;
        z-index: 40;
    }
</style>

<div id="overlay"></div>

<div class="modal active fixed inset-0 flex items-center justify-center px-4">
    <div class="modal-content bg-white rounded-lg overflow-hidden">
        <!-- Image robe -->
        <div class="md:w-1/2">
            <img src="/site_stage/chic-and-chill/assets/images/products/<?= htmlspecialchars($product['image']); ?>"
                 alt="<?= htmlspecialchars($product['name']); ?>"
                 class="w-full h-full object-cover rounded-l-lg">
        </div>

        <!-- Détails -->
        <div class="md:w-1/2 p-6 flex flex-col justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">
                    <?= htmlspecialchars($product['name']); ?>
                </h2>
                <p class="text-gray-700 text-sm mb-4">
                    <?= htmlspecialchars($product['description'] ?? 'Découvrez cette magnifique robe disponible à la location, idéale pour vos occasions spéciales.'); ?>
                </p>
                <p class="text-lg text-[#4d4035] font-semibold">
                    <?= number_format($product['price'], 2); ?> € / jour
                </p>
            </div>

            <div class="mt-6 flex justify-between items-center">
                <a href="#" 
                   class="inline-block px-6 py-3 bg-[#4d4035] text-white rounded hover:bg-[#B2AC88] transition add-to-cart"
                   data-product='<?= json_encode([
                       "id" => $product["id"],
                       "name" => $product["name"],
                       "price" => $product["price"],
                       "image" => $product["image"]
                   ]) ?>'>
                    Louer
                </a>
                <a href="/location" class="text-sm text-gray-500 hover:text-gray-700 underline">Retour</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const addToCartBtn = document.querySelector('.add-to-cart');

        if (addToCartBtn) {
            addToCartBtn.addEventListener('click', function (e) {
                e.preventDefault();
                const product = JSON.parse(this.dataset.product);
                let cart = JSON.parse(localStorage.getItem("cart")) || [];

                const exists = cart.find(item => item.id === product.id);
                if (!exists) {
                    product.quantity = 1;
                    cart.push(product);
                    localStorage.setItem("cart", JSON.stringify(cart));
                    alert("Produit ajouté au panier !");
                } else {
                    alert("Ce produit est déjà dans le panier.");
                }
            });
        }
    });
</script>

<?php include __DIR__ . '/../includes/footer_loc.php'; ?>
