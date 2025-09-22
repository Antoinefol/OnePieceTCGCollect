<h2>Mes decks</h2>

<a href="index.php?controller=deck&action=create">
    <button>+ Créer un nouveau deck</button>
</a>

<?php if (empty($decks)): ?>
    <p>Vous n'avez encore créé aucun deck.</p>
<?php else: ?>
    <ul>
        <?php foreach ($decks as $deck): ?>
            <li>
                <strong><?= htmlspecialchars($deck['name']) ?></strong>
                (Leader ID: <?= htmlspecialchars($deck['leader_id']) ?>)
                - <?= $deck['card_count'] ?>/50 cartes

                <a href="index.php?controller=deck&action=edit&id=<?= $deck['id'] ?>">
                    <button>Voir / Modifier</button>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
