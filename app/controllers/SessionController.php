<?php

namespace app\controllers;

class SessionController
{
  public static function start()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
  }

  public static function isLoggedIn(): bool
  {
    self::start();
    return isset($_SESSION['user_id']);
  }

  public static function requireLogin()
  {
    self::start();
    if (!isset($_SESSION['user_id'])) {
      header('Location: login');
      exit;
    }
  }

  public static function logout()
  {
    self::start();
    session_destroy();
    header('Location: login');
    exit;
  }
}
