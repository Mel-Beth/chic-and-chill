<!DOCTYPE html>
<html lang="fr">

<?php include("src/app/Views/includes/head.php"); ?>
<?php include("src/app/Views/includes/header.php"); ?>

<!-- Conteneur principal -->
<div id="hero-container" class="relative w-full h-screen flex items-center justify-center overflow-hidden pt-[80px]">

    <!-- Image de fond -->
    <img src="assets/images/image_accueil.png" alt="Chic And Chill" class="absolute top-0 left-0 w-full h-full object-cover z-0">

    <!-- Cercle central avec texte -->
    <div id="circle-main" class="absolute flex items-center justify-center z-10 rounded-full border-[3px] border-[#8B5A2B]">
        <p class="text-[#8B5A2B] font-bold text-center tracking-wide leading-tight">CHIC <br> AND <br> CHILL</p>
    </div>

    <!-- Cercles décoratifs repositionnés -->
    <div id="circle-even" class="absolute border-[3px] border-[#8B5A2B] rounded-full z-10"></div>
    <div id="circle-location" class="absolute border-[3px] border-[#8B5A2B] rounded-full z-10"></div>
    <div id="circle-magasin" class="absolute border-[3px] border-[#8B5A2B] rounded-full z-10"></div>

    <!-- Labels repositionnés -->
    <span id="label-even" class="absolute text-[#8B5A2B] font-bold tracking-wide z-10">EVENEMENTS</span>
    <span id="label-location" class="absolute text-[#8B5A2B] font-bold tracking-wide z-10">LOCATION</span>
    <span id="label-magasin" class="absolute text-[#8B5A2B] font-bold tracking-wide z-10">MAGASIN</span>
</div>

<?php include 'src/app/Views/includes/footer.php'; ?>

<script>
    function adjustElements() {
        let container = document.getElementById('hero-container');
        let width = container.offsetWidth;
        let height = container.offsetHeight;

        // Ajustement du cercle central
        let mainCircle = document.getElementById('circle-main');
        mainCircle.style.width = width * 0.25 + "px";
        mainCircle.style.height = width * 0.25 + "px";
        mainCircle.style.left = "50%";
        mainCircle.style.top = "48%";
        mainCircle.style.transform = "translate(-50%, -50%)";
        mainCircle.querySelector("p").style.fontSize = width * 0.03 + "px";

        // Cercles décoratifs
        let evenCircle = document.getElementById('circle-even');
        let locationCircle = document.getElementById('circle-location');
        let magasinCircle = document.getElementById('circle-magasin');

        evenCircle.style.width = width * 0.13 + "px";
        evenCircle.style.height = width * 0.13 + "px";
        evenCircle.style.left = "9%";
        evenCircle.style.top = "12%";

        locationCircle.style.width = width * 0.13 + "px";
        locationCircle.style.height = width * 0.13 + "px";
        locationCircle.style.right = "9%";
        locationCircle.style.top = "12%";

        magasinCircle.style.width = width * 0.11 + "px";
        magasinCircle.style.height = width * 0.11 + "px";
        magasinCircle.style.left = "10%";
        magasinCircle.style.bottom = "14%";

        // Labels repositionnés
        let evenLabel = document.getElementById('label-even');
        let locationLabel = document.getElementById('label-location');
        let magasinLabel = document.getElementById('label-magasin');

        evenLabel.style.left = "11%";
        evenLabel.style.top = "14%";
        evenLabel.style.fontSize = width * 0.018 + "px";

        locationLabel.style.right = "11%";
        locationLabel.style.top = "14%";
        locationLabel.style.fontSize = width * 0.018 + "px";

        magasinLabel.style.left = "12%";
        magasinLabel.style.bottom = "15%";
        magasinLabel.style.fontSize = width * 0.018 + "px";
    }

    // Ajustement automatique à chaque redimensionnement
    window.addEventListener('resize', adjustElements);
    window.addEventListener('load', adjustElements);
</script>

</body>
</html>
