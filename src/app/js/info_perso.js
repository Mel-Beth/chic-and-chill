document.addEventListener("DOMContentLoaded", function() {
    const infosPersoBtn = document.querySelector("a[href='profil_infos_shop']");
    const sectionInfosPerso = document.getElementById("infos_perso_section");
    const sectionHistorique = document.getElementById("section_historique");
    const sectionNouveautes = document.getElementById("nouveaute_section");

    // Vérifier l'URL et afficher la bonne section
    if (window.location.hash === "#info_perso") {
        sectionHistorique.style.display = "none";
        sectionInfosPerso.style.display = "block";
        sectionNouveautes.style.display = "none";
    }

    infosPersoBtn.addEventListener("click", function(event) {
        event.preventDefault();
        window.location.hash = "info_perso"; // Change l'URL
        sectionHistorique.style.display = "none";
        sectionInfosPerso.style.display = "block";
        sectionNouveautes.style.display = "none";
    });
});

