    // Attendre que le DOM soit chargé
    document.addEventListener('DOMContentLoaded', () => {
        // Gestion du menu burger
        const menuToggle = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        const closeMenu = document.getElementById('close-menu');

        // Vérifier si les éléments existent
        if (!menuToggle || !mobileMenu || !closeMenu) {
            console.error('Un ou plusieurs éléments du menu burger ne sont pas trouvés :', {
                menuToggle: !!menuToggle,
                mobileMenu: !!mobileMenu,
                closeMenu: !!closeMenu
            });
            return;
        }

        // Gestion de l'ouverture du menu
        menuToggle.addEventListener('click', () => {
            console.log('Bouton menu-toggle cliqué');
            mobileMenu.classList.remove('hidden');
            mobileMenu.classList.add('open');
        });

        // Gestion de la fermeture du menu
        closeMenu.addEventListener('click', () => {
            console.log('Bouton close-menu cliqué');
            mobileMenu.classList.remove('open');
            setTimeout(() => {
                mobileMenu.classList.add('hidden');
            }, 300); // Attendre la fin de l'animation
        });

        // Fermer le menu en cliquant sur un lien
        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                console.log('Lien dans le menu mobile cliqué');
                mobileMenu.classList.remove('open');
                setTimeout(() => {
                    mobileMenu.classList.add('hidden');
                }, 300);
            });
        });

        // Gestion de l'effet de scroll pour le header
        window.addEventListener("scroll", () => {
            const header = document.getElementById("main-header");
            header.classList.toggle("scrolled", window.scrollY > 50);
        });
    });
