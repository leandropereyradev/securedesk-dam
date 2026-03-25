<?php

use app\helpers\SecurityHelper;
?>

<div class="login-container">
  <h2>Iniciar sesión</h2>

  <form class="login-form" method="POST">

    <?= SecurityHelper::csrfField(); ?>

    <div>
      <label for="username">Usuario</label>
      <input type="text" name="username">
    </div>

    <div>
      <label for="password">Contraseña</label>
      <input type="password" name="password">
    </div>

    <?php require_once ROOT . "app/views/fragments/flash-messages.php"; ?>

    <button class="button" type="submit">Ingresar</button>
  </form>

</div>