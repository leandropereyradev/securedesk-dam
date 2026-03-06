<?php

use app\controllers\SessionController;

SessionController::requireLogin();

$tickets = $_SESSION['tickets'] ?? [];
?>

<div class="tickets-container">
  <h1>Tickets</h1>

  <div class="ticket-filter-container">
    <?php require_once ROOT . "app/views/fragments/ticket-filter.php"; ?>
  </div>

  <?php if (empty($tickets)): ?>
    <p>No hay tickets disponibles.</p>
  <?php else: ?>

    <div class="table-container">
      <?php require_once ROOT . "app/views/fragments/tickets-table.php"; ?>
    </div>
  <?php endif; ?>
</div>