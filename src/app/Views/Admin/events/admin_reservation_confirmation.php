<?php
// Inclusion des fichiers de structure pour la page admin
include('src/app/Views/includes/admin/admin_head.php');   // Contient les métadonnées et les scripts/styles de base
include('src/app/Views/includes/admin/admin_header.php'); // En-tête de la page admin (navbar, etc.)
include('src/app/Views/includes/admin/admin_sidebar.php'); // Barre latérale avec navigation admin
?>

<!-- Conteneur principal avec ajustements pour la responsivité -->
<div class="min-h-screen flex flex-col pl-0 md:pl-64 mt-12">
    <div class="container mx-auto px-4 py-6 md:px-6 md:py-8 flex-grow">
        <!-- Titre de la page avec taille responsive -->
        <h2 class="text-xl md:text-3xl font-bold text-gray-800 mb-6 md:mb-8">Résultat de l'opération</h2>

        <!-- Affichage conditionnel du message de résultat -->
        <?php if (isset($_SESSION['message'])) : ?>
            <!-- Conteneur du message avec style conditionnel basé sur le type (success, warning, error) -->
            <div class="p-3 md:p-4 rounded-md text-sm md:text-base <?php echo $_SESSION['message']['type'] === 'success' ? 'bg-green-100 text-green-800' : ($_SESSION['message']['type'] === 'warning' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'); ?>">
                <!-- Texte du message en gras -->
                <p class="font-semibold"><?php echo $_SESSION['message']['text']; ?></p>
            </div>
            <!-- Message informatif sur la redirection -->
            <p class="mt-3 md:mt-4 text-gray-600 text-sm md:text-base">Vous serez redirigé vers la liste des réservations dans quelques secondes...</p>
            <!-- Lien manuel pour retourner immédiatement -->
            <a href="admin/reservations" class="mt-2 inline-block text-blue-600 hover:underline text-sm md:text-base">⬅️ Retourner maintenant</a>
        <?php else : ?>
            <!-- Message par défaut si aucun message n'est présent dans la session -->
            <p class="text-gray-600 text-sm md:text-base">Aucun message disponible.</p>
            <!-- Lien pour retourner à la liste des réservations -->
            <a href="admin/reservations" class="mt-2 inline-block text-blue-600 hover:underline text-sm md:text-base">Retourner à la liste des réservations</a>
        <?php endif; ?>
    </div>
</div>

</body>

<script>
    // Script pour gérer la redirection automatique
    <?php if (isset($_SESSION['message'])) : ?>
        // Si un message est présent dans la session, redirection après 3 secondes
        setTimeout(function() {
            window.location.href = 'admin/reservations'; // Redirige vers la liste des réservations
        }, 3000); // Délai de 3000ms (3 secondes)
    <?php endif; ?>
</script>
</html>