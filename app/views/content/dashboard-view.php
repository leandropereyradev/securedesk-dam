<?php
$stats = $_SESSION['dashboard']['stats'];
?>

<div class="dashboard-container">
  <h1>Bienvenido</h1>

  <div class="kpi-container">
    <?php if ($stats): ?>
      <div class="tickets-kpi">
        <h2>Tickets</h2>
        <p>Total: <?= $stats['total'] ?></p>
        <p>Nuevos: <?= $stats['new'] ?></p>
        <p>En proceso: <?= $stats['in_process'] ?></p>
        <p>Resueltos: <?= $stats['resolved'] ?></p>
      </div>

      <div class="priorities-kpi">
        <h2>Prioridad</h2>
        <p>Baja: <?= $stats['low'] ?></p>
        <p>Media: <?= $stats['medium'] ?></p>
        <p>Alta: <?= $stats['high'] ?></p>
        <p>Crítica: <?= $stats['critical'] ?></p>
      </div>

      <p>Actualizado: <?= $_SESSION['dashboard']['updated_at'] ?></p>

    <?php else: ?>
      <h2>No hay Tickets.</h2>
    <?php endif; ?>
  </div>
</div>
<?php
unset($_SESSION['dashboard']);
?>