document.addEventListener("DOMContentLoaded", function () {
    const paiementForm = document.getElementById("paiement-form");

    if (paiementForm) {
        paiementForm.addEventListener("submit", async function (event) {
            event.preventDefault();

            // Affiche ce qui est envoyé
            const formData = new FormData(paiementForm);
console.log("Données envoyées :", [...formData.entries()]);

            
            console.log("Données envoyées :", [...formData.entries()]);

            const response = await fetch("paiement_cb_shop", {
                method: "POST",
                body: formData
            });

            const result = await response.json();
            console.log("Réponse du serveur :", result);

            if (result.id) {
                const stripe = Stripe('pk_test_51Qee9BBrAcYVY0bNUxBtEtD30Iu0av16MjctdnoG5ArbNYp8d1hKHJSA1mp2lJoGXQEHc6sr4llGl7lMksu7ppQs00FeHFqACu');
                stripe.redirectToCheckout({ sessionId: result.id });
            } else {
                alert("Erreur lors de la création de la session de paiement.");
            }
        });
    }
});
