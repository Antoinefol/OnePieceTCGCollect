<h2>📊 Statistiques de ma collection</h2>

<h3>💰 Valeur totale : <?= number_format($totalValue, 2, ',', ' ') ?> €</h3>

<?php foreach ($extensions as $extension => $data): ?>
    <div style="border:1px solid #ccc; padding:15px; margin-bottom:20px; border-radius:10px;">
        <h4><?= htmlspecialchars($extension) ?></h4>

        <p>
            Cartes uniques collectionnées :
            <?= count($data['unique']) ?> / <?= $data['total_cards'] ?>
            <?php if ($data['total_cards'] > 0): ?>
                (<?= round(count($data['unique']) / $data['total_cards'] * 100, 1) ?> %)
            <?php endif; ?>
        </p>

        <p>
            👉 Normal : <?= $data['normal'] ?> / <?= $data['normal_total'] ?><br>
            🌟 Parallèle : <?= $data['parallel'] ?> / <?= $data['parallel_total'] ?>
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
