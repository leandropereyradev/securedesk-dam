<?php

use app\controllers\SessionController;
use app\helpers\PermissionHelper;
use app\helpers\SecurityHelper;

if (!SessionController::isLoggedIn()) {
  return;
}

$menu = require ROOT . 'config/menu.php';
$current = $_GET['views'] ?? '';
?>

<nav class="nav">
  <ul>
    <?php foreach ($menu as $item): ?>

      <?php
      if (
        $item['permission'] !== null &&
        !PermissionHelper::can($item['permission'])
      ) {
        continue;
      }

      $isActive = $current === $item['route'];
      ?>

      <li class="<?= $isActive ? 'active' : '' ?>">
        <a class="button" href="<?= APP_URL . $item['route'] ?>">
          <?= SecurityHelper::escapeXSS($item['label']) ?>
        </a>

        <?php if (!empty($item['children'])): ?>
          
            <?php foreach ($item['children'] as $child): ?>

              <?php
              if (!PermissionHelper::can($child['permission'])) {
                continue;
              }

              $childActive = $current === $child['route'];
              ?>

              <li class="<?= $childActive ? 'active' : '' ?>">
                <a class="button submenu-button" href="<?= APP_URL . $child['route'] ?>">
                  <?= SecurityHelper::escapeXSS($child['label']) ?>
                </a>
              </li>

            <?php endforeach; ?>
          
        <?php endif; ?>
      </li>

    <?php endforeach; ?>
  </ul>
</nav>
