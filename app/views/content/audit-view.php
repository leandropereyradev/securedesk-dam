<?php

use app\helpers\AuditViewHelper;

$usersOptions = view('usersOptions', []);
$audits = view('audits', []);

$filters = AuditViewHelper::getFilters($usersOptions);
$columns = AuditViewHelper::getColumns();

$rows = $audits;
$ref = "audit";

$showSearch = false;
?>

<div class="list-container">
  <h1>Auditoría</h1>

  <?php require_once ROOT . "app/views/fragments/filter-form.php"; ?>

  <?php if (empty($audits)): ?>
    <p>No hay auditoría disponibles.</p>
  <?php else: ?>

    <?php require_once ROOT . "app/views/fragments/table.php"; ?>

  <?php endif; ?>
</div>