<?php

use app\helpers\SecurityHelper;

$history = $_SESSION['ticket']['history'] ?? [];

$fieldLabels = [
  'status' => 'Estado',
  'priority' => 'Prioridad',
  'assigned_to' => 'Asignado a'
];
?>

<div class="ticket-history-container">
  <h3>Historial de Cambios</h3>

  <?php if (!empty($history)): ?>

    <ul class="ticket-history">

      <?php foreach ($history as $h): ?>

        <li>
          <strong><?= SecurityHelper::escapeXSS($h['changed_by_username']) ?></strong>
          cambió
          <em><?= SecurityHelper::escapeXSS($fieldLabels[$h['field']] ?? $h['field']) ?></em>
          de
          "<strong><?= SecurityHelper::escapeXSS($h['old_value']) ?></strong>"
          a
          "<strong><?= SecurityHelper::escapeXSS($h['new_value']) ?></strong>"
          <span class="history-date">
            (<?= SecurityHelper::escapeXSS($h['changed_at']) ?>)
          </span>
        </li>

      <?php endforeach; ?>

    </ul>

  <?php else: ?>
    <p>No hay cambios registrados.</p>
  <?php endif; ?>

</div>