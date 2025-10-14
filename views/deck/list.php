<section class="deck-section">
<div class="profileWrap">
<h2>Mes Decks </h2>
<div class="btnWrap" data-active="<?= htmlspecialchars($_GET['action'] ?? '') ?>">
    <a class="profilebtn <?= ($_GET['action'] ?? '') === 'profile' ? 'active' : '' ?>" 
       href="index.php?controller=user&action=profile">Profile</a>

    <a class="profilebtn <?= ($_GET['action'] ?? '') === 'stats' ? 'active' : '' ?>" 
       href="index.php?controller=card&action=stats">Statistiques</a>

    <a class="profilebtn <?= ($_GET['action'] ?? '') === 'list' ? 'active' : '' ?>" 
       href="index.php?controller=deck&action=list">Decks</a>
  </div>
<a class="deckCreate" href="index.php?controller=deck&action=create">Créer un nouveau deck</a>
</div>



<div class="deckGrid">
  <?php foreach ($decks as $deck): ?>
    <div 
      class="deckCard" 
      data-color="<?= htmlspecialchars($deck['leader_color'] ?? '') ?>"
    >
      <div class="deckHeader">
        <img src="./images/cards/<?php echo htmlspecialchars($deck['leader_number']) . '-' . htmlspecialchars($deck['leader_version']); ?>.png" alt="carte <?php echo htmlspecialchars($deck['leader_number']); ?>">
      </div>

      <div class="deckInfo">
        <h3><?= htmlspecialchars($deck['name']) ?></h3>
        <?php if (!empty($deck['leader_name'])): ?>
          <p><strong>Leader :</strong> <?= htmlspecialchars($deck['leader_name']) ?></p>
        <?php endif; ?>
        <p><?= (int)$deck['card_count'] ?> cartes</p>
      </div>

      <div class="deckActions">
        <a href="index.php?controller=deck&action=edit&id=<?= $deck['id'] ?>" class="editBtn">Modifier</a>
        <form method="POST" action="index.php?controller=deck&action=delete" onsubmit="return confirm('Supprimer ce deck ?')">
          <input type="hidden" name="deck_id" value="<?= $deck['id'] ?>">
          <button type="submit" class="deleteBtn">Supprimer</button>
        </form>
      </div>
    </div>
  <?php endforeach; ?>
</div>

</section>
