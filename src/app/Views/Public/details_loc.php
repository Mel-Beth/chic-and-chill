<?php include __DIR__ . '/../includes/header_loc.php'; ?>

<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden card-hover max-w-4xl mx-auto">
        <div class="flex justify-center">
            <div id="carouselImages" class="relative w-full">
                <?php
                $images = explode(',', $product['image']);
                foreach ($images as $index => $image) :
                ?>
                    <img src="/site_stage/chic-and-chill/assets/images/robe_loc/<?= trim($image); ?>" 
                         alt="<?= htmlspecialchars($product['name']); ?>" 
                         class="w-full h-80 object-cover <?php if ($index !== 0) echo 'hidden'; ?>" 
                         data-index="<?= $index; ?>">
                <?php endforeach; ?>
                
                <!-- Navigation -->
                <button id="prevImage" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-gray-800 text-white p-2">◀</button>
                <button id="nextImage" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-gray-800 text-white p-2">▶</button>
            </div>
        </div>

        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4"><?= htmlspecialchars($product['name']); ?></h2>
            <p class="text-gray-600"><?= htmlspecialchars($product['description']); ?></p>
            <p class="text-lg text-[#B71C1C] font-bold mt-4"><?= number_format($product['price'], 2); ?> € / jour</p>

            <a href="/site_stage/chic-and-chill/location/reserve/<?= $product['id']; ?>" 
               class="mt-4 inline-block bg-[#b71c1c] text-white px-6 py-2 rounded hover:bg-[#ff0000] transition">
               Louer cette robe
            </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const images = document.querySelectorAll('[data-index]');
        let currentIndex = 0;

        const showImage = index => {
            images.forEach((img, i) => {
                img.classList.toggle('hidden', i !== index);
            });
        };

        document.getElementById('prevImage').addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            showImage(currentIndex);
        });

        document.getElementById('nextImage').addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % images.length;
            showImage(currentIndex);
        });

        showImage(currentIndex);
    });
</script>

<?php include __DIR__ . '/../includes/footer_loc.php'; ?>