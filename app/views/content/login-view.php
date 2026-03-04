<?php

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

    <?php if ($error): ?>
      <div class="error-message">
        <div><?= htmlspecialchars($error) ?></div>
      </div>
    <?php endif; ?>

    <button class="button" type="submit">Ingresar</button>
  </form>

</div>