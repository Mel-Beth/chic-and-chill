<?php include 'src/app/Views/includes/header_loc.php'; ?>

<main class="container mx-auto px-4 py-12 max-w-4xl">
    <h1 class="text-3xl font-bold text-center mb-8">Votre Panier</h1>

    <div id="panierContent"></div>

    <div class="mt-6 text-center">
        <a href="/location" class="bg-gray-800 text-white px-6 py-2 rounded hover:bg-gray-600">Continuer vos achats</a>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const panierContent = document.getElementById('panierContent');
        const cart = JSON.parse(localStorage.getItem("cart")) || [];

        if (cart.length === 0) {
            panierContent.innerHTML = '<p class="text-center text-gray-500">Votre panier est vide.</p>';
        } else {
            let output = '<table class="w-full text-left table-auto border-collapse">';
            output += '<thead><tr><th>Article</th><th>Quantité</th><th>Prix</th><th>Action</th></tr></thead><tbody>';

            cart.forEach((item, index) => {
                output += <tr>
                    <td>${item.name}</td>
                    <td>${item.quantity}</td>
                    <td>${(item.price * item.quantity).toFixed(2)} €</td>
                    <td><button class="removeItem" data-index="${index}">Supprimer</button></td>
                </tr>;
            });

            output += '</tbody></table>';
            panierContent.innerHTML = output;

            document.querySelectorAll('.removeItem').forEach(button => {
                button.addEventListener('click', function () {
                    const index = this.getAttribute('data-index');
                    cart.splice(index, 1);
                    localStorage.setItem("cart", JSON.stringify(cart));
                    location.reload();
                });
            });
        }
    });
</script>

<?php include 'src/app/Views/includes/footer_loc.php'; ?>