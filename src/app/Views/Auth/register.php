
<div class="flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-96">
        <h2 class="text-2xl font-bold mb-6">Inscription</h2>
        <form action="register" method="POST">
            <label class="block">Nom</label>
            <input type="text" name="name" class="border px-4 py-2 w-full rounded mb-4" required>

            <label class="block">Email</label>
            <input type="email" name="email" class="border px-4 py-2 w-full rounded mb-4" required>

            <label class="block">Mot de passe</label>
            <input type="password" name="password" class="border px-4 py-2 w-full rounded mb-4" required>

            <label class="block">Confirmer le mot de passe</label>
            <input type="password" name="confirm_password" class="border px-4 py-2 w-full rounded mb-4" required>

            <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded">S'inscrire</button>
        </form>

        <p class="mt-4 text-center text-sm">
            Déjà un compte ? <a href="login" class="text-blue-600">Connectez-vous</a>
        </p>
    </div>
</div>
