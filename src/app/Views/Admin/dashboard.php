<?php include('src/app/Views/includes/admin_header.php'); ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">📊 Panneau d'Administration</h1>

    <!-- Onglets de navigation -->
    <div class="flex space-x-4 border-b pb-2">
        <button class="tab-button active" data-target="events">📅 Événements</button>
        <button class="tab-button" data-target="packs">🎁 Packs</button>
        <button class="tab-button" data-target="reservations">📜 Réservations</button>
        <button class="tab-button" data-target="users">👥 Utilisateurs</button>
        <button class="tab-button" data-target="messages">📩 Messages</button>
        <button class="tab-button" data-target="newsletter">📬 Newsletter</button>
    </div>

    <!-- Contenu des onglets -->
    <div class="tab-content active" id="events">
        <?php include('src/app/Views/Admin/admin_events.php'); ?>
    </div>

    <div class="tab-content" id="packs">
        <?php include('src/app/Views/Admin/admin_packs.php'); ?>
    </div>

    <div class="tab-content" id="reservations">
        <?php include('src/app/Views/Admin/admin_reservations.php'); ?>
    </div>

    <div class="tab-content" id="users">
        <?php include('src/app/Views/Admin/admin_users.php'); ?>
    </div>

    <div class="tab-content" id="messages">
        <?php include('src/app/Views/Admin/admin_messages.php'); ?>
    </div>

    <div class="tab-content" id="newsletter">
        <?php include('src/app/Views/Admin/admin_newsletter.php'); ?>
    </div>
</div>

<!-- Script pour gérer les onglets -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tabButtons = document.querySelectorAll(".tab-button");
        const tabContents = document.querySelectorAll(".tab-content");

        tabButtons.forEach(button => {
            button.addEventListener("click", () => {
                tabButtons.forEach(btn => btn.classList.remove("active"));
                button.classList.add("active");

                tabContents.forEach(content => content.classList.remove("active"));
                document.getElementById(button.dataset.target).classList.add("active");
            });
        });
    });
</script>

<style>
    .tab-button {
        padding: 10px 20px;
        background: #8B5A2B;
        color: white;
        border-radius: 5px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .tab-button.active {
        background: #5a3d1c;
    }

    .tab-content {
        display: none;
        padding-top: 20px;
    }

    .tab-content.active {
        display: block;
    }
</style>

<?php include('src/app/Views/includes/admin_footer.php'); ?>
