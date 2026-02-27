<?php

use app\controllers\SessionController;

$loggedIn = SessionController::isLoggedIn();
?>

<header>

  <a class="link-logo" href="<?php echo APP_URL; ?>">
    <div class="logo-container">
      <img src="<?php echo APP_URL; ?>public/assets/images/secureDesk.png" alt="SecureDesk Logo">
      <h1>SecureDesk</h1>
    </div>
  </a>

  <div>
    <?php if ($loggedIn): ?>
      <a class="button" href="logout">Logout</a>
    <?php else: ?>
      <a class="button" href="login">Login</a>
    <?php endif; ?>
  </div>
</header>