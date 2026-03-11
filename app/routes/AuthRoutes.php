<?php

namespace app\routes;

use app\controllers\AuthController;

class AuthRoutes
{
  public static function loginPost()
  {
    AuthController::login();
  }

  public static function logoutGet()
  {
    AuthController::logout();
  }
}
