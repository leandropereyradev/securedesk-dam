<?php

use app\controllers\SessionController;

// Verificar sesión antes de mostrar el dashboard
SessionController::requireLogin();
?>

<div class="dashboard-container">
  <h1>Bienvenido</h1>
</div>