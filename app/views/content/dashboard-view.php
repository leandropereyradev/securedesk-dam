<?php

use app\helpers\KpiHelper;

$dashboard = view('dashboard', []);

$distribution = $dashboard['distribution'] ?? [];
$stats = $dashboard['stats'] ?? [];

$statsDetails = KpiHelper::getFields();

?>

<div class="dashboard-container">
  <div class="kpi-container">
    <?php if ($stats): ?>

      <div class="dashboard-header">
        <div>
          <h3>Actualizado: <?= $dashboard['updated_at'] ?></h3>
          <div class="border"></div>
          <?php require_once ROOT . "app/views/fragments/dashboard/stats.php"; ?>
        </div>
        <div class="quick-links">
          <a href="critical-tickets" class="button danger">
            Tickets críticos
          </a>

          <a href="unassigned-tickets" class="button warning">
            Tickets sin asignar
          </a>
        </div>

      </div>

      <div class="border"></div>

      <?php require_once ROOT . "app/views/fragments/dashboard/graphics.php"; ?>

    <?php else: ?>
      <div class="empty-state">
        <h2>KPIs no disponibles</h2>
        <p>No se han encontrado registros en el sistema para calcular métricas.</p>
      </div>
    <?php endif; ?>

  </div>
</div>