<?php

namespace app\middlewares;

use app\controllers\SessionController;

class RoleMiddleware
{
  private static array $config;

  private static function loadConfig(): void
  {
    if (!isset(self::$config)) {
      self::$config = require ROOT . 'config/permissions.php';
    }
  }

  public static function check(string $viewName)
  {
    self::loadConfig();
    SessionController::start();

    // Vistas públicas
    if (in_array($viewName, self::$config['public'], true)) {
      return;
    }

    // Requiere login
    if (!isset($_SESSION['user_id'])) {
      header('Location: login');
      exit;
    }

    // Admin = acceso total
    $role = $_SESSION['role'] ?? null;
    if (
      isset(self::$config['roles'][$role]) &&
      in_array('*', self::$config['roles'][$role], true)
    ) {
      return;
    }

    // Permisos normales
    if (
      !isset(self::$config['roles'][$role]) ||
      !in_array($viewName, self::$config['roles'][$role], true)
    ) {

      http_response_code(403);
      require_once ROOT . 'app/views/content/403-view.php';
      exit;
    }
  }
}
