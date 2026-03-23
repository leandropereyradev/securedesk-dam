<?php

namespace app\routes;

use app\controllers\SessionController;
use app\controllers\UsersController;
use app\core\ViewContext;

class UsersRoutes
{
  public static function usersGet()
  {
    SessionController::requireLogin();

    $filters = [
      'role' => $_GET['role'] ?? null
    ];

    $users = UsersController::listAll($filters);

    ViewContext::set('users', $users);
    ViewContext::set('filters', $filters);
  }
}
