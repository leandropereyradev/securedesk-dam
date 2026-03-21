<?php

use app\helpers\PermissionHelper;
use app\helpers\TicketsListViewHelper;
use app\helpers\UrlHelper;

$tickets = view('tickets', []);
$users = view('users', []);

$filters = TicketsListViewHelper::getFilters($users);
$columns = TicketsListViewHelper::getColumns();

$rows = $tickets;
$ref = "tickets";

$selectedFilters = view('filters', []);
$q = $selectedFilters['q'] ?? '';
$searchIn = $selectedFilters['search_in'] ?? 'all';
?>

<div class="list-container">
  <div class="title">
    <h1>Listado de Tickets</h1>
    <div class="export-button">
      <?php if (PermissionHelper::can('tickets-export')): ?>
        <a href="<?=
                  UrlHelper::withQuery(
                    'tickets-export-csv',
                    view('filters', [])
                  ) ?>"
          class="button">
          Exportar CSV
        </a>
      <?php endif; ?>
    </div>
  </div>

  <?php require_once ROOT . "app/views/fragments/filter-form.php"; ?>

  <?php if (empty($tickets)): ?>
    <p>No hay tickets disponibles.</p>
  <?php else: ?>

    <?php require_once ROOT . "app/views/fragments/table.php"; ?>
  <?php endif; ?>
</div>