<?php

use app\helpers\SecurityHelper;

$ticket = $_SESSION['ticket'] ?? null;
$comments = $ticket['comments'] ?? null;
$history = $ticket['history'] ?? null;
$attachments = $ticket['attachments'] ?? null;

?>
<style>
  @media print {
    body {
      font-size: 12pt;
      color: black;
    }
  }
</style>
<div class="report-container">
  <div class="report-body">
    <h1>Informe de Ticket #<?= SecurityHelper::escapeXSS($ticket['id']) ?></h1>
    <br>
    <br>

    <p><strong>Título:</strong> <?= SecurityHelper::escapeXSS($ticket['title']) ?></p>
    <p><strong>Estado:</strong> <?= SecurityHelper::escapeXSS($ticket['status']) ?></p>
    <p><strong>Prioridad:</strong> <?= SecurityHelper::escapeXSS($ticket['priority']) ?></p>
    <p><strong>Asignado:</strong> <?= SecurityHelper::escapeXSS($ticket['assigned_to']) ?></p>
    <p><strong>Creado:</strong> <?= SecurityHelper::escapeXSS($ticket['created_at']) ?></p>
    <p><strong>Actualizado:</strong> <?= SecurityHelper::escapeXSS($ticket['updated_at']) ?></p>
    <p><strong>Descripción:</strong></p>
    <p> - <?= SecurityHelper::escapeXSS($ticket['description']) ?></p>

    <br>
    <br>
    <h3>Comentarios</h3>

    <?php if (!empty($comments)): ?>

      <?php foreach ($comments as $comment): ?>

        <p>-
          <strong><?= SecurityHelper::escapeXSS($comment['username']) ?></strong>
          (<?= SecurityHelper::escapeXSS($comment['created_at']) ?>)
        </p>

        <p><?= SecurityHelper::escapeXSS($comment['comment']) ?></p>
        <br>

      <?php endforeach; ?>

    <?php else: ?>

      <p>No hay comentarios.</p>

    <?php endif; ?>

    <br>
    <h3>Historial de Cambios</h3>

    <?php if (!empty($history)): ?>
      <?php foreach ($history as $change): ?>

        <p>-
          <?= SecurityHelper::escapeXSS($change['field']) ?>:
          <?= SecurityHelper::escapeXSS($change['old_value']) ?>
          →
          <?= SecurityHelper::escapeXSS($change['new_value']) ?>
          (<?= SecurityHelper::escapeXSS($change['changed_at']) ?>)
        </p>

      <?php endforeach; ?>
    <?php else: ?>

      <p>No hay histórico de cambios.</p>

    <?php endif; ?>

    <br>
    <br>
    <h3>Adjuntos</h3>

    <?php if (!empty($attachments)): ?>
      <?php foreach ($attachments as $file): ?>

        <p>-
          <?= SecurityHelper::escapeXSS($file['filename']) ?>
          (<?= SecurityHelper::escapeXSS($file['uploaded_at']) ?>)
        </p>

      <?php endforeach; ?>
    <?php else: ?>

      <p>No hay archivos adjuntos.</p>

    <?php endif; ?>
  </div>
</div>