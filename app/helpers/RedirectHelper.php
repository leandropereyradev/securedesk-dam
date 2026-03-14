<?php

namespace app\helpers;

class RedirectHelper
{
  public static function loginError(string $message): void
  {
    self::fail(
      'login_error',
      $message,
      'login'
    );
  }

  public static function to(string $route): void
  {
    header("Location: {$route}");
    exit;
  }

  private static function fail(
    string $typeError,
    string $message,
    string $route
  ): void {
    $_SESSION[$typeError] = $message;
    self::to($route);
  }
}
