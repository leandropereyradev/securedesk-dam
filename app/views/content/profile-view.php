<?php

use app\helpers\SecurityHelper;

?>

<div class="profile-container">
  <h1>Perfil de Usuario</h1>

  <p>Nombre de usuario: <?= SecurityHelper::escapeXSS($_SESSION['username']); ?></p>
  <p>Rol: <?= SecurityHelper::escapeXSS($_SESSION['role']); ?></p>

  <?php require_once ROOT . "app/views/fragments/flash-messages.php"; ?>

  <div class="password-card">
    <h2>Cambiar contraseña</h2>

    <form method="POST" action="change-password">
      <?= SecurityHelper::csrfField(); ?>

      <label>
        Nueva contraseña
        <input type="password" name="password" id="password" required>
      </label>

      <label>
        Repetir contraseña
        <input type="password" name="password_confirm" id="password_confirm" required>
      </label>

      <div class="password-rules">
        <p id="rule-length">Mínimo 8 caracteres</p>
        <p id="rule-upper">Al menos una mayúscula</p>
        <p id="rule-lower">Al menos una minúscula</p>
        <p id="rule-number">Al menos un número</p>
        <p id="rule-special">Al menos un carácter especial</p>
        <p id="rule-match">Las contraseñas coinciden</p>
      </div>

      <button type="submit" class="button">Actualizar contraseña</button>
    </form>
  </div>
</div>