<?php
include('src/app/Views/includes/admin/admin_head.php');
include('src/app/Views/includes/admin/admin_header.php');
include('src/app/Views/includes/admin/admin_sidebar.php');
?>

<div id="dashboard" class="min-h-screen flex flex-col transition-all duration-300 pl-20 lg:pl-64 mt-16">
    <div class="container mx-auto px-6 py-12 flex-grow max-w-7xl">
        <!-- Boutons de contrôle -->
        <div class="flex justify-between mb-6">
            <select id="timeFilter" class="p-2 border rounded">
                <option value="month">Dernier mois</option>
                <option value="quarter">Dernier trimestre</option>
                <option value="year">Année</option>
            </select>
            <button id="exportBtn" class="p-2 bg-blue-500 text-white rounded">Exporter en CSV</button>
        </div>

        <!-- 🔔 Notifications -->
        <div class="mt-10 bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-bold text-gray-700 mb-2">📢 Notifications</h2>
            <div class="flex items-center">
                <ul id="notificationsList" class="divide-y divide-gray-300 w-full"></ul>
                <div id="loadingSpinner" class="hidden animate-spin h-5 w-5 border-2 border-blue-500 rounded-full ml-2"></div>
            </div>
            <p id="notificationError" class="hidden text-red-500 mt-2">Erreur lors du chargement des notifications.</p>
        </div>

        <!-- Cartes de statistiques -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
            <div class="bg-white p-6 rounded-lg shadow-md cursor-pointer" onclick="window.location.href='admin/messages'">
                <h2 class="text-lg font-bold text-gray-700 mb-2">📩 Messages</h2>
                <p class="text-3xl font-bold text-gray-900"><?= $dashboardData['messages_count'] ?? 0; ?></p>
                <a href="admin/messages" class="text-sm text-blue-500 hover:underline">Voir tout</a>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md cursor-pointer" onclick="window.location.href='admin/evenements'">
                <h2 class="text-lg font-bold text-gray-700 mb-2">🎉 Événements</h2>
                <p class="text-3xl font-bold text-gray-900"><?= ($dashboardData['active_events'] ?? 0) . ' / ' . ($dashboardData['total_events'] ?? 0); ?> actifs</p>
                <a href="admin/evenements" class="text-sm text-blue-500 hover:underline">Gérer</a>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md cursor-pointer" onclick="window.location.href='admin/reservations'">
                <h2 class="text-lg font-bold text-gray-700 mb-2">🛒 Réservations</h2>
                <p class="text-3xl font-bold text-gray-900"><?= $dashboardData['pending_reservations'] ?? 0; ?> en attente</p>
                <a href="admin/reservations" class="text-sm text-blue-500 hover:underline">Voir tout</a>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md cursor-pointer" onclick="window.location.href='admin/newsletter'">
                <h2 class="text-lg font-bold text-gray-700 mb-2">📢 Newsletter</h2>
                <p class="text-3xl font-bold text-gray-900"><?= $dashboardData['subscribers_count'] ?? 0; ?> abonnés</p>
                <a href="admin/newsletter" class="text-sm text-blue-500 hover:underline">Gérer</a>
            </div>
        </div>

        <!-- Graphiques -->
        <div class="mt-10 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white p-4 rounded-lg shadow-md h-[450px]">
                <h2 class="text-lg font-bold text-gray-700 mb-1">📈 Évolution des réservations</h2>
                <canvas id="reservationsChart"></canvas>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-md h-[450px]">
                <h2 class="text-lg font-bold text-gray-700 mb-1">📊 Packs réservés</h2>
                <canvas id="packsChart"></canvas>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-md h-[450px]">
                <h2 class="text-lg font-bold text-gray-700 mb-1">📊 Sources des messages</h2>
                <?php if (empty($dashboardData['message_sources']['labels']) || empty($dashboardData['message_sources']['counts'])): ?>
                    <p class="text-gray-500 text-center">Aucune donnée disponible</p>
                <?php else: ?>
                    <canvas id="sourcesChart"></canvas>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const dashboardData = <?php echo json_encode($dashboardData ?? [], JSON_NUMERIC_CHECK); ?>;
    let reservationsChart, packsChart, sourcesChart;

    // Fonctions utilitaires
    const showSpinner = () => document.getElementById('loadingSpinner').classList.remove('hidden');
    const hideSpinner = () => document.getElementById('loadingSpinner').classList.add('hidden');

    // Initialisation des graphiques
    const initCharts = (data) => {
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
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    },
                    y: {
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    }
                }
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
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    },
                    y: {
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });

        const sourcesChartElement = document.getElementById('sourcesChart');
        if (sourcesChartElement) {
            console.log("Initialisation du graphique Sources des messages avec:", data.message_sources);
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
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: {
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });
        }
    };

    // Mise à jour des graphiques avec filtre
    const updateCharts = async (period) => {
        const response = await fetch(`admin/dashboard/stats?period=${period}`);
        const data = await response.json();
        reservationsChart.data.labels = data.reservation_months || [];
        reservationsChart.data.datasets[0].data = data.reservation_counts || [];
        reservationsChart.update();
        packsChart.data.labels = data.packs_labels || [];
        packsChart.data.datasets[0].data = data.packs_counts || [];
        packsChart.update();
        if (sourcesChart) {
            sourcesChart.data.labels = data.message_sources?.labels || [];
            sourcesChart.data.datasets[0].data = (data.message_sources?.counts || []).map(Number);
            sourcesChart.update();
        }
    };

    // Gestion des notifications
    const fetchNotifications = async () => {
        showSpinner();
        try {
            const response = await fetch("admin/notifications/unread");
            if (!response.ok) throw new Error('Erreur réseau');
            const data = await response.json();
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
                li.className = "py-3 flex justify-between";
                li.innerHTML = `
                    <span class="text-gray-600">${notif.message}</span>
                    <button class="markAsRead text-sm text-blue-500 hover:underline" data-id="${notif.id}">Marquer comme lu</button>
                `;
                notificationsList.appendChild(li);
            });
            attachReadEvents();
        } else {
            notificationsList.innerHTML = `<li class="py-3 text-gray-500">Aucune nouvelle notification</li>`;
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

    // Filtre temporel
    document.getElementById('timeFilter').addEventListener('change', (e) => updateCharts(e.target.value));

    // Initialisation
    document.addEventListener('DOMContentLoaded', () => {
        initCharts(dashboardData);
        fetchNotifications();
        setInterval(fetchNotifications, 10000);
    });
</script>