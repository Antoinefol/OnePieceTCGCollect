<section class="centeredWrap column">
    <div class="profileWrap">
<h2 class="profileName">Profil de <?= htmlspecialchars($user['username']) ?></h2>
<div>
<a class="profilebtn" href="index.php?controller=card&action=stats">Statistiques</a>
<a class="profilebtn" href="index.php?controller=deck&action=list">Mes Decks</a></div>
</div>
<h3>Ma collection de cartes</h3>

<?php if (empty($cards)) : ?>
    <p>Aucune collection enregistrée pour le moment.</p>
<?php else : ?>
    <?php $total = 0; ?>
    <div class="cards-grid">
        <?php foreach ($cards as $card) : ?>
            <?php if (is_numeric($card['price'])) : ?>
                <?php $total += $card['price'] * (int)$card['quantity']; ?>
            <?php endif; ?>

            <div class="card-wrapper">
                <div class="sleeve"></div>
                <div class="collectionFlipCard"
                     data-id="<?= htmlspecialchars($card['id']) ?>"
                     data-name="<?= htmlspecialchars($card['name']) ?>"
                     data-type="<?= htmlspecialchars($card['type']) ?>"
                     data-color="<?= htmlspecialchars($card['color']) ?>"
                     data-life="<?= htmlspecialchars($card['Life']) ?>"
                     data-rarity="<?= htmlspecialchars($card['rarity']) ?>"
                     data-quantity="<?= htmlspecialchars($card['quantity']) ?>"
                     data-price="<?= is_numeric($card['price']) ? htmlspecialchars($card['price']) . ' €' : htmlspecialchars($card['price']) ?>">
                    <div class="collectionFlipCardInner">
                        <div class="collectionFlipCardFront">
                            <img src="./images/cards/<?= htmlspecialchars($card['number']) . '-' . htmlspecialchars($card['version']); ?>.png"
                                 alt="carte <?= htmlspecialchars($card['number']); ?>">
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <h3 class="totalValue">Valeur totale de ma collection : <?= number_format($total, 2, ',', ' ') ?> $</h3>
<?php endif; ?>

</section>