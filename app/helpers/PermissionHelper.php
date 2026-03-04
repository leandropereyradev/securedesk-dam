<?php

namespace app\helpers;

class PermissionHelper
{
  public static function can(string $permission): bool
  {
    $role = $_SESSION['role'] ?? null;
    if (!$role) {
      return false;
    }

    $config = require ROOT . 'config/permissions.php';

    if (!isset($config['roles'][$role])) {
      return false;
    }

    $permissions = $config['roles'][$role];

    // admin / acceso total
    if (in_array('*', $permissions, true)) {
      return true;
    }

    return in_array($permission, $permissions, true);
  }
}
