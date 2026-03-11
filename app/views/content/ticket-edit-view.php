<?php

use app\helpers\SecurityHelper;

$ticket = $_SESSION['ticket'] ?? null;
$users = $_SESSION['users'] ?? [];
?>

<div class="ticket-edit-container">
  <h1>Editar Ticket</h1>

  <form method="POST">

    <input type="hidden" name="ticket_id" value="<?= (int)$ticket['id'] ?>">
    
    <?= SecurityHelper::csrfField(); ?>

    <!-- ESTADO -->
    <label for="status">Estado</label>
    <select name="status" id="status" required>
      <?php
      $statusOptions = ['new', 'in_process', 'resolved'];
      foreach ($statusOptions as $status):
      ?>
        <option
          value="<?= $status ?>"
          <?= $ticket['status'] === $status ? 'selected' : '' ?>>
          <?= ucfirst(str_replace('_', ' ', $status)) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <!-- PRIORIDAD -->
    <label for="priority">Prioridad</label>
    <select name="priority" id="priority" required>
      <?php
      $priorityOptions = ['low', 'medium', 'high', 'critical'];
      foreach ($priorityOptions as $priority):
      ?>
        <option
          value="<?= $priority ?>"
          <?= $ticket['priority'] === $priority ? 'selected' : '' ?>>
          <?= ucfirst($priority) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <!-- DESCRIPCIÓN -->
    <label for="description">Descripción</label>
    <textarea
      name="description"
      id="description"
      rows="5"
      required><?= SecurityHelper::escapeXSS($ticket['description']) ?></textarea>

    <!-- ASIGNADO A -->
    <label for="assigned_to">Asignado a: <?= SecurityHelper::escapeXSS($ticket['assigned_to'] ?
                                            $users[array_search(
                                              $ticket['assigned_to'],
                                              array_column($users, 'id')
                                            )]['username'] : 'Sin asignar') ?>
    </label>
    <br>
    <!-- ASIGNAR A -->
    <label for="assigned_to">Asignar a</label>
    <select name="assigned_to" id="assigned_to">
      <option value="">Sin asignar</option>

      <?php foreach ($users as $user): ?>
        <option
          value="<?= (int)$user['id'] ?>"
          <?= (int)$ticket['assigned_to'] === (int)$user['id'] ? 'selected' : '' ?>>
          <?= SecurityHelper::escapeXSS($user['username']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <!-- ACCIONES -->
    <div class="actions">
      <a href="<?= APP_URL ?>ticket?id=<?= (int)$ticket['id'] ?>" class="button">
        Cancelar
      </a>

      <button type="submit" class="button">
        Guardar cambios
      </button>
    </div>

  </form>
</div>