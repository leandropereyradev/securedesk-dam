<?php

use app\helpers\TicketsListViewHelper;

$tickets = $_SESSION['tickets'] ?? [];

$filters = TicketsListViewHelper::getFilters();
$columns = TicketsListViewHelper::getColumns();

$sessionKey = 'tickets_filters';
$rows = $tickets;

?>

<div class="list-container">
  <h1>Tickets</h1>

  <?php require_once ROOT . "app/views/fragments/filter-form.php"; ?>

  <?php if (empty($tickets)): ?>
    <p>No hay tickets disponibles.</p>
  <?php else: ?>

    <?php require_once ROOT . "app/views/fragments/table.php"; ?>
  <?php endif; ?>
</div>