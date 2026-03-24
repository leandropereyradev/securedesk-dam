<?php

namespace app\middlewares;

class RoleMiddleware
{
  private static array $config;

  private static function loadConfig(): void
  {
    if (!isset(self::$config)) {
      self::$config = require ROOT . 'config/permissions.php';
    }
  }

  public static function check(string $viewName): bool
  {
    self::loadConfig();

    if (in_array($viewName, self::$config['public'], true)) {
      return true;
    }

    if (!isset($_SESSION['user_id'])) {
      return false;
    }

    $role = $_SESSION['role'] ?? null;

    if (
      isset(self::$config['roles'][$role]) &&
      in_array('*', self::$config['roles'][$role], true)
    ) {
      return true;
    }

    if (
      isset(self::$config['roles'][$role]) &&
      in_array($viewName, self::$config['roles'][$role], true)
    ) {
      return true;
    }

    return false;
  }
}
