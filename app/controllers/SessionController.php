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
}
