<section class="centeredWrap">
    <div class="register">
        <h2>Inscription</h2>
        <form method="POST" action="?controller=auth&action=register">
            <label>Nom d'utilisateur : <input type="text" name="username" required></label>
            <label>Email : <input type="email" name="email" required></label>
            <label>Mot de passe : <input type="password" name="password" required></label>
            <button type="submit">S'inscrire</button>
        </form>
    </div>
</section>
