<div class="table-container">
  <table>
    <thead>
      <tr>
        <?php

        use app\helpers\SecurityHelper;

        foreach ($columns as $column): ?>
          <th><?= SecurityHelper::escapeXSS($column['label']) ?></th>
        <?php endforeach; ?>
      </tr>
    </thead>

    <tbody>
      <?php foreach ($rows as $row): ?>
        <tr>

          <?php foreach ($columns as $column): ?>

            <td>

              <?php
              $field = $column['field'] ?? null;

              if (isset($column['render']) && is_callable($column['render'])) {
                echo $column['render']($row);
              } elseif ($field) {
                echo SecurityHelper::escapeXSS($row[$field] ?? '');
              }
              ?>

            </td>

          <?php endforeach; ?>

        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>