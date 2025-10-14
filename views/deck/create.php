<section class="create-deck-section">
  <h2>Créer un deck</h2>
  <form method="POST" id="create-deck-form">
    <label>Nom du deck :</label>
    <input type="text" name="name" required>

    <label>Choisir un Leader :</label>
    <div class="leader-select-grid">
      <?php foreach ($leaders as $leader): ?>
        <div class="leader-card" data-leader-id="<?= htmlspecialchars($leader['id']) ?>" data-color="<?= htmlspecialchars($leader['color']) ?>">
          <img src="./images/cards/<?= htmlspecialchars($leader['number']) ?>-<?= htmlspecialchars($leader['version']) ?>.png"
               alt="<?= htmlspecialchars($leader['name']) ?>">
          <p><?= htmlspecialchars($leader['name']) ?> <?= htmlspecialchars($leader['version']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Champ caché qui reçoit l’ID du leader sélectionné -->
    <input type="hidden" name="leader_id" id="leader_id" required>

    <button class="btn"type="submit">Créer</button>
  </form>
</section>
