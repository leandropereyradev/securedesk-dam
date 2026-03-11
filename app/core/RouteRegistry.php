<?php

namespace app\core;

class RouteRegistry
{
  public static function dispatch(string $methodName): void
  {
    $routesPath = ROOT . "app/routes/";

    $files = glob($routesPath . "*.php");

    foreach ($files as $file) {

      // Nombre del archivo
      $className = basename($file, ".php");

      // Namespace completo
      $fullClass = "app\\routes\\" . $className;

      if (!class_exists($fullClass)) {
        require_once $file;
      }

      if (method_exists($fullClass, $methodName)) {
        $fullClass::$methodName();
        return;
      }
    }
  }
}
