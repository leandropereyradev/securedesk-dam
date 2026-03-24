<?php

namespace app\helpers;

class FlashHelper
{
  public static function set(string $key, string $message): void
  {
    $_SESSION['_flash'][$key] = $message;
  }

  public static function get(string $key): ?string
  {
    if (!isset($_SESSION['_flash'][$key])) {
      return null;
    }

    $msg = $_SESSION['_flash'][$key];
    unset($_SESSION['_flash'][$key]);

    return $msg;
  }
}
