<?php

use app\helpers\AuditViewHelper;

$usersOptions = $_SESSION['usersOptions'] ?? [];
$audits = $_SESSION['audits'] ?? [];

$filters = AuditViewHelper::getFilters($usersOptions);
$columns = AuditViewHelper::getColumns();

$sessionKey = 'audit_filters';
$rows = $audits;

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