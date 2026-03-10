<div class="list-filter-container">
  <form method="POST">

    <?php
    // Mantener los filtros seleccionados en sesión
    $selectedFilters = $_SESSION[$sessionKey ?? 'filters'] ?? [];
    ?>

    <?php foreach ($filters as $filter): ?>
      <?php $selected = $selectedFilters[$filter['name']] ?? ''; ?>

      <label>
        <?= htmlspecialchars($filter['label']) ?>:

        <select name="<?= htmlspecialchars($filter['name']) ?>">
          <option value=""><?= $filter['all_label'] ?? 'Todos' ?></option>

          <?php foreach ($filter['options'] as $value => $text): ?>
            <option value="<?= htmlspecialchars($value) ?>"
              <?= (string)$selected === (string)$value ? 'selected' : '' ?>>
              <?= htmlspecialchars($text) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </label>

    <?php endforeach; ?>

    <button type="submit">Filtrar</button>
  </form>
</div>