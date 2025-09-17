<h2>Connexion</h2>

<form method="POST" action="?controller=auth&action=login">
    <label>
        Email :
        <input type="email" name="email" required>
    </label><br><br>

    <label>
        Mot de passe :
        <input type="password" name="password" required>
    </label><br><br>

    <button type="submit">Se connecter</button>
</form>

<?php if (!empty($error)) : ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
