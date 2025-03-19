<?php
include('src/app/Views/includes/Admin/admin_head.php');
include('src/app/Views/includes/Admin/admin_header.php');
include('src/app/Views/includes/Admin/admin_sidebar.php');
?>

<div class="min-h-screen flex flex-col lg:pl-64 mt-12">
    <div class="container mx-auto px-6 py-8 flex-grow">
        <h2 class="text-3xl font-bold text-gray-800">⚙️ Paramètres</h2>

        <div class="bg-white p-6 rounded-lg shadow-md max-w-5xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Menu latéral -->
                <div class="bg-gray-100 p-4 rounded-lg">
                    <ul class="space-y-2">
                        <li><button class="tab-button active" data-target="account">Mon Compte</button></li>
                        <li><button class="tab-button" data-target="appearance">Apparence</button></li>
                        <li><button class="tab-button" data-target="security">Sécurité & Confidentialité</button></li>
                        <li><button class="tab-button" data-target="notifications">Notifications</button></li>
                        <li><button class="tab-button" data-target="history">Historique des Actions</button></li>
                        <li><button class="tab-button" data-target="data">Import/Export des Données</button></li>
                    </ul>
                </div>

                <!-- Contenu dynamique -->
                <div class="col-span-3">
                    <!-- Mon Compte -->
                    <div id="account" class="settings-content">
                        <h3 class="text-2xl font-semibold mb-4">Mon Compte</h3>
                        <form id="accountForm" action="admin/settings/update" method="POST">
                            <label class="block text-gray-600">Nom d’utilisateur</label>
                            <input type="text" name="username" value="<?= htmlspecialchars($settings['name'] ?? '') ?>" class="border px-4 py-2 rounded-md w-full">
                            <label class="block text-gray-600 mt-4">Adresse Email</label>
                            <input type="email" name="email" value="<?= htmlspecialchars($settings['email'] ?? '') ?>" class="border px-4 py-2 rounded-md w-full">
                            <label class="block text-gray-600 mt-4">Rôle</label>
                            <input type="text" name="role" value="<?= htmlspecialchars($settings['role'] ?? '') ?>" class="border px-4 py-2 rounded-md w-full" disabled>
                            <button type="submit" class="mt-4 bg-black text-white px-6 py-3 rounded-md hover:scale-105 transition">Mettre à jour</button>
                        </form>
                    </div>

                    <!-- Apparence -->
                    <div id="appearance" class="settings-content hidden">
                        <h3 class="text-2xl font-semibold mb-4">🎨 Apparence</h3>
                        <label class="block text-gray-600 mb-2">Mode Sombre / Clair</label>
                        <button id="toggleDarkMode" class="border px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300"><?= $appearance['dark_mode'] === 'enabled' ? '☀️ Mode Clair' : '🌙 Mode Sombre' ?></button>
                        <label class="block text-gray-600 mt-4">🎨 Couleur principale</label>
                        <select id="themeColor" class="border px-4 py-2 rounded-md w-full">
                            <option value="blue" <?= $appearance['theme_color'] === 'blue' ? 'selected' : '' ?>>Bleu</option>
                            <option value="red" <?= $appearance['theme_color'] === 'red' ? 'selected' : '' ?>>Rouge</option>
                            <option value="green" <?= $appearance['theme_color'] === 'green' ? 'selected' : '' ?>>Vert</option>
                            <option value="purple" <?= $appearance['theme_color'] === 'purple' ? 'selected' : '' ?>>Violet</option>
                            <option value="orange" <?= $appearance['theme_color'] === 'orange' ? 'selected' : '' ?>>Orange</option>
                        </select>
                        <label class="block text-gray-600 mt-4">🔤 Police d'écriture</label>
                        <select id="fontFamily" class="border px-4 py-2 rounded-md w-full">
                            <option value="sans-serif" <?= $appearance['font_family'] === 'sans-serif' ? 'selected' : '' ?>>Sans-serif (Par défaut)</option>
                            <option value="serif" <?= $appearance['font_family'] === 'serif' ? 'selected' : '' ?>>Serif</option>
                            <option value="monospace" <?= $appearance['font_family'] === 'monospace' ? 'selected' : '' ?>>Monospace</option>
                        </select>
                        <label class="block text-gray-600 mt-4">📏 Taille du texte</label>
                        <select id="fontSize" class="border px-4 py-2 rounded-md w-full">
                            <option value="normal" <?= $appearance['font_size'] === 'normal' ? 'selected' : '' ?>>Normal</option>
                            <option value="large" <?= $appearance['font_size'] === 'large' ? 'selected' : '' ?>>Grand</option>
                            <option value="x-large" <?= $appearance['font_size'] === 'x-large' ? 'selected' : '' ?>>Extra-large</option>
                        </select>
                        <label class="block text-gray-600 mt-4">📶 Affichage des statistiques</label>
                        <div class="flex space-x-4">
                            <label class="flex items-center"><input type="checkbox" id="showTraffic" <?= $appearance['show_traffic'] ? 'checked' : '' ?> class="mr-2"> Trafic</label>
                            <label class="flex items-center"><input type="checkbox" id="showSales" <?= $appearance['show_sales'] ? 'checked' : '' ?> class="mr-2"> Ventes</label>
                            <label class="flex items-center"><input type="checkbox" id="showOrders" <?= $appearance['show_orders'] ? 'checked' : '' ?> class="mr-2"> Commandes</label>
                        </div>
                        <button id="saveAppearanceSettings" class="mt-4 bg-blue-600 text-white px-6 py-3 rounded-md hover:scale-105 transition">💾 Sauvegarder</button>
                    </div>

                    <!-- Sécurité & Confidentialité -->
                    <div id="security" class="settings-content hidden">
                        <h3 class="text-2xl font-semibold mb-4">🔒 Sécurité & Confidentialité</h3>
                        <label class="block text-gray-600">🔐 Changer le mot de passe</label>
                        <form id="passwordForm" action="admin/settings/update-password" method="POST">
                            <input type="password" name="current_password" placeholder="Mot de passe actuel" class="border px-4 py-2 rounded-md w-full mt-2">
                            <input type="password" name="new_password" placeholder="Nouveau mot de passe" class="border px-4 py-2 rounded-md w-full mt-2">
                            <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" class="border px-4 py-2 rounded-md w-full mt-2">
                            <button type="submit" class="mt-4 bg-black text-white px-6 py-3 rounded-md hover:scale-105 transition">Mettre à jour</button>
                        </form>
                        <label class="block text-gray-600 mt-6">⏳ Déconnexion automatique</label>
                        <select id="autoLogout" class="border px-4 py-2 rounded-md w-full">
                            <option value="never">Jamais</option>
                            <option value="5">Après 5 minutes</option>
                            <option value="15">Après 15 minutes</option>
                            <option value="30">Après 30 minutes</option>
                        </select>
                        <label class="block text-gray-600 mt-6">📧 Notifications de connexion suspecte</label>
                        <label class="flex items-center mt-2"><input type="checkbox" id="notifyLogin" checked class="mr-2"> Recevoir un e-mail</label>
                        <label class="block text-red-600 mt-6">🗑️ Suppression du compte</label>
                        <button id="deleteAccount" class="border px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700 mt-2">Supprimer définitivement</button>
                    </div>

                    <!-- Notifications -->
                    <div id="notifications" class="settings-content hidden">
                        <h3 class="text-2xl font-semibold mb-4">📢 Notifications</h3>
                        <label class="block text-gray-600">📩 Recevoir des notifications par e-mail pour :</label>
                        <label class="flex items-center mt-2"><input type="checkbox" id="notifyMessages" class="mr-2"> Nouveaux messages</label>
                        <label class="flex items-center mt-2"><input type="checkbox" id="notifyOrders" class="mr-2"> Nouvelles commandes</label>
                        <label class="flex items-center mt-2"><input type="checkbox" id="notifyReservations" class="mr-2"> Nouvelles réservations</label>
                        <label class="flex items-center mt-2"><input type="checkbox" id="notifyPackReservations" class="mr-2"> Réservations de packs</label>
                        <label class="flex items-center mt-2"><input type="checkbox" id="notifyProductsSoldRented" class="mr-2"> Produits achetés/loués</label>
                        <label class="block text-gray-600 mt-6">🔔 Notifications sur le site</label>
                        <label class="flex items-center mt-2"><input type="checkbox" id="siteNotifications" class="mr-2"> Activer</label>
                        <label class="block text-gray-600 mt-6">📅 Fréquence des notifications</label>
                        <select id="emailFrequency" class="border px-4 py-2 rounded-md w-full">
                            <option value="immediate">Immédiatement</option>
                            <option value="daily">Quotidien</option>
                            <option value="weekly">Hebdomadaire</option>
                        </select>
                        <button id="saveNotifications" class="mt-4 bg-black text-white px-6 py-3 rounded-md hover:scale-105 transition">Enregistrer</button>
                    </div>

                    <!-- Historique des Actions -->
                    <div id="history" class="settings-content hidden">
                        <h3 class="text-2xl font-semibold mb-4">📚 Historique des Actions</h3>
                        <?php if (!empty($history)) : ?>
                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse border">
                                    <thead>
                                        <tr class="bg-gray-200">
                                            <th class="border p-3">📅 Date</th>
                                            <th class="border p-3">🧑 Utilisateur</th>
                                            <th class="border p-3">🖥️ Action</th>
                                            <th class="border p-3">📍 Adresse IP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($history as $entry) : ?>
                                            <tr class="hover:bg-gray-100">
                                                <td class="border p-3"><?= htmlspecialchars($entry['action_date']) ?></td>
                                                <td class="border p-3"><?= htmlspecialchars($entry['username']) ?></td>
                                                <td class="border p-3"><?= htmlspecialchars($entry['action']) ?></td>
                                                <td class="border p-3"><?= htmlspecialchars($entry['ip_address']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else : ?>
                            <p class="text-gray-500">Aucune action enregistrée.</p>
                        <?php endif; ?>
                        <button id="refreshHistory" class="mt-4 bg-gray-200 px-4 py-2 rounded-md hover:bg-gray-300">🔄 Rafraîchir</button>
                    </div>

                    <!-- Import/Export des Données -->
                    <div id="data" class="settings-content hidden">
                        <h3 class="text-2xl font-semibold mb-4">🔗 Import/Export des Données</h3>
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h4 class="text-lg font-semibold mb-2">📤 Exporter les Données</h4>
                            <button id="exportUsers" class="border px-4 py-2 rounded-md bg-blue-500 text-white hover:bg-blue-600">Exporter utilisateurs (CSV)</button>
                            <button id="exportProducts" class="border px-4 py-2 rounded-md bg-blue-500 text-white hover:bg-blue-600">Exporter produits (CSV)</button>
                            <hr class="my-4">
                            <h4 class="text-lg font-semibold mb-2">📥 Importer des Données</h4>
                            <form id="importForm" action="admin/settings/import" method="POST" enctype="multipart/form-data">
                                <input type="file" name="importFile" accept=".csv" class="border px-4 py-2 rounded-md w-full">
                                <button type="submit" class="mt-2 border px-4 py-2 rounded-md bg-green-500 text-white hover:bg-green-600">Importer CSV</button>
                            </form>
                            <hr class="my-4">
                            <h4 class="text-lg font-semibold mb-2">🔄 Sauvegarde & Restauration</h4>
                            <button id="backupDb" class="border px-4 py-2 rounded-md bg-gray-500 text-white hover:bg-gray-600">Sauvegarder BDD</button>
                            <form id="restoreForm" action="admin/settings/restore" method="POST" enctype="multipart/form-data">
                                <input type="file" name="backupFile" accept=".sql" class="border px-4 py-2 rounded-md w-full">
                                <button type="submit" class="mt-2 border px-4 py-2 rounded-md bg-red-500 text-white hover:bg-red-600">Restaurer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .dark-mode {
        background-color: #1a202c;
        color: #e2e8f0;
    }
    .dark-mode .bg-white {
        background-color: #2d3748;
    }
    .dark-mode .text-gray-800 {
        color: #e2e8f0;
    }
    .dark-mode .bg-gray-100 {
        background-color: #4a5568;
    }
    .dark-mode .bg-gray-200 {
        background-color: #718096;
    }
    /* Assurez-vous que la couleur principale est utilisée */
    body {
        color: var(--main-color);
    }
    h2, h3, button {
        color: var(--main-color);
    }
</style>

<script>
// Appliquer les paramètres d'apparence au chargement
document.addEventListener("DOMContentLoaded", function() {
    const body = document.body;
    const darkMode = "<?= $appearance['dark_mode'] ?>";
    const themeColor = "<?= $appearance['theme_color'] ?>";
    const fontFamily = "<?= $appearance['font_family'] ?>";
    const fontSize = "<?= $appearance['font_size'] === 'normal' ? '16px' : ($appearance['font_size'] === 'large' ? '18px' : '20px') ?>";

    if (darkMode === "enabled") body.classList.add("dark-mode");
    document.documentElement.style.setProperty("--main-color", themeColor);
    body.style.fontFamily = fontFamily;
    body.style.fontSize = fontSize;
});

// Gestion des onglets
document.querySelectorAll(".tab-button").forEach(button => {
    button.addEventListener("click", function() {
        document.querySelectorAll(".settings-content").forEach(content => content.classList.add("hidden"));
        document.querySelectorAll(".tab-button").forEach(btn => btn.classList.remove("active"));
        document.getElementById(this.dataset.target).classList.remove("hidden");
        this.classList.add("active");
    });
});

// Mon Compte
document.getElementById("accountForm").addEventListener("submit", function(e) {
    e.preventDefault();
    fetch(this.action, { method: "POST", body: new FormData(this) })
        .then(response => response.json())
        .then(data => alert(data.success ? "Compte mis à jour !" : "Erreur: " + data.message))
        .catch(error => console.error("Erreur:", error));
});

// Apparence
const darkModeButton = document.getElementById("toggleDarkMode");
darkModeButton.addEventListener("click", function() {
    document.body.classList.toggle("dark-mode");
    this.textContent = document.body.classList.contains("dark-mode") ? "☀️ Mode Clair" : "🌙 Mode Sombre";
});

// Appliquer les changements en temps réel
document.getElementById("themeColor").addEventListener("change", function() {
    document.documentElement.style.setProperty("--main-color", this.value);
});

document.getElementById("fontFamily").addEventListener("change", function() {
    document.body.style.fontFamily = this.value;
});

document.getElementById("fontSize").addEventListener("change", function() {
    document.body.style.fontSize = this.value === "normal" ? "16px" : this.value === "large" ? "18px" : "20px";
});

document.getElementById("saveAppearanceSettings").addEventListener("click", function() {
    const settings = {
        darkMode: document.body.classList.contains("dark-mode") ? "enabled" : "disabled",
        themeColor: document.getElementById("themeColor").value,
        fontFamily: document.getElementById("fontFamily").value,
        fontSize: document.getElementById("fontSize").value,
        showTraffic: document.getElementById("showTraffic").checked,
        showSales: document.getElementById("showSales").checked,
        showOrders: document.getElementById("showOrders").checked
    };

    fetch("admin/settings/update-appearance", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(settings)
    })
    .then(response => {
        if (!response.ok) throw new Error("Erreur réseau : " + response.status);
        return response.json();
    })
    .then(data => {
        console.log("Réponse serveur:", data); // Débogage
        alert(data.success ? data.message : "Erreur: " + data.message);
    })
    .catch(error => {
        console.error("Erreur lors de la sauvegarde:", error);
        alert("Une erreur est survenue : " + error.message);
    });
});

