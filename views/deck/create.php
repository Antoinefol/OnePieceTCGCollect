<h2>Créer un deck</h2>
<form method="POST">
    <label>Nom du deck :</label>
    <input type="text" name="name" required>

    <label>Choisir un Leader :</label>
    <select name="leader_id" required>
        <?php foreach ($leaders as $leader): ?>
            <option value="<?= htmlspecialchars($leader['id']) ?>">
                <?= htmlspecialchars($leader['name']) ?> (<?= htmlspecialchars($leader['color']) ?>)
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Créer</button>
</form>
