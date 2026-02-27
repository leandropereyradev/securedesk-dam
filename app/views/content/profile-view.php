<?php

use app\controllers\SessionController;

// Verificar sesión antes de mostrar el profile
SessionController::requireLogin();
?>

<div class="profile-container">
  <h1>Perfil de Usuario</h1>
  <p>Nombre de usuario: <?php echo htmlspecialchars($_SESSION['username']); ?></p>
  <p>Rol: <?php echo $_SESSION['role']; ?></p>
</div>