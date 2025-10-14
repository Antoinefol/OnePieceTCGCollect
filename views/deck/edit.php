<section class="deck-section">
  <h2>Deck : <?= htmlspecialchars($deck['name']) ?></h2>
 <a class="btn" href="index.php?controller=deck&action=list">Retour à mes decks</a>
  <div class="deck-leader">
    <p><strong>Leader :</strong> <?= htmlspecialchars($leader['name']) ?> <span>(<?= htmlspecialchars($leader['color']) ?>)</span></p>
  </div>

  <?php if (!empty($_SESSION['flash'])): ?>
    <p class="deck-flash"><?= $_SESSION['flash']; unset($_SESSION['flash']); ?></p>
  <?php endif; ?>

  <h3>Cartes du deck (<?= (int)($deck['card_count'] ?? array_sum(array_column($deck['cards'],'quantity'))) ?>/50)</h3>
   
  <ul class="deck-card-list">
    <?php foreach ($deck['cards'] as $card): ?>
      <li>
        <strong><?= htmlspecialchars($card['name']) ?></strong> 
        <span>x<?= (int)$card['quantity'] ?></span>

        <?php if (is_numeric($card['price'])): ?>
          <small><?= number_format($card['price'], 2, ',', ' ') ?> € / unité → 
          <?= number_format($card['price'] * $card['quantity'], 2, ',', ' ') ?> € total</small>
        <?php else: ?>
          <small>Prix non disponible</small>
        <?php endif; ?>

        <form method="POST" action="index.php?controller=deck&action=removeCard">
          <input type="hidden" name="deck_id" value="<?= htmlspecialchars($deck['id']) ?>">
          <input type="hidden" name="card_id" value="<?= htmlspecialchars($card['id']) ?>">
          <input type="number" name="quantity" value="1" min="1" max="<?= (int)$card['quantity'] ?>">
          <button type="submit">🗑️ Retirer</button>
        </form>
      </li>
    <?php endforeach; ?>
  </ul>

  <p class="deck-value"> Valeur totale du deck : <?= number_format($deck['total_value'], 2, ',', ' ') ?> €</p>

  <h3>Ajouter une carte</h3>


<ul class="card-list">
  <?php foreach ($cards as $card): ?>
    <li>
      <strong><?= htmlspecialchars($card['name']) ?></strong> 
      <span>(<?= htmlspecialchars($card['version']) ?>)</span>

      <img src="./images/cards/<?php echo htmlspecialchars($card['number']) . '-' . htmlspecialchars($card['version']); ?>.png" 
           alt="carte <?php echo htmlspecialchars($card['number']); ?>">

      <form method="POST" action="index.php?controller=deck&action=addCard">
        <input type="hidden" name="deck_id" value="<?= htmlspecialchars($deck['id']) ?>">
        <input type="hidden" name="card_id" value="<?= htmlspecialchars($card['id']) ?>">
        <label>Quantité :</label>
        <input type="number" name="quantity" value="1" min="1" max="4">
        <button type="submit">Ajouter</button>
      </form>
    </li>
  <?php endforeach; ?>
</ul>

</section>