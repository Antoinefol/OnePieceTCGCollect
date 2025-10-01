<form method="get" action="index.php">
    <input type="hidden" name="controller" value="card">
    <input type="hidden" name="action" value="list">

    🔍 Recherche : <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">

    🎨 Couleur :
    <select name="color">
        <option value="">Toutes</option>
        <option value="Red"   <?= (($_GET['color'] ?? '') === 'Red') ? 'selected' : '' ?>>Rouge</option>
        <option value="Blue"  <?= (($_GET['color'] ?? '') === 'Blue') ? 'selected' : '' ?>>Bleu</option>
        <option value="Green" <?= (($_GET['color'] ?? '') === 'Green') ? 'selected' : '' ?>>Vert</option>
        <option value="Yellow"<?= (($_GET['color'] ?? '') === 'Yellow') ? 'selected' : '' ?>>Jaune</option>
        <option value="Black" <?= (($_GET['color'] ?? '') === 'Black') ? 'selected' : '' ?>>Noir</option>
    </select>

    📂 Extension :
    <select name="extension">
        <option value="">Toutes</option>
        <option value="OP01" <?= (($_GET['extension'] ?? '') === 'OP01') ? 'selected' : '' ?>>OP01</option>
        <option value="OP02" <?= (($_GET['extension'] ?? '') === 'OP02') ? 'selected' : '' ?>>OP02</option>
        <option value="OP03" <?= (($_GET['extension'] ?? '') === 'OP03') ? 'selected' : '' ?>>OP03</option>
        <!-- ⚠️ à compléter avec tes extensions -->
    </select>

    ⚔️ Type :
    <select name="type">
        <option value="">Tous</option>
        <option value="Leader"     <?= (($_GET['type'] ?? '') === 'Leader') ? 'selected' : '' ?>>Leader</option>
        <option value="Character"  <?= (($_GET['type'] ?? '') === 'Character') ? 'selected' : '' ?>>Character</option>
        <option value="Event"      <?= (($_GET['type'] ?? '') === 'Event') ? 'selected' : '' ?>>Event</option>
        <option value="Stage"      <?= (($_GET['type'] ?? '') === 'Stage') ? 'selected' : '' ?>>Stage</option>
    </select>

    💰 Tri :
    <select name="sort">
        <option value="">Aucun</option>
        <option value="price_asc"  <?= (($_GET['sort'] ?? '') === 'price_asc') ? 'selected' : '' ?>>Prix croissant</option>
        <option value="price_desc" <?= (($_GET['sort'] ?? '') === 'price_desc') ? 'selected' : '' ?>>Prix décroissant</option>
    </select>

    <button type="submit">Filtrer</button>
</form>


<?php foreach ($cards as $card): ?>
    <li>
        <img src="../img/cards/<?php echo htmlspecialchars($card['number']) . '-' . htmlspecialchars($card['version']); ?>.png" alt="">
        <?= htmlspecialchars($card['number']) ?> - 
        <?= htmlspecialchars($card['name']) ?> - 
        <?= htmlspecialchars($card['rarity']) ?> -  
        <?= htmlspecialchars($card['type']) ?> - 
        <?= htmlspecialchars($card['life']) ?> - 
        <?= htmlspecialchars($card['cost']) ?> - 
        <?= htmlspecialchars($card['color']) ?> - 
        <?= htmlspecialchars($card['extension']) ?> - 
        <?= htmlspecialchars($card['version']) ?> - 

        <?php if (!empty($card['price'])): ?>
            Prix : <?= htmlspecialchars($card['price']) ?> $
            <?php if (!empty($card['url'])): ?>
                - <a href="<?= htmlspecialchars($card['url']) ?>" target="_blank">Voir l'annonce</a>
            <?php endif; ?>
        <?php else: ?>
            Prix : Non disponible
        <?php endif; ?>

        <?php if (isset($_SESSION['user'])): ?>
            <form method="POST" action="index.php?controller=card&action=addToCollection" style="display:inline;">
                <input type="hidden" name="card_id" value="<?= htmlspecialchars($card['id']) ?>">
                <button type="submit">Ajouter à ma collection</button>
            </form>
        <?php endif; ?>
    </li>
<?php endforeach; ?>
