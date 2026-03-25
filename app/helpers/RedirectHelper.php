<?php

namespace app\helpers;

use app\helpers\FlashHelper;

class RedirectHelper
{
  public static function to(string $route): void
  {
    header("Location: {$route}");
    exit;
  }

  public static function success(string $route, string $message): void
  {
    FlashHelper::set('success', $message);
    self::to($route);
  }

  public static function error(string $route, string $message): void
  {
    FlashHelper::set('error', $message);
    self::to($route);
  }

  public static function warning(string $route, string $message): void
  {
    FlashHelper::set('warning', $message);
    self::to($route);
  }

  public static function info(string $route, string $message): void
  {
    FlashHelper::set('info', $message);
    self::to($route);
  }
}
