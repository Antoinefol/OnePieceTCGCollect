<h2>Mes decks</h2>

<a href="index.php?controller=deck&action=create">Créer un nouveau deck</a>

<ul>
<?php foreach ($decks as $deck): ?>
    <li>
        <?= htmlspecialchars($deck['name']) ?> 
        (<?= $deck['card_count'] ?> cartes)
        

        <a href="index.php?controller=deck&action=edit&id=<?= $deck['id'] ?>">✏️ Modifier</a>

        <form method="POST" action="index.php?controller=deck&action=delete" style="display:inline;">
            <input type="hidden" name="deck_id" value="<?= $deck['id'] ?>">
            <button type="submit" onclick="return confirm('Supprimer ce deck ?')">🗑️ Supprimer</button>
        </form>
    </li>
<?php endforeach; ?>
</ul>
