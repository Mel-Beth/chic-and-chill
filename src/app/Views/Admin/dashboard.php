<?php
include('src/app/Views/includes/admin_head.php');
include('src/app/Views/includes/admin_header.php');
include('src/app/Views/includes/admin_sidebar.php');
?>

<div id="dashboard" class="min-h-screen flex flex-col transition-all duration-300 pl-20 lg:pl-64 mt-10">
    <div class="container mx-auto px-6 py-12 flex-grow max-w-7xl">
        <!-- 🔔 Notifications -->
        <div class="mt-10 bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-bold text-gray-700 mb-2">📢 Notifications</h2>

            <ul id="notificationsList" class="divide-y divide-gray-300">
                <?php if (!empty($dashboardData['notifications'])): ?>
                    <?php foreach ($dashboardData['notifications'] as $notif): ?>
                        <li class="py-3 flex justify-between">
                            <span class="text-gray-600"><?= htmlspecialchars($notif['message']) ?></span>
                            <button class="markAsRead text-sm text-blue-500 hover:underline" data-id="<?= $notif['id'] ?>">Marquer comme lu</button>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="py-3 text-gray-500">Aucune nouvelle notification</li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Cartes de statistiques -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-2">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-bold text-gray-700 mb-2">📩 Messages</h2>
                <p class="text-3xl font-bold text-gray-900"> <?= $dashboardData['messages_count']; ?> </p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-bold text-gray-700 mb-2">🎉 Événements</h2>
                <p class="text-3xl font-bold text-gray-900"> <?= $dashboardData['active_events']; ?> / <?= $dashboardData['total_events']; ?> actifs</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-bold text-gray-700 mb-2">🛒 Réservations</h2>
                <p class="text-3xl font-bold text-gray-900"> <?= $dashboardData['pending_reservations']; ?> en attente</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-bold text-gray-700 mb-2">📢 Newsletter</h2>
                <p class="text-3xl font-bold text-gray-900"> <?= $dashboardData['subscribers_count']; ?> abonnés</p>
            </div>
        </div>

        <!-- Graphiques -->
        <div class="mt-10 grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md h-96">
                <h2 class="text-lg font-bold text-gray-700 mb-2">📈 Évolution des réservations</h2>
                <canvas id="reservationsChart"></canvas>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md h-96">
                <h2 class="text-lg font-bold text-gray-700 mb-2">📊 Packs réservés</h2>
                <canvas id="packsChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    console.log(<?= json_encode($dashboardData); ?>);

    var ctx1 = document.getElementById('reservationsChart').getContext('2d');
    var reservationsChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: <?= json_encode($dashboardData['reservation_months']); ?>,
            datasets: [{
                label: 'Réservations',
                data: <?= json_encode($dashboardData['reservation_counts']); ?>,
                borderColor: '#8B5A2B',
                fill: false
            }]
        }
    });

    var ctx2 = document.getElementById('packsChart').getContext('2d');
    var packsChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: <?= json_encode($dashboardData['packs_labels']); ?>,
            datasets: [{
                label: 'Packs réservés',
                data: <?= json_encode($dashboardData['packs_counts']); ?>,
                backgroundColor: '#8B5A2B'
            }]
        }
    });

    function fetchNotifications() {
        fetch("admin/notifications/unread")
            .then(response => response.json())
            .then(data => {
                const notificationsList = document.getElementById("notificationsList");
                notificationsList.innerHTML = ""; // Vide la liste avant de la remplir

                if (data.length > 0) {
                    data.forEach(notification => {
                        const li = document.createElement("li");
                        li.className = "py-3 flex justify-between";
                        li.innerHTML = `
                            <span class="text-gray-600">${notification.message}</span>
                            <button class="markAsRead text-sm text-blue-500 hover:underline" data-id="${notification.id}">Marquer comme lu</button>
                        `;
                        notificationsList.appendChild(li);
                    });
                } else {
                    notificationsList.innerHTML = `<li class="py-3 text-gray-500">Aucune nouvelle notification</li>`;
                }

                attachReadEvent();
            })
            .catch(error => console.error("Erreur lors de la récupération des notifications :", error));
    }

    function attachReadEvent() {
        document.querySelectorAll(".markAsRead").forEach(button => {
            button.addEventListener("click", function() {
                let notifId = this.dataset.id;
                fetch(`admin/notifications/read/${notifId}`, {
                        method: "POST"
                    })
                    .then(() => fetchNotifications()); // Rafraîchir les notifications après la mise à jour
            });
        });
    }

    // Rafraîchir les notifications toutes les 10 secondes
    setInterval(fetchNotifications, 10000);
    fetchNotifications(); // Charger les notifications au chargement de la page
</script>