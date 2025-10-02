<section class="centeredWrap">
    <div class="register">
<h2>Connexion</h2>

<form method="POST" action="?controller=auth&action=login">
    <label>
        Email 
        <input type="email" name="email" required>
    </label>
    <label>
        Mot de passe 
        <input type="password" name="password" required>
    </label>
    <div class="submitWrap"><button type="submit">Connexion</button> <div>Pas de compte ? <a href="index.php?controller=auth&action=register">Inscription</a></div></div>
</form>
</div>
</section>
<?php if (!empty($error)) : ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
