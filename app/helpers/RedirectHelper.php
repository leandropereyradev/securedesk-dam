<?php

namespace app\helpers;

use app\helpers\FlashHelper;

class RedirectHelper
{
  public static function TicketError(string $message): void
  {
    self::fail(
      'ticket_error',
      $message,
      'tickets'
    );
  }

  private static function fail(
    string $typeError,
    string $message,
    string $route
  ): void {
    $_SESSION[$typeError] = $message;
    self::to($route);
  }

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
