<h2>Deck : <?= htmlspecialchars($deck['name']) ?></h2>
<p><strong>Leader :</strong> <?= htmlspecialchars($leader['name']) ?> (<?= htmlspecialchars($leader['color']) ?>)</p>

<?php if (!empty($_SESSION['flash'])): ?>
    <p style="color:red;"><?= $_SESSION['flash']; unset($_SESSION['flash']); ?></p>
<?php endif; ?>

<h3>Cartes du deck</h3>
<ul>
    <?php foreach ($deck['cards'] as $card): ?>
        <li>
            <?= htmlspecialchars($card['name']) ?> x<?= $card['quantity'] ?>

            <!-- Formulaire pour supprimer une carte -->
            <form method="POST" action="index.php?controller=deck&action=removeCard" style="display:inline;">
                <input type="hidden" name="deck_id" value="<?= htmlspecialchars($deck['id']) ?>">
                <input type="hidden" name="card_id" value="<?= htmlspecialchars($card['id']) ?>">
                <label>Quantité à retirer :</label>
                <input type="number" name="quantity" value="1" min="1" max="<?= $card['quantity'] ?>">
                <button type="submit">Retirer</button>
            </form>
        </li>
    <?php endforeach; ?>
</ul>

<h3>Ajouter une carte</h3>
<ul>
    <?php foreach ($cards as $card): ?>
        <li>
            <?= htmlspecialchars($card['name']) ?> (<?= htmlspecialchars($card['rarity']) ?>)

            <!-- Formulaire pour ajouter cette carte -->
            <form method="POST" action="index.php?controller=deck&action=addCard" style="display:inline;">
                <input type="hidden" name="deck_id" value="<?= htmlspecialchars($deck['id']) ?>">
                <input type="hidden" name="card_id" value="<?= htmlspecialchars($card['id']) ?>">
                <label>Quantité :</label>
                <input type="number" name="quantity" value="1" min="1" max="4">
                <button type="submit">Ajouter</button>
            </form>
        </li>
    <?php endforeach; ?>
</ul>

<a href="index.php?controller=deck&action=list">Retour à mes decks</a>
