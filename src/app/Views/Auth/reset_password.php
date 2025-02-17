<form action="reset-password" method="POST">
    <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token']) ?>">
    
    <label>Nouveau mot de passe</label>
    <input type="password" name="password" required>

    <label>Confirmer le mot de passe</label>
    <input type="password" name="confirm_password" required>

    <button type="submit">Réinitialiser</button>
</form>
