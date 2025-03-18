document.addEventListener("DOMContentLoaded", function() {
    const searchIcon = document.querySelector(".icons_shop_nav_bar .fa-search"); 
    const searchBox = document.createElement("div");
    searchBox.classList.add("search-box");

    //Création dynamiquement de la boîte de recherche
    searchBox.innerHTML = `
        <input type="text" id="search-input" placeholder="Rechercher..."> 
        <button id="search-btn">Rechercher</button>
        <ul id="search-results"></ul>
    `;
    document.body.appendChild(searchBox);

    searchIcon.addEventListener("click", function(event) {
        event.stopPropagation(); //empeche propa du clic pr pas fermer la boite par inadvertance
        searchBox.classList.toggle("visible");
        document.getElementById("search-input").focus();
    });

    document.addEventListener("click", function(event) {
        if (!searchBox.contains(event.target) && !searchIcon.contains(event.target)) {
            searchBox.classList.remove("visible");//si on clique à côté, ça ferme la boite
        }
    });

    document.getElementById("search-input").addEventListener("input", function() {
        let query = this.value;
        if (query.length > 2) {
            fetch("LoupeRechercheShop.php?q=" + encodeURIComponent(query))
                .then(response => response.json())
                .then(data => {
                    const resultsList = document.getElementById("search-results");
                    resultsList.innerHTML = "";
                    data.forEach(item => {
                        let li = document.createElement("li");
                        li.innerHTML = `<a href="produits_shop?id=${item.id}">${item.name}</a>`;
                        resultsList.appendChild(li);
                    });
                })
                .catch(error => console.error("Erreur recherche :", error));
        }
    });

    document.getElementById("search-input").addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            window.location.href = "produits_shop.php?q=" + encodeURIComponent(this.value);
        }
    });

    document.getElementById("search-btn").addEventListener("click", function() {
        let query = document.getElementById("search-input").value;
        window.location.href = "produits_shop.php?q=" + encodeURIComponent(query); 
    });
});


