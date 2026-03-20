<?php

use app\helpers\SecurityHelper;
?>
<div class="list-filter-container">
  <form method="POST">

    <?= SecurityHelper::csrfField(); ?>

    <?php
    // Mantener los filtros seleccionados en sesión
    $selectedFilters = $_SESSION[$sessionKey ?? 'filters'] ?? [];
    ?>

    <?php foreach ($filters as $filter): ?>
      <?php $selected = $selectedFilters[$filter['name']] ?? ''; ?>

      <label>
        <?= SecurityHelper::escapeXSS($filter['label']) ?>:

        <select name="<?= SecurityHelper::escapeXSS($filter['name']) ?>">
          <option value=""><?= $filter['all_label'] ?? 'Todos' ?></option>

          <?php foreach ($filter['options'] as $value => $text): ?>
            <option value="<?= SecurityHelper::escapeXSS($value) ?>"
              <?= (string)$selected === (string)$value ? 'selected' : '' ?>>
              <?= SecurityHelper::escapeXSS($text) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </label>

    <?php endforeach; ?>

    <button type="submit">Filtrar</button>
    <button type="submit" name="reset_filters" value="1">Ver todos</button>
  </form>
</div>