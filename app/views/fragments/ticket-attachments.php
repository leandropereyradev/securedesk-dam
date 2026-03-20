<?php

use app\helpers\PermissionHelper;
use app\helpers\SecurityHelper;

?>

<div class="attachment-container">
  <h3>Archivos adjuntos</h3>

  <?php if (PermissionHelper::can('upload')): ?>

    <form method="POST" action="upload" enctype="multipart/form-data">

      <?= SecurityHelper::csrfField(); ?>

      <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
      <input type="file" name="attachment">
      <button type="submit">Subir</button>
    </form>

  <?php endif; ?>

  <?php if (!empty($attachments)): ?>

    <ul>
      <?php foreach ($attachments as $att): ?>
        <li>
          <?= SecurityHelper::escapeXSS($att['filename']) ?>
          (<?= round($att['size'] / 1024, 1) ?> KB)
          subido por <?= SecurityHelper::escapeXSS($att['uploaded_by']) ?>
          el <?= $att['uploaded_at'] ?>
          - <a href="attachment-download?id=<?= $att['id'] ?>">Descargar</a>
        </li>
      <?php endforeach; ?>
    </ul>

  <?php else: ?>
    <p>No hay archivos adjuntos.</p>
  <?php endif; ?>
</div>