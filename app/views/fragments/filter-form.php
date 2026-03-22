<?php

use app\helpers\SecurityHelper;
?>
<div class="list-filter-container">
  <form method="GET">
    <?php
    $selectedFilters = view('filters', []);
    ?>

    <div class="states">
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
    </div>

    <?php if ($showSearch): ?>
      <div class="search">
        <label>
          Buscar:
          <input type="text" name="q" value="<?= SecurityHelper::escapeXSS($q ?? '') ?>">
        </label>
        <label>
          En:
          <select name="search_in">
            <option value="all" <?= ($searchIn ?? '') === 'all' ? 'selected' : '' ?>>Título + Descripción</option>
            <option value="title" <?= ($searchIn ?? '') === 'title' ? 'selected' : '' ?>>Título</option>
            <option value="description" <?= ($searchIn ?? '') === 'description' ? 'selected' : '' ?>>Descripción</option>
          </select>
        </label>
      </div>
    <?php endif; ?>

    <div class="button-container">
      <button type="submit" class="button">Filtrar</button>
      <a href="<?= SecurityHelper::escapeXSS($ref) ?>" class="button">Ver todos los tickets</a>
    </div>
  </form>
</div>