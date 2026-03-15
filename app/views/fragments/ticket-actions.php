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

  <a href="ticket-report?id=<?= (int)$ticket['id'] ?>"
    class="button"
    target="_blank">
    Exportar informe
  </a>
</div>