// Sécurité
document.getElementById("passwordForm").addEventListener("submit", function(e) {
    e.preventDefault();
    const newPassword = this.querySelector("[name='new_password']").value;
    const confirmPassword = this.querySelector("[name='confirm_password']").value;
    if (newPassword !== confirmPassword) {
        alert("Les mots de passe ne correspondent pas !");
        return;
    }
    fetch(this.action, { method: "POST", body: new FormData(this) })
        .then(response => response.json())
        .then(data => {
            alert(data.success ? "Mot de passe mis à jour avec succès !" : "Erreur: " + data.message);
            if (data.success) this.reset();
        })
        .catch(error => console.error("Erreur:", error));
});

document.getElementById("deleteAccount").addEventListener("click", function() {
    if (confirm("Sûr de supprimer votre compte ?") && confirm("Action irréversible. Confirmez à nouveau.")) {
        fetch("admin/settings/delete-account", { method: "POST" })
            .then(response => response.json())
            .then(data => {
                if (data.success) window.location.href = "accueil";
                else alert("Erreur: " + data.message);
            });
    }
});

// Notifications
document.getElementById("saveNotifications").addEventListener("click", function() {
    const settings = {
        notifyMessages: document.getElementById("notifyMessages").checked,
        notifyOrders: document.getElementById("notifyOrders").checked,
        notifyReservations: document.getElementById("notifyReservations").checked,
        notifyPackReservations: document.getElementById("notifyPackReservations").checked,
        notifyProductsSoldRented: document.getElementById("notifyProductsSoldRented").checked,
        siteNotifications: document.getElementById("siteNotifications").checked,
        emailFrequency: document.getElementById("emailFrequency").value
    };
    fetch("admin/settings/update-notifications", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(settings)
    })
    .then(response => response.json())
    .then(data => alert(data.success ? "Notifications enregistrées !" : "Erreur: " + data.message))
    .catch(error => console.error("Erreur:", error));
});

