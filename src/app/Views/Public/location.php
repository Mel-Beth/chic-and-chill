<?php include 'src/app/Views/includes/header_loc.php'; ?>
<br><br><br><br>

<section class="bg-gradient-to-r from-[#b71c1c] to-[#ff0000] text-white text-center py-12">
    <h1 class="text-4xl font-bold uppercase tracking-wide">Location de Robes</h1>
    <p class="text-lg mt-2">Explorez notre collection et louez la tenue parfaite pour vos occasions spéciales.</p>
</section>

<section class="bg-gray-100 py-16">
    <div class="text-center mb-10">
        <h2 class="text-2xl font-bold text-gray-800">Nos robes disponibles</h2>
        <p class="text-gray-600">Cliquez sur une robe pour voir toutes les photos disponibles.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 px-6 mx-8">
        <?php foreach ($products as $p): ?>
            <div class="relative group cursor-pointer rounded-xl overflow-hidden shadow-md bg-white card-hover mx-4">
                <div
                    class="absolute -inset-1 bg-gradient-to-r from-[#b71c1c] to-[#ff0000] rounded-xl blur opacity-25 group-hover:opacity-100 transition duration-1000 group-hover:duration-200">
                </div>
                <div class="relative rounded-xl overflow-hidden">
                    <?php
                    $images = explode(',', $p['image']);
                    $mainImage = $images[0];
                    ?>
                    <img src="/site_stage/chic-and-chill/assets/images/robe_loc/<?= htmlspecialchars($mainImage); ?>"
                         alt="<?= htmlspecialchars($p['name']); ?>"
                         class="w-full h-34 object-cover">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($p['name']); ?></h3>
                        <p class="text-gray-600 mb-3"><?= number_format($p['price'], 2); ?> € / jour</p>
                        <div class="flex space-x-2">
                            <button 
                                class="px-4 py-2 bg-[#b71c1c] text-white rounded hover:bg-[#ff0000] transition open-details"
                                data-product='<?= json_encode($p); ?>'>
                                Détails & Réservation
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Modale Détails -->
<div id="productModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white w-full max-w-4xl mx-auto rounded-lg shadow-xl overflow-hidden relative p-6">
        <button id="closeModal" class="absolute top-4 right-4 text-gray-700 hover:text-black text-xl">×</button>
        <div class="flex">
            <!-- Carousel -->
            <div class="w-1/2 carousel-container relative">
                <div class="carousel-images"></div>
                <button id="prevImage" class="absolute left-2 top-1/2 transform -translate-y-1/2 text-black text-2xl">&#10094;</button>
                <button id="nextImage" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-black text-2xl">&#10095;</button>
            </div>

            <!-- Description -->
            <div class="w-1/2 pl-6 space-y-4">
                <h2 id="modalTitle" class="text-2xl font-bold text-gray-800"></h2>
                <p id="modalDescription" class="text-gray-600"></p>
                <p class="text-lg text-[#2a1c0e] font-semibold">Prix : <span id="modalPrice"></span> € / jour</p>

                <button id="addToCartButton"
                    class="mt-4 w-full bg-[#b71c1c] text-white py-2 px-4 rounded hover:bg-[#ff0000] transition">
                    Louer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById("productModal");
    const modalTitle = document.getElementById("modalTitle");
    const modalDescription = document.getElementById("modalDescription");
    const modalPrice = document.getElementById("modalPrice");
    const addToCartButton = document.getElementById("addToCartButton");
    const carouselContainer = document.querySelector(".carousel-images");

    let currentImageIndex = 0;
    let images = [];

    document.querySelectorAll(".open-details").forEach(button => {
        button.addEventListener("click", () => {
            const product = JSON.parse(button.getAttribute("data-product"));
            images = product.image.split(',');

            modalTitle.textContent = product.name;
            modalDescription.textContent = product.description || "Description non disponible.";
            modalPrice.textContent = parseFloat(product.price).toFixed(2);

            loadCarouselImages();

            addToCartButton.onclick = () => {
                const cart = JSON.parse(localStorage.getItem("cart")) || [];
                const existingItem = cart.find(item => item.id === product.id);

                if (existingItem) {
                    existingItem.quantity += 1;
                } else {
                    cart.push({ ...product, quantity: 1 });
                }
                localStorage.setItem("cart", JSON.stringify(cart));
                alert("Article ajouté au panier !");
                modal.classList.add("hidden");
            };

            modal.classList.remove("hidden");
        });
    });

    function loadCarouselImages() {
        carouselContainer.innerHTML = '';
        const img = document.createElement('img');
        img.src = `/site_stage/chic-and-chill/assets/images/robe_loc/${images[currentImageIndex]}`;
        img.className = 'w-full h-full object-cover rounded-l-lg';
        carouselContainer.appendChild(img);
    }

    document.getElementById("prevImage").addEventListener("click", () => {
        currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
        loadCarouselImages();
    });

    document.getElementById("nextImage").addEventListener("click", () => {
        currentImageIndex = (currentImageIndex + 1) % images.length;
        loadCarouselImages();
    });

    document.getElementById("closeModal").addEventListener("click", () => {
        modal.classList.add("hidden");
    });
    addToCartButton.onclick = () => {
    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    const existingItem = cart.find(item => item.id === product.id);

    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({ ...product, quantity: 1 });
    }
    localStorage.setItem("cart", JSON.stringify(cart));
    alert("Article ajouté au panier !");
    modal.classList.add("hidden");
};

</script>

<?php include 'src/app/Views/includes/footer_loc.php'; ?>