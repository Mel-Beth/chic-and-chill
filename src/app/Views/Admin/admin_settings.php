<?php
include('src/app/Views/includes/admin_head.php');
include('src/app/Views/includes/admin_header.php');
include('src/app/Views/includes/admin_sidebar.php');
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
                        <li><button class="tab-button" data-target="billing">Facturation & Abonnement</button></li>
                        <li><button class="tab-button" data-target="language">Langue & Localisation</button></li>
                        <li><button class="tab-button" data-target="integrations">Intégrations</button></li>
                        <li><button class="tab-button" data-target="history">Historique des Actions</button></li>
                        <li><button class="tab-button" data-target="data">Import/Export des Données</button></li>
                        <li><button class="tab-button" data-target="reset">Réinitialisation & Réparation</button></li>
                    </ul>
                </div>

                <!-- Contenu dynamique -->
                <div class="col-span-3">
                    <!-- Mon Compte -->
                    <div id="account" class="settings-content">
                        <h3 class="text-2xl font-semibold mb-4">Mon Compte</h3>

                        <!-- Modification des informations du compte -->
                        <form action="admin/settings/update" method="POST">
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

                        <!-- Mode Sombre / Mode Clair -->
                        <label class="block text-gray-600 mb-2">Mode Sombre / Clair</label>
                        <button id="toggleDarkMode" class="border px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300">🌙 Mode Sombre</button>

                        <!-- Couleur principale -->
                        <label class="block text-gray-600 mt-4">🎨 Couleur principale</label>
                        <select id="themeColor" class="border px-4 py-2 rounded-md w-full">
                            <option value="blue">Bleu</option>
                            <option value="red">Rouge</option>
                            <option value="green">Vert</option>
                            <option value="purple">Violet</option>
                            <option value="orange">Orange</option>
                        </select>

                        <!-- Police d'écriture -->
                        <label class="block text-gray-600 mt-4">🔤 Police d'écriture</label>
                        <select id="fontFamily" class="border px-4 py-2 rounded-md w-full">
                            <option value="sans-serif">Sans-serif (Par défaut)</option>
                            <option value="serif">Serif</option>
                            <option value="monospace">Monospace</option>
                        </select>

                        <!-- Taille du texte -->
                        <label class="block text-gray-600 mt-4">📏 Taille du texte</label>
                        <select id="fontSize" class="border px-4 py-2 rounded-md w-full">
                            <option value="normal">Normal</option>
                            <option value="large">Grand</option>
                            <option value="x-large">Extra-large</option>
                        </select>

                        <!-- Affichage des statistiques -->
                        <label class="block text-gray-600 mt-4">📶 Affichage des statistiques</label>
                        <div class="flex space-x-4">
                            <label class="flex items-center">
                                <input type="checkbox" id="showTraffic" checked class="mr-2"> Trafic
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" id="showSales" checked class="mr-2"> Ventes
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" id="showOrders" checked class="mr-2"> Commandes
                            </label>
                        </div>

                        <!-- Sauvegarde -->
                        <button id="saveAppearanceSettings" class="mt-4 bg-blue-600 text-white px-6 py-3 rounded-md hover:scale-105 transition">
                            💾 Sauvegarder
                        </button>
                    </div>

                    <!-- Sécurité & Confidentialité -->
                    <div id="security" class="settings-content hidden">
                        <h3 class="text-2xl font-semibold mb-4">🔒 Sécurité & Confidentialité</h3>

                        <!-- Changer le mot de passe -->
                        <label class="block text-gray-600">🔐 Changer le mot de passe</label>
                        <form action="admin/settings/update-password" method="POST">
                            <input type="password" name="current_password" placeholder="Mot de passe actuel" class="border px-4 py-2 rounded-md w-full mt-2">
                            <input type="password" name="new_password" placeholder="Nouveau mot de passe" class="border px-4 py-2 rounded-md w-full mt-2">
                            <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" class="border px-4 py-2 rounded-md w-full mt-2">
                            <button type="submit" class="mt-4 bg-black text-white px-6 py-3 rounded-md hover:scale-105 transition">Mettre à jour</button>
                        </form>

                        <!-- 2FA -->
                        <label class="block text-gray-600 mt-6">✅ Authentification à Deux Facteurs</label>
                        <button id="toggle2FA" class="border px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300 mt-2">Activer 2FA</button>

                        <!-- Déconnexion automatique -->
                        <label class="block text-gray-600 mt-6">⏳ Déconnexion automatique</label>
                        <select id="autoLogout" class="border px-4 py-2 rounded-md w-full">
                            <option value="never">Jamais</option>
                            <option value="5">Après 5 minutes</option>
                            <option value="15">Après 15 minutes</option>
                            <option value="30">Après 30 minutes</option>
                        </select>

                        <!-- Notifications connexion suspecte -->
                        <label class="block text-gray-600 mt-6">📧 Notifications de connexion suspecte</label>
                        <label class="flex items-center mt-2">
                            <input type="checkbox" id="notifyLogin" checked class="mr-2"> Recevoir un e-mail en cas de connexion suspecte
                        </label>

                        <!-- Suppression du compte -->
                        <label class="block text-red-600 mt-6">🗑️ Suppression du compte</label>
                        <button id="deleteAccount" class="border px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700 mt-2">Supprimer définitivement</button>
                    </div>

                    <!-- Notifications -->
                    <div id="notifications" class="settings-content hidden">
                        <h3 class="text-2xl font-semibold mb-4">📢 Notifications</h3>

                        <!-- Notifications Email -->
                        <label class="block text-gray-600">📩 Recevoir des notifications par e-mail pour :</label>
                        <label class="flex items-center mt-2">
                            <input type="checkbox" id="notifyMessages" class="mr-2"> Nouveaux messages de contact
                        </label>
                        <label class="flex items-center mt-2">
                            <input type="checkbox" id="notifyOrders" class="mr-2"> Nouvelles commandes
                        </label>
                        <label class="flex items-center mt-2">
                            <input type="checkbox" id="notifyReservations" class="mr-2"> Nouvelles réservations d’événements
                        </label>
                        <label class="flex items-center mt-2">
                            <input type="checkbox" id="notifyPackReservations" class="mr-2"> Nouvelles réservations de packs
                        </label>
                        <label class="flex items-center mt-2">
                            <input type="checkbox" id="notifyProductsSoldRented" class="mr-2"> Produits achetés ou loués (Idée de tenue)
                        </label>

                        <!-- Notifications Site -->
                        <label class="block text-gray-600 mt-6">🔔 Notifications sur le site</label>
                        <label class="flex items-center mt-2">
                            <input type="checkbox" id="siteNotifications" class="mr-2"> Activer les notifications sur le site
                        </label>

                        <!-- Fréquence des emails -->
                        <label class="block text-gray-600 mt-6">📅 Fréquence des notifications par e-mail</label>
                        <select id="emailFrequency" class="border px-4 py-2 rounded-md w-full">
                            <option value="immediate">Immédiatement</option>
                            <option value="daily">Une fois par jour</option>
                            <option value="weekly">Une fois par semaine</option>
                        </select>

                        <!-- Bouton Sauvegarde -->
                        <button id="saveNotifications" class="mt-4 bg-black text-white px-6 py-3 rounded-md hover:scale-105 transition">
                            Enregistrer les préférences
                        </button>
                    </div>

                    <!-- Facturation & Abonnement -->
                    <div id="billing" class="settings-content hidden">
                        <h3 class="text-2xl font-semibold mb-4">💳 Facturation & Abonnement</h3>

                        <!-- Voir Factures -->
                        <label class="block text-gray-600">🧾 Voir les factures des abonnements en cours :</label>
                        <button id="viewInvoices" class="mt-2 border px-4 py-2 rounded-md">📄 Voir mes factures</button>

                        <!-- Mode de paiement -->
                        <label class="block text-gray-600 mt-6">💰 Mode de paiement enregistré :</label>
                        <select id="paymentMethod" class="border px-4 py-2 rounded-md w-full">
                            <option value="stripe">💳 Carte Bancaire (Stripe)</option>
                            <option value="paypal">🅿️ PayPal</option>
                            <option value="cb">🏦 Virement Bancaire</option>
                        </select>

                        <!-- Annulation abonnement -->
                        <label class="block text-gray-600 mt-6">🛑 Annuler mon abonnement :</label>
                        <button id="cancelSubscription" class="mt-2 bg-red-500 text-white px-4 py-2 rounded-md">❌ Annuler mon abonnement</button>

                        <!-- Codes promo -->
                        <label class="block text-gray-600 mt-6">🏷️ Gérer les codes promo :</label>
                        <input type="text" id="promoCode" placeholder="Entrez un code promo" class="border px-4 py-2 rounded-md w-full">
                        <button id="applyPromo" class="mt-2 bg-green-500 text-white px-4 py-2 rounded-md">✔️ Appliquer</button>
                    </div>

                    <!-- Langue & Localisation -->
                    <div id="language" class="settings-content hidden">
                        <h3 class="text-2xl font-semibold mb-4">🌍 Langue & Localisation</h3>

                        <form action="admin/settings/update-language" method="POST">
                            <!-- Sélection de la langue -->
                            <label class="block text-gray-600">🌐 Langue de l'interface</label>
                            <select name="language" class="border px-4 py-2 rounded-md w-full">
                                <option value="fr" <?= ($settings['language'] ?? '') === 'fr' ? 'selected' : '' ?>>Français</option>
                                <option value="en" <?= ($settings['language'] ?? '') === 'en' ? 'selected' : '' ?>>Anglais</option>
                                <option value="es" <?= ($settings['language'] ?? '') === 'es' ? 'selected' : '' ?>>Espagnol</option>
                            </select>

                            <!-- Sélection du fuseau horaire -->
                            <label class="block text-gray-600 mt-4">⏰ Fuseau horaire</label>
                            <select name="timezone" class="border px-4 py-2 rounded-md w-full">
                                <option value="UTC-5" <?= ($settings['timezone'] ?? '') === 'UTC-5' ? 'selected' : '' ?>>(GMT-5) New York</option>
                                <option value="UTC+0" <?= ($settings['timezone'] ?? '') === 'UTC+0' ? 'selected' : '' ?>>(GMT) Londres</option>
                                <option value="UTC+1" <?= ($settings['timezone'] ?? '') === 'UTC+1' ? 'selected' : '' ?>>(GMT+1) Paris</option>
                                <option value="UTC+3" <?= ($settings['timezone'] ?? '') === 'UTC+3' ? 'selected' : '' ?>>(GMT+3) Moscou</option>
                                <option value="UTC-3" <?= ($settings['timezone'] ?? '') === 'UTC-3' ? 'selected' : '' ?>>(GMT-3) Buenos Aires</option>
                            </select>

                            <!-- Sélection du pays -->
                            <label class="block text-gray-600 mt-4">🗺️ Pays</label>
                            <select name="country" class="border px-4 py-2 rounded-md w-full">
                                <option value="FR" <?= ($settings['country'] ?? '') === 'FR' ? 'selected' : '' ?>>France</option>
                                <option value="US" <?= ($settings['country'] ?? '') === 'US' ? 'selected' : '' ?>>États-Unis</option>
                                <option value="ES" <?= ($settings['country'] ?? '') === 'ES' ? 'selected' : '' ?>>Espagne</option>
                                <option value="CO" <?= ($settings['country'] ?? '') === 'CO' ? 'selected' : '' ?>>Colombie</option>
                                <option value="DE" <?= ($settings['country'] ?? '') === 'DE' ? 'selected' : '' ?>>Allemagne</option>
                            </select>

                            <!-- Bouton de mise à jour -->
                            <button type="submit" class="mt-4 bg-black text-white px-6 py-3 rounded-md hover:scale-105 transition">Mettre à jour</button>
                        </form>
                    </div>

                    <!-- Intégrations -->
                    <div id="integrations" class="settings-content hidden">
                        <h3 class="text-2xl font-semibold mb-4">⚙️ Intégrations</h3>

                        <form action="admin/settings/update-integrations" method="POST">
                            <!-- Google Analytics -->
                            <label class="block text-gray-600">📊 Google Analytics API Key</label>
                            <input type="text" name="google_analytics" value="<?= htmlspecialchars($settings['google_analytics'] ?? '') ?>" class="border px-4 py-2 rounded-md w-full" placeholder="Entrez votre clé API Google Analytics">

                            <!-- Mailchimp / SendinBlue -->
                            <label class="block text-gray-600 mt-4">📧 API Email Marketing (Mailchimp / SendinBlue)</label>
                            <input type="text" name="email_api" value="<?= htmlspecialchars($settings['email_api'] ?? '') ?>" class="border px-4 py-2 rounded-md w-full" placeholder="Entrez votre clé API Mailchimp / SendinBlue">

                            <!-- Stripe / PayPal -->
                            <label class="block text-gray-600 mt-4">💳 Paiements (Stripe / PayPal)</label>
                            <select name="payment_provider" class="border px-4 py-2 rounded-md w-full">
                                <option value="stripe" <?= ($settings['payment_provider'] ?? '') === 'stripe' ? 'selected' : '' ?>>Stripe</option>
                                <option value="paypal" <?= ($settings['payment_provider'] ?? '') === 'paypal' ? 'selected' : '' ?>>PayPal</option>
                            </select>
                            <input type="text" name="payment_api" value="<?= htmlspecialchars($settings['payment_api'] ?? '') ?>" class="border px-4 py-2 rounded-md w-full mt-2" placeholder="Entrez votre clé API Stripe / PayPal">

                            <!-- Zapier / Webhooks -->
                            <label class="block text-gray-600 mt-4">🚀 Webhooks (Zapier / Automatisation)</label>
                            <input type="text" name="webhook_url" value="<?= htmlspecialchars($settings['webhook_url'] ?? '') ?>" class="border px-4 py-2 rounded-md w-full" placeholder="Entrez l'URL Webhook Zapier">

                            <!-- Bouton de mise à jour -->
                            <button type="submit" class="mt-4 bg-black text-white px-6 py-3 rounded-md hover:scale-105 transition">Enregistrer les intégrations</button>
                        </form>
                    </div>

                    <!-- Historique des Actions -->
                    <div id="history" class="settings-content hidden">
                        <h3 class="text-2xl font-semibold mb-4">📚 Historique des Actions</h3>

                        <!-- Vérification si des actions sont disponibles -->
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

                        <!-- Bouton de rafraîchissement -->
                        <button id="refreshHistory" class="mt-4 bg-gray-200 px-4 py-2 rounded-md hover:bg-gray-300">🔄 Rafraîchir</button>
                    </div>

                    <!-- Import/Export des Données -->
                    <div id="data" class="settings-content hidden">
                        <h3 class="text-2xl font-semibold mb-4">🔗 Import/Export des Données</h3>

                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <!-- Export des utilisateurs -->
                            <h4 class="text-lg font-semibold mb-2">📤 Exporter les Données</h4>
                            <button id="exportUsers" class="border px-4 py-2 rounded-md bg-blue-500 text-white hover:bg-blue-600">
                                Exporter les utilisateurs (CSV)
                            </button>
                            <button id="exportProducts" class="border px-4 py-2 rounded-md bg-blue-500 text-white hover:bg-blue-600">
                                Exporter les produits (CSV)
                            </button>

                            <hr class="my-4">

                            <!-- Import des fichiers CSV -->
                            <h4 class="text-lg font-semibold mb-2">📥 Importer des Données</h4>
                            <form id="importForm" action="admin/settings/import" method="POST" enctype="multipart/form-data">
                                <input type="file" name="importFile" accept=".csv" class="border px-4 py-2 rounded-md w-full">
                                <button type="submit" class="mt-2 border px-4 py-2 rounded-md bg-green-500 text-white hover:bg-green-600">
                                    Importer un fichier CSV
                                </button>
                            </form>

                            <hr class="my-4">

                            <!-- Sauvegarde et restauration -->
                            <h4 class="text-lg font-semibold mb-2">🔄 Sauvegarde & Restauration</h4>
                            <button id="backupDb" class="border px-4 py-2 rounded-md bg-gray-500 text-white hover:bg-gray-600">
                                Sauvegarder la base de données
                            </button>
                            <form id="restoreForm" action="admin/settings/restore" method="POST" enctype="multipart/form-data">
                                <input type="file" name="backupFile" accept=".sql" class="border px-4 py-2 rounded-md w-full">
                                <button type="submit" class="mt-2 border px-4 py-2 rounded-md bg-red-500 text-white hover:bg-red-600">
                                    Restaurer une sauvegarde
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Réinitialisation & Réparation -->
                    <div id="reset" class="settings-content hidden">
                        <h3 class="text-2xl font-semibold mb-4">🔄 Réinitialisation & Réparation</h3>

                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <!-- Réinitialisation du cache -->
                            <h4 class="text-lg font-semibold mb-2">🚀 Optimisation du site</h4>
                            <button id="resetCache" class="border px-4 py-2 rounded-md bg-blue-500 text-white hover:bg-blue-600">
                                Réinitialiser le cache
                            </button>

                            <hr class="my-4">

                            <!-- Mise à jour des statistiques -->
                            <h4 class="text-lg font-semibold mb-2">📊 Mise à jour des statistiques</h4>
                            <button id="updateStats" class="border px-4 py-2 rounded-md bg-green-500 text-white hover:bg-green-600">
                                Mettre à jour les statistiques
                            </button>

                            <hr class="my-4">

                            <!-- Nettoyage des anciennes commandes -->
                            <h4 class="text-lg font-semibold mb-2">🗑️ Nettoyage des anciennes commandes</h4>
                            <button id="cleanOrders" class="border px-4 py-2 rounded-md bg-red-500 text-white hover:bg-red-600">
                                Supprimer les commandes inactives
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Gestion des onglets
    document.querySelectorAll(".tab-button").forEach(button => {
        button.addEventListener("click", function() {
            document.querySelectorAll(".settings-content").forEach(content => content.classList.add("hidden"));
            document.getElementById(this.dataset.target).classList.remove("hidden");
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        // Mode Sombre / Clair
        const darkModeButton = document.getElementById("toggleDarkMode");
        const body = document.body;

        function toggleDarkMode() {
            body.classList.toggle("dark-mode");
            localStorage.setItem("darkMode", body.classList.contains("dark-mode") ? "enabled" : "disabled");
            darkModeButton.textContent = body.classList.contains("dark-mode") ? "☀️ Mode Clair" : "🌙 Mode Sombre";
        }

        // Appliquer le mode sombre s'il est activé
        if (localStorage.getItem("darkMode") === "enabled") {
            body.classList.add("dark-mode");
            darkModeButton.textContent = "☀️ Mode Clair";
        }

        darkModeButton.addEventListener("click", toggleDarkMode);

        // Changer la couleur principale
        const themeColorSelect = document.getElementById("themeColor");
        themeColorSelect.value = localStorage.getItem("themeColor") || "blue";

        themeColorSelect.addEventListener("change", function() {
            document.documentElement.style.setProperty("--main-color", this.value);
            localStorage.setItem("themeColor", this.value);
        });

        // Changer la police
        const fontFamilySelect = document.getElementById("fontFamily");
        fontFamilySelect.value = localStorage.getItem("fontFamily") || "sans-serif";

        fontFamilySelect.addEventListener("change", function() {
            document.body.style.fontFamily = this.value;
            localStorage.setItem("fontFamily", this.value);
        });

        // Changer la taille du texte
        const fontSizeSelect = document.getElementById("fontSize");
        fontSizeSelect.value = localStorage.getItem("fontSize") || "normal";

        fontSizeSelect.addEventListener("change", function() {
            document.body.style.fontSize = this.value;
            localStorage.setItem("fontSize", this.value);
        });

        // Affichage des statistiques
        const showTraffic = document.getElementById("showTraffic");
        const showSales = document.getElementById("showSales");
        const showOrders = document.getElementById("showOrders");

        function toggleStat(stat, checkbox) {
            const isVisible = checkbox.checked;
            localStorage.setItem(stat, isVisible ? "visible" : "hidden");
            document.querySelector(`.${stat}`).style.display = isVisible ? "block" : "none";
        }

        [showTraffic, showSales, showOrders].forEach((checkbox) => {
            checkbox.checked = localStorage.getItem(checkbox.id) !== "hidden";
            checkbox.addEventListener("change", function() {
                toggleStat(this.id, this);
            });
        });

        // Sauvegarde des préférences
        document.getElementById("saveAppearanceSettings").addEventListener("click", function() {
            alert("Préférences d'apparence enregistrées avec succès !");
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        // Changer le mot de passe : validation
        document.querySelector("form[action='admin/settings/update-password']").addEventListener("submit", function(event) {
            const newPassword = document.querySelector("input[name='new_password']").value;
            const confirmPassword = document.querySelector("input[name='confirm_password']").value;

            if (newPassword !== confirmPassword) {
                alert("Les mots de passe ne correspondent pas !");
                event.preventDefault();
            }
        });

        // Activer/Désactiver l'authentification à deux facteurs (2FA)
        const toggle2FAButton = document.getElementById("toggle2FA");
        let is2FAEnabled = localStorage.getItem("2FA") === "enabled";

        function update2FAButton() {
            toggle2FAButton.textContent = is2FAEnabled ? "Désactiver 2FA" : "Activer 2FA";
        }

        toggle2FAButton.addEventListener("click", function() {
            is2FAEnabled = !is2FAEnabled;
            localStorage.setItem("2FA", is2FAEnabled ? "enabled" : "disabled");
            update2FAButton();
        });

        update2FAButton();

        // Déconnexion automatique
        const autoLogoutSelect = document.getElementById("autoLogout");
        autoLogoutSelect.value = localStorage.getItem("autoLogout") || "never";

        autoLogoutSelect.addEventListener("change", function() {
            localStorage.setItem("autoLogout", this.value);
        });

        // Notifications de connexion suspecte
        const notifyLoginCheckbox = document.getElementById("notifyLogin");
        notifyLoginCheckbox.checked = localStorage.getItem("notifyLogin") !== "false";

        notifyLoginCheckbox.addEventListener("change", function() {
            localStorage.setItem("notifyLogin", this.checked);
        });

        // Suppression du compte avec double confirmation
        document.getElementById("deleteAccount").addEventListener("click", function() {
            if (confirm("Êtes-vous sûr de vouloir supprimer définitivement votre compte ?")) {
                if (confirm("⚠️ Cette action est irréversible ! Confirmez à nouveau.")) {
                    window.location.href = "admin/settings/delete-account";
                }
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        // Charger les préférences enregistrées
        function loadNotificationSettings() {
            document.getElementById("notifyMessages").checked = localStorage.getItem("notifyMessages") === "true";
            document.getElementById("notifyOrders").checked = localStorage.getItem("notifyOrders") === "true";
            document.getElementById("notifyReservations").checked = localStorage.getItem("notifyReservations") === "true";
            document.getElementById("notifyPackReservations").checked = localStorage.getItem("notifyPackReservations") === "true";
            document.getElementById("notifyProductsSoldRented").checked = localStorage.getItem("notifyProductsSoldRented") === "true";
            document.getElementById("siteNotifications").checked = localStorage.getItem("siteNotifications") === "true";
            document.getElementById("emailFrequency").value = localStorage.getItem("emailFrequency") || "immediate";
        }

        loadNotificationSettings();

        // Sauvegarder les préférences
        document.getElementById("saveNotifications").addEventListener("click", function() {
            let settings = {
                notifyMessages: document.getElementById("notifyMessages").checked,
                notifyOrders: document.getElementById("notifyOrders").checked,
                notifyReservations: document.getElementById("notifyReservations").checked,
                notifyPackReservations: document.getElementById("notifyPackReservations").checked,
                notifyProductsSoldRented: document.getElementById("notifyProductsSoldRented").checked,
                siteNotifications: document.getElementById("siteNotifications").checked,
                emailFrequency: document.getElementById("emailFrequency").value
            };

            // Stockage local
            for (let key in settings) {
                localStorage.setItem(key, settings[key]);
            }

            // Envoi des préférences au serveur
            fetch("admin/settings/update-notifications", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(settings)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Préférences enregistrées !");
                    } else {
                        alert("Erreur lors de l'enregistrement.");
                    }
                })
                .catch(error => console.error("Erreur :", error));
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        // Voir les factures
        document.getElementById("viewInvoices").addEventListener("click", function() {
            window.location.href = "admin/billing/invoices";
        });

        // Annuler l’abonnement
        document.getElementById("cancelSubscription").addEventListener("click", function() {
            if (confirm("Êtes-vous sûr de vouloir annuler votre abonnement ? Cette action est irréversible.")) {
                fetch("admin/billing/cancel-subscription", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("Votre abonnement a été annulé.");
                            location.reload();
                        } else {
                            alert("Erreur lors de l’annulation.");
                        }
                    })
                    .catch(error => console.error("Erreur :", error));
            }
        });

        // Appliquer un code promo
        document.getElementById("applyPromo").addEventListener("click", function() {
            let promoCode = document.getElementById("promoCode").value;
            if (!promoCode) {
                alert("Veuillez entrer un code promo.");
                return;
            }

            fetch("admin/billing/apply-promo", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        promoCode: promoCode
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Code promo appliqué avec succès !");
                    } else {
                        alert("Code promo invalide ou expiré.");
                    }
                })
                .catch(error => console.error("Erreur :", error));
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        const settingsForm = document.querySelector("form[action='admin/settings/update-language']");

        settingsForm.addEventListener("submit", function(event) {
            event.preventDefault();

            let formData = new FormData(this);

            fetch("admin/settings/update-language", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Langue et localisation mises à jour !");
                    } else {
                        alert("Erreur lors de la mise à jour.");
                    }
                });
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        const settingsForm = document.querySelector("form[action='admin/settings/update-integrations']");

        settingsForm.addEventListener("submit", function(event) {
            event.preventDefault();

            let formData = new FormData(this);

            fetch("admin/settings/update-integrations", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Les intégrations ont été mises à jour !");
                    } else {
                        alert("Erreur lors de la mise à jour.");
                    }
                });
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("refreshHistory").addEventListener("click", function() {
            fetch("admin/settings/history")
                .then(response => response.json())
                .then(data => {
                    let tableBody = document.querySelector("#history tbody");
                    tableBody.innerHTML = ""; // Efface le contenu existant

                    if (data.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="4" class="border p-3 text-gray-500">Aucune action enregistrée.</td></tr>';
                        return;
                    }

                    data.forEach(entry => {
                        let row = document.createElement("tr");
                        row.innerHTML = `
                        <td class="border p-3">${entry.action_date}</td>
                        <td class="border p-3">${entry.username}</td>
                        <td class="border p-3">${entry.action}</td>
                        <td class="border p-3">${entry.ip_address}</td>
                    `;
                        tableBody.appendChild(row);
                    });
                })
                .catch(error => console.error("Erreur récupération historique:", error));
        });
    });

    document.getElementById('exportUsers').addEventListener('click', function() {
        window.location.href = "admin/settings/export_users";
    });

    document.getElementById('exportProducts').addEventListener('click', function() {
        window.location.href = "admin/settings/export_products";
    });

    document.getElementById('backupDb').addEventListener('click', function() {
        window.location.href = "admin/settings/backup";
    });

    document.getElementById('resetCache').addEventListener('click', function() {
        fetch("admin/settings/reset_cache", {
                method: "POST"
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) alert("Cache réinitialisé !");
            });
    });

    document.getElementById('updateStats').addEventListener('click', function() {
        fetch("admin/settings/update_stats", {
                method: "POST"
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) alert("Statistiques mises à jour !");
            });
    });

    document.getElementById('cleanOrders').addEventListener('click', function() {
        fetch("admin/settings/clean_orders", {
                method: "POST"
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) alert("Commandes inactives supprimées !");
            });
    });
</script>