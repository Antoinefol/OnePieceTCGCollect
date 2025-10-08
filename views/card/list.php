<form method="get" action="index.php" class="filterForm">
  <input type="hidden" name="controller" value="card">
  <input type="hidden" name="action" value="list">

  <div class="mainFilters">
    <label>Recherche :</label>
    <input type="text" name="search" placeholder="Nom de carte..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">

    <button type="button" id="toggleFilters">+ Plus de filtres</button>
  </div>

    <div class="moreFilters">
        <div class="filterRow">
      <label>Couleur :</label>
      <select name="color">
        <option value="">Toutes</option>
        <option value="Red" <?= (($_GET['color'] ?? '') === 'Red') ? 'selected' : '' ?>>Rouge</option>
        <option value="Blue" <?= (($_GET['color'] ?? '') === 'Blue') ? 'selected' : '' ?>>Bleu</option>
        <option value="Green" <?= (($_GET['color'] ?? '') === 'Green') ? 'selected' : '' ?>>Vert</option>
        <option value="Yellow" <?= (($_GET['color'] ?? '') === 'Yellow') ? 'selected' : '' ?>>Jaune</option>
        <option value="Black" <?= (($_GET['color'] ?? '') === 'Black') ? 'selected' : '' ?>>Noir</option>
      </select>
        </div>

        <div class="filterRow">
      <label>Extension :</label>
      <select name="extension">
        <option value="">Toutes</option>
        <option value="OP01" <?= (($_GET['extension'] ?? '') === 'OP01') ? 'selected' : '' ?>>OP01</option>
        <option value="OP02" <?= (($_GET['extension'] ?? '') === 'OP02') ? 'selected' : '' ?>>OP02</option>
        <option value="OP03" <?= (($_GET['extension'] ?? '') === 'OP03') ? 'selected' : '' ?>>OP03</option>
      </select>
        </div>

        <div class="filterRow">
      <label>Type :</label>
      <select name="type">
        <option value="">Tous</option>
        <option value="Leader" <?= (($_GET['type'] ?? '') === 'Leader') ? 'selected' : '' ?>>Leader</option>
        <option value="Character" <?= (($_GET['type'] ?? '') === 'Character') ? 'selected' : '' ?>>Character</option>
        <option value="Event" <?= (($_GET['type'] ?? '') === 'Event') ? 'selected' : '' ?>>Event</option>
        <option value="Stage" <?= (($_GET['type'] ?? '') === 'Stage') ? 'selected' : '' ?>>Stage</option>
      </select>
        </div>

        <div class="filterRow">
        <label>Tri :</label>
        <select name="sort">
            <option value="">Aucun</option>
            <option value="price_asc" <?= (($_GET['sort'] ?? '') === 'price_asc') ? 'selected' : '' ?>>Prix croissant</option>
            <option value="price_desc" <?= (($_GET['sort'] ?? '') === 'price_desc') ? 'selected' : '' ?>>Prix décroissant</option>
        </select>
        </div>

        <button type="submit" class="applyFilters">Filtrer</button>
    </div>
</form>


<div class="cardWrap">
    <?php foreach ($cards as $card): ?>
    
    <div class="flip-card">
        <div class="flip-card-inner">
            <div class="flip-card-front">
                <img src="./images/cards/<?php echo htmlspecialchars($card['number']) . '-' . htmlspecialchars($card['version']); ?>.png" alt="carte <?php echo htmlspecialchars($card['number']); ?>">
            </div>
        <div class="flip-card-back">
            <h3><?= htmlspecialchars($card['number']) ?>
             <?= htmlspecialchars($card['name']) ?> <?= htmlspecialchars($card['version']) ?></h3>
            <p><?= htmlspecialchars($card['type']) ?> </p>
            <p> <?= htmlspecialchars($card['color']) ?></p>
            <p>Serie: <?= htmlspecialchars($card['extension']) ?></p>
           
<?php if (!empty($card['price'])): ?>
                <p>Prix : <?= htmlspecialchars($card['price']) ?> $</p>
                    <?php if (!empty($card['url'])): ?>
                        <a href="<?= htmlspecialchars($card['url']) ?>" target="_blank">Voir l'annonce</a>
                    <?php endif; ?>
            <?php else: ?>
                Prix : Non disponible
            <?php endif; ?>
            

            <?php if (isset($_SESSION['user'])): ?>
                <form class="addToCollection" method="POST" action="index.php?controller=card&action=addToCollection" style="display:inline;">
                    <input type="hidden" name="card_id" value="<?= htmlspecialchars($card['id']) ?>">
                    <button type="submit" class="addToCollection">Ajouter à ma collection</button>
                </form>
            <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>




