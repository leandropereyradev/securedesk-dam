<?php

namespace app\routes;

use app\controllers\AuthController;
use app\helpers\RedirectHelper;

class AuthRoutes
{
  public static function loginGet()
  {
    if (isset($_SESSION['user_id'])) {
      RedirectHelper::to('dashboard');
    }
  }

  public static function loginPost()
  {
    AuthController::login();
  }

  public static function logoutGet()
  {
    AuthController::logout();
  }
}
