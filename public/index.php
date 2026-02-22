<?php
// Definir la ruta raíz del proyecto
define('ROOT', realpath(__DIR__ . '/../') . '/');

// Cargar configuración y autoload
require_once ROOT . 'config/config.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php require_once ROOT . "app/views/fragments/head.php"; ?>
</head>

<body>
  <?php require_once ROOT . "app/views/fragments/header.php"; ?>
  <section>
    <div>
      <?php require_once ROOT . "app/views/fragments/navBar.php"; ?>
    </div>
    <div class="dinamic-container">
      <?php require_once ROOT . "app/views/content/home-view.php"; ?>
    </div>
  </section>
</body>

</html>