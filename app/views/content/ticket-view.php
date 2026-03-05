<?php

use app\helpers\PermissionHelper;

$ticket = $_SESSION['ticket'] ?? null;

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

<h1>Detalle del Ticket</h1>

<div class="ticket-card">

  <?php foreach ($details as $field => $label): ?>

    <?php if ($field === 'assigned_to_username' && empty($ticket[$field])): ?>
      <p><strong><?= htmlspecialchars($label) ?></strong>
        Sin Asignar
      </p>
    <?php else: ?>
      <p><strong><?= htmlspecialchars($label) ?></strong>
        <?= nl2br(htmlspecialchars($ticket[$field])) ?>
      </p>
    <?php endif; ?>

  <?php endforeach; ?>

</div>

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

</div>

<div class="attachment-container">
  <h3>Archivos adjuntos</h3>
  <?php if ($_SESSION['role'] !== 'reader'): ?>
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