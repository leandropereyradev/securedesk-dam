<?php

use app\helpers\SecurityHelper;

$details = [
  'title' => 'Título:',
  'description' => 'Descripción:',
  'status' => 'Estado:',
  'priority' => 'Prioridad:',
  'category' => 'Categoría:',
  'assigned_to_username' => 'Asignado a:',
  'created_at' => 'Creado el:',
  'updated_at' => 'Última actualización:'
];
?>

<div class="ticket-card">

  <?php foreach ($details as $field => $label): ?>

    <?php if ($field === 'assigned_to_username' && empty($ticket[$field])): ?>
      <p><strong><?= SecurityHelper::escapeXSS($label) ?></strong>
        Sin Asignar
      </p>
    <?php else: ?>
      <p><strong><?= SecurityHelper::escapeXSS($label) ?></strong>
        <?= nl2br(SecurityHelper::escapeXSS($ticket[$field])) ?>
      </p>
    <?php endif; ?>

  <?php endforeach; ?>

</div>