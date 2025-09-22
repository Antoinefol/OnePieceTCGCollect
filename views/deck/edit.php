<h2>Deck : <?= htmlspecialchars($deck['name']) ?></h2>
<p><strong>Leader :</strong> <?= htmlspecialchars($leader['name']) ?> (<?= htmlspecialchars($leader['color']) ?>)</p>

<?php if (!empty($_SESSION['flash'])): ?>
    <p style="color:green;"><?= $_SESSION['flash']; unset($_SESSION['flash']); ?></p>
<?php endif; ?>

<h3>Cartes du deck (<?= (int)($deck['card_count'] ?? array_sum(array_column($deck['cards'],'quantity'))) ?>/50)</h3>
<ul>
    <?php foreach ($deck['cards'] as $card): ?>
        <li>
            <?= htmlspecialchars($card['name']) ?> x<?= (int)$card['quantity'] ?>

            <?php if (is_numeric($card['price'])): ?>
                - <?= number_format($card['price'], 2, ',', ' ') ?> € / unité 
                → <?= number_format($card['price'] * $card['quantity'], 2, ',', ' ') ?> € total
            <?php else: ?>
                - Prix non dispo
            <?php endif; ?>

            <form method="POST" action="index.php?controller=deck&action=removeCard" style="display:inline;">
                <input type="hidden" name="deck_id" value="<?= htmlspecialchars($deck['id']) ?>">
                <input type="hidden" name="card_id" value="<?= htmlspecialchars($card['id']) ?>">
                <input type="number" name="quantity" value="1" min="1" max="<?= (int)$card['quantity'] ?>" style="width:60px;">
                <button type="submit">Retirer</button>
            </form>
        </li>
    <?php endforeach; ?>
</ul>

<h3>💰 Valeur totale du deck : <?= number_format($deck['total_value'], 2, ',', ' ') ?> €</h3>

<h3>Ajouter une carte</h3>
<ul>
    <?php foreach ($cards as $card): ?>
        <li>
            <?= htmlspecialchars($card['name']) ?> (<?= htmlspecialchars($card['rarity']) ?>)

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
