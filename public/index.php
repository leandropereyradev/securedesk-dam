<?php
define('ROOT', realpath(__DIR__ . '/../') . '/');

require_once ROOT . 'config/bootstrap.php';

use app\controllers\AppController;

$app = new AppController();
$viewPath = $app->handleRequest();

$viewName = $_GET['views'] ?? 'home';

$isLogged = isset($_SESSION['user_id']);
$isErrorView = str_contains($viewPath, 'error-view.php');

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <?php require_once ROOT . "app/views/fragments/head.php"; ?>
</head>

<body>

  <?php if ($viewName === 'ticket-report'): ?>

    <?php require_once $viewPath; ?>

  <?php elseif ($isErrorView && !$isLogged): ?>

    <?php require_once $viewPath; ?>

  <?php else: ?>

    <?php require_once ROOT . "app/views/fragments/header.php"; ?>

    <section>
      <div class="nav-container">
        <?php require_once ROOT . "app/views/fragments/navBar.php"; ?>
      </div>

      <div class="dinamic-container <?= $isLogged ? 'is-logged' : '' ?>">
        <?php require_once $viewPath; ?>
      </div>
    </section>

  <?php endif; ?>

</body>

</html>