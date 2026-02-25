<?php
// Definir la ruta raíz
define('ROOT', realpath(__DIR__ . '/../') . '/');

// Cargar bootstrap
require_once ROOT . 'config/bootstrap.php';

use app\controllers\AppController;

$app = new AppController();
$viewPath = $app->handleRequest();


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php require_once ROOT . "app/views/fragments/head.php"; ?>
</head>

<body>
  <?php require_once ROOT . "app/views/fragments/header.php"; ?>
  <section>
    <div class="nav-container">
      <?php require_once ROOT . "app/views/fragments/navBar.php"; ?>
    </div>
    <div class="dinamic-container">
      <?php require_once $viewPath; ?>
    </div>
  </section>
</body>

</html>