<header>
    <a href="index.php"><img class="logo" src="./images/assets/logo.png" alt="logo" class="logo"></a>


   
        <a href="index.php">Accueil</a>
        <a href="?controller=card&action=list">Voir les cartes</a>
        <?php if (isset($_SESSION['user'])): ?>
    <a href="index.php?controller=user&action=profile">
        <i class="fa-regular fa-user"></i>
    </a>
<?php else: ?>
    <a href="index.php?controller=auth&action=login">
        <i class="fa-regular fa-user"></i>
    </a>
<?php endif; ?>

   
</header>
