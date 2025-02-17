
<div class="flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-96">
        <h2 class="text-2xl font-bold mb-6">Connexion</h2>
        <form action="login" method="POST">
            <label class="block">Email</label>
            <input type="email" name="email" class="border px-4 py-2 w-full rounded mb-4" required>

            <label class="block">Mot de passe</label>
            <input type="password" name="password" class="border px-4 py-2 w-full rounded mb-4" required>

            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded">Se connecter</button>
        </form>

        <p class="mt-4 text-center text-sm">
            Pas encore de compte ? <a href="register" class="text-blue-600">Inscrivez-vous</a>
        </p>
    </div>
</div>
