<?php
include('src/app/Views/includes/admin/admin_head.php');
include('src/app/Views/includes/admin/admin_header.php');
include('src/app/Views/includes/admin/admin_sidebar.php');
?>

<div id="dashboard" class="min-h-screen flex flex-col transition-all duration-300 pl-0 md:pl-64 mt-16">
    <div class="container mx-auto px-4 py-6 md:px-6 md:py-12 flex-grow max-w-7xl">
        <!-- Boutons de contrôle -->
        <div class="flex flex-col md:flex-row justify-between mb-6 space-y-4 md:space-y-0">
            <select id="timeFilter" class="p-2 border rounded w-full md:w-auto">
                <option value="month" selected>Dernier mois</option>
                <option value="quarter">Dernier trimestre</option>
                <option value="year">Année</option>
            </select>
            <button id="exportBtn" class="p-2 bg-blue-500 text-white rounded w-full md:w-auto">Exporter les données </button>
        </div>

        <!-- 🔔 Notifications -->
        <div class="mt-6 md:mt-10 bg-white p-4 md:p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-bold text-gray-700 mb-2">📢 Notifications</h2>
            <div class="flex items-center">
                <ul id="notificationsList" class="divide-y divide-gray-300 w-full"></ul>
                <div id="loadingSpinner" class="hidden animate-spin h-5 w-5 border-2 border-blue-500 rounded-full ml-2"></div>
            </div>
            <p id="notificationError" class="hidden text-red-500 mt-2">Erreur lors du chargement des notifications.</p>
        </div>

        <!-- Cartes de statistiques -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 mt-6">
            <div class="bg-white p-4 md:p-6 rounded-lg shadow">
                <h2 class="text-base md:text-lg font-semibold text-gray-700">Messages</h2>
                <p class="text-2xl md:text-3xl font-bold text-gray-900"><?= $dashboardData['messages_count'] ?? 0; ?></p>
            </div>
            <div class="bg-white p-4 md:p-6 rounded-lg shadow">
                <h2 class="text-base md:text-lg font-semibold text-gray-700">Événements actifs</h2>
                <p class="text-2xl md:text-3xl font-bold text-gray-900"><?= ($dashboardData['active_events'] ?? 0) . ' / ' . ($dashboardData['total_events'] ?? 0); ?></p>
            </div>
            <div class="bg-white p-4 md:p-6 rounded-lg shadow">
                <h2 class="text-base md:text-lg font-semibold text-gray-700">Réservations</h2>
                <p class="text-2xl md:text-3xl font-bold text-gray-900"><?= ($dashboardData['pending_reservations'] ?? 0); ?> en attente</p>
            </div>
            <div class="bg-white p-4 md:p-6 rounded-lg shadow">
                <h2 class="text-base md:text-lg font-semibold text-gray-700">Abonnés</h2>
                <p class="text-2xl md:text-3xl font-bold text-gray-900"><?= $dashboardData['subscribers_count'] ?? 0; ?> abonnés</p>
            </div>
        </div>

        <!-- Graphiques -->
        <div class="mt-6 md:mt-10 grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
            <div class="bg-white p-4 md:p-6 rounded-lg shadow-md h-64 md:h-96">
                <h2 class="text-base md:text-lg font-bold text-gray-700 mb-2">📈 Évolution des réservations</h2>
                <canvas id="reservationsChart"></canvas>
            </div>
            <div class="bg-white p-4 md:p-6 rounded-lg shadow-md h-64 md:h-96">
                <h2 class="text-base md:text-lg font-bold text-gray-700 mb-2">📊 Packs réservés</h2>
                <canvas id="packsChart"></canvas>
            </div>
            <div class="bg-white p-4 md:p-6 rounded-lg shadow-md h-64 md:h-96">
                <h2 class="text-base md:text-lg font-bold text-gray-700 mb-2">📊 Sources des messages</h2>
                <?php if (empty($dashboardData['message_sources']['labels']) || empty($dashboardData['message_sources']['counts'])): ?>
                    <p class="text-gray-500 text-center">Aucune donnée disponible</p>
                <?php else: ?>
                    <canvas id="sourcesChart"></canvas>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-yellow {
        background-color: #fefcbf !important;
        /* bg-yellow-200 */
    }

    .custom-green {
        background-color: #c6f6d5 !important;
        /* bg-green-200 */
    }

    .custom-blue {
        background-color: #bee3f8 !important;
        /* bg-blue-200 */
    }

    .custom-gray {
        background-color: #f7fafc !important;
        /* bg-gray-100 */
    }

    .custom-red {
        background-color: #fed7d7 !important;
        /* bg-red-200 pour ruptures de stock */
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const dashboardData = <?php echo json_encode($dashboardData ?? []); ?>;
    let reservationsChart, packsChart, sourcesChart;

    // Fonctions utilitaires
    const showSpinner = () => document.getElementById('loadingSpinner').classList.remove('hidden');
    const hideSpinner = () => document.getElementById('loadingSpinner').classList.add('hidden');

    // Initialisation des graphiques
    const initCharts = (data) => {
        if (reservationsChart) reservationsChart.destroy();
        if (packsChart) packsChart.destroy();
        if (sourcesChart) sourcesChart.destroy();

        const ctx1 = document.getElementById('reservationsChart').getContext('2d');
        reservationsChart = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: data.reservation_months || [],
                datasets: [{
                    label: 'Réservations',
                    data: data.reservation_counts || [],
                    borderColor: '#8B5A2B',
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        const ctx2 = document.getElementById('packsChart').getContext('2d');
        packsChart = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: data.packs_labels || [],
                datasets: [{
                    label: 'Packs réservés',
                    data: data.packs_counts || [],
                    backgroundColor: '#8B5A2B'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        const sourcesChartElement = document.getElementById('sourcesChart');
        if (sourcesChartElement) {
            const ctx3 = sourcesChartElement.getContext('2d');
            sourcesChart = new Chart(ctx3, {
                type: 'doughnut',
                data: {
                    labels: data.message_sources?.labels || [],
                    datasets: [{
                        data: (data.message_sources?.counts || []).map(Number),
                        backgroundColor: ['#8B5A2B', '#D4A5A5', '#A9A9A9']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
    };

    const updateDashboard = async (period) => {
        const response = await fetch(`admin/dashboard/stats?period=${period}`);
        const text = await response.text();
        try {
            const data = JSON.parse(text);

            if (reservationsChart) {
                reservationsChart.data.labels = data.reservation_months || [];
                reservationsChart.data.datasets[0].data = data.reservation_counts || [];
                reservationsChart.update();
            }

            if (packsChart) {
                packsChart.data.labels = data.packs_labels || [];
                packsChart.data.datasets[0].data = data.packs_counts || [];
                packsChart.update();
            }

            if (sourcesChart) {
                sourcesChart.data.labels = data.message_sources?.labels || [];
                sourcesChart.data.datasets[0].data = (data.message_sources?.counts || []).map(Number);
                sourcesChart.update();
            }

            document.querySelector('.bg-white.p-4.md\\:p-6:nth-child(1) p.text-2xl.md\\:text-3xl').textContent = data.messages_count || 0;
            document.querySelector('.bg-white.p-4.md\\:p-6:nth-child(2) p.text-2xl.md\\:text-3xl').textContent = `${data.active_events || 0} / ${data.total_events || 0}`;
            document.querySelector('.bg-white.p-4.md\\:p-6:nth-child(3) p.text-2xl.md\\:text-3xl').textContent = `${data.pending_reservations || 0} en attente`;
            document.querySelector('.bg-white.p-4.md\\:p-6:nth-child(4) p.text-2xl.md\\:text-3xl').textContent = `${data.subscribers_count || 0} abonnés`;
        } catch (error) {
            console.error("Erreur dans updateDashboard :", error);
        }
    };

    // Gestion des notifications
    const fetchNotifications = async () => {
        showSpinner();
        try {
            const response = await fetch("admin/notifications/unread");
            if (!response.ok) throw new Error('Erreur réseau');
            const data = await response.json();
            // console.log("Notifications récupérées :", data);
            updateNotificationsList(data);
        } catch (error) {
            document.getElementById('notificationError').classList.remove('hidden');
            console.error("Erreur:", error);
        } finally {
            hideSpinner();
        }
    };

    const updateNotificationsList = (notifications) => {
        const notificationsList = document.getElementById("notificationsList");
        notificationsList.innerHTML = "";
        if (notifications.length > 0) {
            notifications.forEach(notif => {
                const li = document.createElement("li");
                li.className = "py-2 md:py-3 flex flex-col md:flex-row justify-between items-start md:items-center rounded-md";

                // Déterminer la couleur en fonction du contenu de la notification
                if (notif.message.includes("Rappel : Envoyez la newsletter")) {
                    li.classList.add("custom-yellow"); // Jaune pour rappel newsletter
                } else if (notif.message.includes("Nouvelle réservation")) {
                    li.classList.add("custom-green"); // Vert pour nouvelle réservation
                } else if (notif.message.includes("Nouveau message")) {
                    li.classList.add("custom-blue"); // Bleu pour nouveau message
                } else if (notif.message.includes("rupture de stock")) {
                    li.classList.add("custom-red"); // Rouge pour rupture de stock
                } else {
                    li.classList.add("custom-gray"); // Gris par défaut
                }

                li.innerHTML = `
                    <span class="text-gray-600 text-sm md:text-base">${notif.message}</span>
                    <button class="markAsRead text-xs md:text-sm text-blue-500 hover:underline mt-2 md:mt-0" data-id="${notif.id}">Marquer comme lu</button>
                `;
                notificationsList.appendChild(li);
            });
            attachReadEvents();
        } else {
            notificationsList.innerHTML = `<li class="py-2 md:py-3 text-gray-500 text-sm md:text-base">Aucune nouvelle notification</li>`;
        }
    };

    const attachReadEvents = () => {
        document.querySelectorAll(".markAsRead").forEach(button => {
            button.removeEventListener('click', handleMarkAsRead);
            button.addEventListener('click', handleMarkAsRead);
        });
    };

    const handleMarkAsRead = async (e) => {
        const notifId = e.target.dataset.id;
        if (!notifId || isNaN(notifId)) return;
        try {
            const response = await fetch(`admin/notifications/read/${notifId}`, {
                method: "POST"
            });
            if (response.ok) fetchNotifications();
        } catch (error) {
            console.error("Erreur:", error);
        }
    };

    // Export CSV
    document.getElementById('exportBtn').addEventListener('click', () => {
        window.location.href = 'admin/export?type=dashboard';
    });

    // Initialisation
    document.addEventListener('DOMContentLoaded', () => {
        // console.log("DOM chargé, initialisation...");
        initCharts(dashboardData);
        fetchNotifications();
        setInterval(fetchNotifications, 10000); // Vérifie toutes les 10 secondes
    });

    document.getElementById('timeFilter').addEventListener('change', (e) => updateDashboard(e.target.value));
</script>