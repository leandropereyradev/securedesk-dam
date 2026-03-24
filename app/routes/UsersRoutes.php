<?php

namespace app\routes;

use app\controllers\AuditLogsController;
use app\controllers\SessionController;
use app\controllers\UsersController;
use app\core\ViewContext;
use app\helpers\RedirectHelper;

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

  public static function changePasswordPost()
  {
    SessionController::requireLogin();

    $result = UsersController::changePassword(
      $_SESSION['user_id'],
      $_POST['password'] ?? '',
      $_POST['password_confirm'] ?? ''
    );

    if (!$result['success']) {

      RedirectHelper::error('profile', $result['error']);
    } else {
      AuditLogsController::logChangePassword($_SESSION['user_id']);
      RedirectHelper::success('profile', 'Contraseña actualizada correctamente');
    }
  }
}
