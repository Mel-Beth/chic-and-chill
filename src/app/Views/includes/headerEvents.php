<header id="main-header" class="fixed top-0 left-0 w-full z-50 transition-all duration-300 bg-transparent">
    <div class="container mx-auto flex justify-between items-center px-10 py-3">
        <!-- Logo + Texte -->
        <div class="flex items-center space-x-4">
            <!-- Logo -->
            <a href="accueil">
                <img src="assets/images/logo_magasin-chic.png" alt="Chic & Chill Logo" class="w-30 h-30 object-contain">
            </a>

            <!-- Texte CHIC AND CHILL -->
            <div class="text-white font-bold text-3xl tracking-wide transition-all duration-300" id="brand-text" style="font-family: 'Cormorant Garamond', serif;">
                <span class="brand-chic">CHIC</span> <span class="text-[#8B5A2B]">AND</span> <span class="brand-chill">CHILL</span>
            </div>
        </div>

        <!-- Menu -->
        <nav class="hidden md:flex space-x-8 text-lg font-semibold transition-all duration-300">
            <a href="accueil" class="menu-link relative"><i class="fas fa-home"></i> Accueil</a>
            <a href="evenements" class="menu-link relative"><i class="fas fa-calendar-alt"></i> Événements</a>
            <a href="location" class="menu-link relative"><i class="fas fa-map-marker-alt"></i> Location</a>
            <a href="magasin" class="menu-link relative"><i class="fas fa-shopping-bag"></i> Magasin</a>
            <a href="contact_evenements" class="menu-link relative"><i class="fas fa-envelope"></i> Contact</a>
            <a href="admin/dashboard" class="menu-link relative"><i class="fas fa-chart-line"></i> Dashboard</a>
        </nav>

        <!-- Menu mobile -->
        <div class="md:hidden">
            <button id="menu-toggle" class="text-white focus:outline-none text-2xl"><i class="fas fa-bars"></i></button>
        </div>
    </div>
</header>

<style>
    /* Style du header par défaut (transparent au départ) */
    #main-header {
        background: rgba(0, 0, 0, 0);
        transition: background 0.4s ease-in-out, box-shadow 0.4s ease-in-out;
    }

    /* Style du header après scroll */
    #main-header.scrolled {
        background: rgba(0, 0, 0, 0.9) !important;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }

    /* Changement de couleur des liens */
    .menu-link {
        color: white;
        transition: color 0.3s ease-in-out;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    #main-header.scrolled .menu-link {
        color: #8B5A2B;
    }

    /* Changement de couleur du texte du magasin */
    #brand-text {
        transition: color 0.3s ease-in-out;
    }

    /* CHIC et CHILL en blanc au départ */
    .brand-chic,
    .brand-chill {
        color: white;
        transition: color 0.3s ease-in-out;
    }

    /* CHIC et CHILL deviennent noirs après scroll, AND reste marron */
    #main-header.scrolled .brand-chic,
    #main-header.scrolled .brand-chill {
        color: white;
    }

    /* Icônes */
    .menu-link i {
        font-size: 1.2rem;
    }
</style>

<script>
    window.addEventListener("scroll", function() {
        const header = document.getElementById("main-header");
        if (window.scrollY > 50) {
            header.classList.add("scrolled");
        } else {
            header.classList.remove("scrolled");
        }
    });

    // Menu mobile
    document.getElementById("menu-toggle").addEventListener("click", function() {
        const mobileMenu = document.getElementById("mobile-menu");
        mobileMenu.classList.toggle("hidden");
    });

    // Sous-menu mobile (dropdown)
    document.getElementById("mobile-dropdown-toggle").addEventListener("click", function() {
        const dropdown = document.getElementById("mobile-dropdown");
        const icon = document.getElementById("dropdown-icon");
        dropdown.classList.toggle("hidden");
        icon.textContent = dropdown.classList.contains("hidden") ? "▼" : "▲";
    });
</script>