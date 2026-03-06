<?php

use app\helpers\PermissionHelper;
?>

<div class="attachment-container">
  <h3>Archivos adjuntos</h3>

  <?php if (PermissionHelper::can('upload')): ?>

    <form method="POST" action="upload" enctype="multipart/form-data">
      <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
      <input type="file" name="attachment">
      <button type="submit">Subir</button>
    </form>

  <?php endif; ?>

  <?php if (!empty($ticket['attachments'])): ?>

    <ul>
      <?php foreach ($ticket['attachments'] as $att): ?>
        <li>
          <?= htmlspecialchars($att['filename']) ?>
          (<?= round($att['size'] / 1024, 1) ?> KB)
          subido por <?= htmlspecialchars($att['uploaded_by']) ?>
          el <?= $att['uploaded_at'] ?>
          - <a href="attachment-download?id=<?= $att['id'] ?>">Descargar</a>
        </li>
      <?php endforeach; ?>
    </ul>

  <?php else: ?>
    <p>No hay archivos adjuntos.</p>
  <?php endif; ?>
</div>