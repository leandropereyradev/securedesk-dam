<?php

use app\helpers\SecurityHelper;
use app\helpers\TicketReportHelper;

$isPdf = $isPdf ?? false;
$report = $report ?? ($_SESSION['report'] ?? null);

$ticket = $report['ticket'];
$comments = $report['comments'];
$history = $report['history'];
$attachments = $report['attachments'];

$details = TicketReportHelper::getFields();
?>

<div class="report-container">
  <div class="report-header">
    <div>
      <h1><?= SecurityHelper::escapeXSS(APP_NAME) ?></h1>
      <h2>Informe de Ticket</h2>
    </div>
    <div>
      <p><strong>Ticket ID: <?= SecurityHelper::escapeXSS($ticket['id']) ?></strong></p>
      <p>Generado por: <?= SecurityHelper::escapeXSS($report['generated_by']) ?></p>
      <p>Fecha: <?= SecurityHelper::escapeXSS($report['generated_at']) ?></p>
    </div>
  </div>

  <div class="report-section">
    <div class="report-details">
      <h3>Detalle</h3>
      <?php foreach ($details['ticket'] as $field => $label): ?>

        <p><strong> <?= SecurityHelper::escapeXSS($label) ?> </strong>
          <?= SecurityHelper::escapeXSS($ticket[$field]) ?>
        </p>
      <?php endforeach; ?>
    </div>

    <div class="report-comments">
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
    </div>

    <div class="report-history">
      <h3>Historial de Cambios</h3>
      <?php if (!empty($history)): ?>
        <?php foreach ($history as $change): ?>
          <?php
          $label = $details['history'][$change['field']] ?? $change['field'];
          ?>

          <p>-
            <?= SecurityHelper::escapeXSS($label) ?>
            de <?= SecurityHelper::escapeXSS($change['old_value']) ?>
        
            a <?= SecurityHelper::escapeXSS($change['new_value']) ?>
            (<?= SecurityHelper::escapeXSS($change['changed_at']) ?>)
          </p>

        <?php endforeach; ?>
      <?php else: ?>
        <p>No hay histórico de cambios.</p>
      <?php endif; ?>
    </div>

    <div class="report-attachments">
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
</div>

<?php
unset($_SESSION['report']);
?>