// Historique
document.getElementById("refreshHistory").addEventListener("click", function() {
    fetch("admin/settings/history")
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector("#history tbody");
            tbody.innerHTML = data.length ? data.map(entry => `
                <tr class="hover:bg-gray-100">
                    <td class="border p-3">${entry.action_date}</td>
                    <td class="border p-3">${entry.username}</td>
                    <td class="border p-3">${entry.action}</td>
                    <td class="border p-3">${entry.ip_address}</td>
                </tr>`).join("") : '<tr><td colspan="4" class="border p-3 text-gray-500">Aucune action</td></tr>';
        });
});

// Import/Export
document.getElementById("exportUsers").addEventListener("click", () => window.location.href = "admin/settings/export_users");
document.getElementById("exportProducts").addEventListener("click", () => window.location.href = "admin/settings/export_products");
document.getElementById("backupDb").addEventListener("click", () => window.location.href = "admin/settings/backup");
document.getElementById("importForm").addEventListener("submit", function(e) {
    e.preventDefault();
    fetch(this.action, { method: "POST", body: new FormData(this) })
        .then(response => response.json())
        .then(data => alert(data.success ? "Import réussi !" : "Erreur: " + data.message))
        .catch(error => console.error("Erreur:", error));
});
document.getElementById("restoreForm").addEventListener("submit", function(e) {
    e.preventDefault();
    fetch(this.action, { method: "POST", body: new FormData(this) })
        .then(response => response.json())
        .then(data => alert(data.success ? "Restauration réussie !" : "Erreur: " + data.message))
        .catch(error => console.error("Erreur:", error));
});
</script>