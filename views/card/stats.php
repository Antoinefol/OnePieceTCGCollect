<div class="profileWrap">
<h2>Statistiques </h2>
<div>
<a class="profilebtn" href="index.php?controller=user&action=profile">Profile</a>
<a class="profilebtn" href="index.php?controller=deck&action=list">Mes Decks</a></div>
</div>
<h3>Valeur totale : <?= number_format($totalValue, 2, ',', ' ') ?> €</h3>
<div class="statGrid">
<?php foreach ($extensions as $extension => $data): ?>
    <div class="statCard">
        <h4><?= htmlspecialchars($extension) ?></h4>

        <p>
            Cartes uniques collectionnées :
            <?= count($data['unique']) ?> / <?= $data['total_cards'] ?>
            <?php if ($data['total_cards'] > 0): ?>
                (<?= round(count($data['unique']) / $data['total_cards'] * 100, 1) ?> %)
            <?php endif; ?>
        </p>

        <p>
             Normal : <?= $data['normal'] ?> / <?= $data['normal_total'] ?><br>
             Parallèle : <?= $data['parallel'] ?> / <?= $data['parallel_total'] ?>
        </p>

        <h5>Détail par type :</h5>
        <ul>
            <?php foreach ($data['total_types'] as $type => $totalType): ?>
                <li>
                    <?= htmlspecialchars($type) ?> :
                    <?= $data['types'][$type] ?? 0 ?> / <?= $totalType ?>
                    (<?= round(($data['types'][$type] ?? 0) / $totalType * 100, 1) ?> %)
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endforeach; ?>
</div>