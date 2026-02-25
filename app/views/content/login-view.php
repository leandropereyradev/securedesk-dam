<?php

use app\controllers\SessionController;

SessionController::start();

$error = $_SESSION['login_error'] ?? null;
unset($_SESSION['login_error']);
?>

<div class="login-container">
  <h2>Iniciar sesión</h2>

  <form class="login-form" method="POST">
    <div>
      <label for="username">Usuario</label>
      <input type="text" name="username" required>
    </div>

    <div>
      <label for="password">Contraseña</label>
      <input type="password" name="password" required>
    </div>

    <div class="error-message">
      <?php if ($error): ?>
        <div><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
    </div>

    <button class="button" type="submit">Ingresar</button>
  </form>

</div>