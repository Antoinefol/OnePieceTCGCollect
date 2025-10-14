<div class="profileWrap">
<h2>Statistiques </h2>
<div class="btnWrap" data-active="<?= htmlspecialchars($_GET['action'] ?? '') ?>">
    <a class="profilebtn <?= ($_GET['action'] ?? '') === 'profile' ? 'active' : '' ?>" 
       href="index.php?controller=user&action=profile">Profile</a>

    <a class="profilebtn <?= ($_GET['action'] ?? '') === 'stats' ? 'active' : '' ?>" 
       href="index.php?controller=card&action=stats">Statistiques</a>

    <a class="profilebtn <?= ($_GET['action'] ?? '') === 'list' ? 'active' : '' ?>" 
       href="index.php?controller=deck&action=list">Decks</a>
  </div>
</div>
<h3 class="Valeur">Valeur totale : <?= number_format($totalValue, 2, ',', ' ') ?> €</h3>
<div class="statGrid">
<?php foreach ($extensions as $extension => $data): ?>
    <div class="statCard">
        <div>
        <h4><?= htmlspecialchars($extension) ?></h4>

        <p>
            Cartes uniques collectionnées :
            <?= count($data['unique']) ?> / <?= $data['total_cards'] ?>
            <?php if ($data['total_cards'] > 0): ?>
                (<?= round(count($data['unique']) / $data['total_cards'] * 100, 1) ?> %)
            <?php endif; ?>
        </p>

        <p>
             Normal : <?= $data['normal'] ?> / <?= $data['normal_total'] ?>
        </p>
        <p> Parallèle : <?= $data['parallel'] ?> / <?= $data['parallel_total'] ?></p>

        <h5>Détail par type :</h5>
        
            <?php foreach ($data['total_types'] as $type => $totalType): ?>
                <p>
                    <?= htmlspecialchars($type) ?> :
                    <?= $data['types'][$type] ?? 0 ?> / <?= $totalType ?>
                    (<?= round(($data['types'][$type] ?? 0) / $totalType * 100, 1) ?> %)
                </p>
            <?php endforeach; ?>
        
    </div>
        <img src="./images/assets/<?= htmlspecialchars($extension) ?>.png" alt="Card Box">
    </div>
<?php endforeach; ?>
</div>