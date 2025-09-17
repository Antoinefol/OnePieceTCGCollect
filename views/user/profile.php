<h2>Profil de <?= htmlspecialchars($user['username']) ?></h2>

<h3>Ma collection de cartes</h3>
<pre><?php var_dump($cards); ?></pre>
<?php if (empty($cards)) : ?>
    <p>Aucune collection enregistrée pour le moment.</p>
<?php else : ?>

    <?php $total = 0; ?>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Type</th>
                <th>Couleur</th>
                <th>Vie</th>
                <th>Rareté</th>
                <th>Quantité</th>
                <th>Prix</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cards as $card) : ?>
                <tr>
                    <td><?= htmlspecialchars($card['name']) ?></td>
                    <td><?= htmlspecialchars($card['type']) ?></td>
                    <td><?= htmlspecialchars($card['color']) ?></td>
                    <td><?= htmlspecialchars($card['Life']) ?></td>
                    <td><?= htmlspecialchars($card['rarity']) ?></td>
                    <td><?= htmlspecialchars($card['quantity']) ?></td>
                    <td>
                        <?php if (is_numeric($card['price'])) : ?>
                            <?= $card['price'] ?> €
                            <?php 
                                // on calcule la valeur totale pour cette carte
                                $total += $card['price'] * (int)$card['quantity']; 
                            ?>
                        <?php else : ?>
                            <?= htmlspecialchars($card['price']) ?>
                        <?php endif; ?>
                    </td>
                    <td>
    <form method="POST" action="index.php?controller=card&action=removeFromCollection" style="display:inline;">
        <input type="hidden" name="card_id" value="<?= htmlspecialchars($card['id']) ?>">
        <input type="number" name="quantity" min="1" max="<?= (int)$card['quantity'] ?>" value="1" style="width:60px;">
        <button type="submit">🗑️ Retirer</button>
    </form>
</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3>💰 Valeur totale de ma collection : <?= number_format($total, 2, ',', ' ') ?> €</h3>

<?php endif; ?>
