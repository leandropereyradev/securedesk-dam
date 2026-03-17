<?php

use app\helpers\PermissionHelper;
?>

<div class="actions">
  <a href="<?= APP_URL ?>tickets" class="button">
    Volver
  </a>
  <?php if (PermissionHelper::can('ticket-edit')): ?>
    <a
      href="<?= APP_URL ?>ticket-edit?id=<?= (int)$ticket['id'] ?>"
      class="button">
      Editar
    </a>
  <?php endif; ?>

  <?php if (PermissionHelper::can('ticket-report')): ?>
    <a href="ticket-report?id=<?= (int)$ticket['id'] ?>"
      class="button"
      target="_blank">
      Exportar informe
    </a>

    <a href="ticket-export-pdf?id=<?= (int)$ticket['id'] ?>"
      class="button">
      Exportar PDF
    </a>
  <?php endif; ?>
</div>