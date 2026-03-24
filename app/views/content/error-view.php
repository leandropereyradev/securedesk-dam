<?php
$errorCode = view('error_code', 500);
$title = view('error_title');
$message = view('error_message');
$isLogged = view('is_logged');
?>

<div class="error-container <?= !$isLogged ? 'no-session' : '' ?>">

  <div class="error-card">

    <div class="logo">
      <img src="<?= APP_URL ?>public/assets/images/secureDesk.png" alt="Logo">
    </div>

    <h1><?= $errorCode ?></h1>

    <p class="message">
      <?= $title ?>
    </p>

    <p class="submessage">
      <?= $message ?>
    </p>

    <?php if (!$isLogged): ?>

      <a href="<?= APP_URL ?>" class="button-home">
        Volver al inicio
      </a>

    <?php endif; ?>
  </div>

</div>