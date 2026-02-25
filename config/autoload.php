<?php

spl_autoload_register(function (string $class) {
  $baseDir = BASE_PATH . '/';

  $file = $baseDir . str_replace('\\', '/', $class) . '.php';

  if (is_file($file)) {
    require_once $file;
  }
});
