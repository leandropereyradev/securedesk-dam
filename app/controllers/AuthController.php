<?php

namespace app\controllers;

use app\helpers\IpHelper;
use app\helpers\RedirectHelper;

class AuthController
{
  public static function login()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

      RedirectHelper::to('login');
    }

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $ip = IpHelper::getClientIp();

    if ($username === '' || $password === '') {

      RedirectHelper::error(
        'login',
        'Debes completar todos los campos.'
      );
    }

    try {
      if (LoginAttemptsController::isIpBlocked($ip)) {

        RedirectHelper::error(
          'login',
          'Demasiados intentos. Inténtalo en 5 minutos.'
        );
      }

      $user = UsersController::getByUsername($username);

      if (!$user) {
        RedirectHelper::error(
          'login',
          'Usuario o contraseña incorrectos.'
        );
      }

      $userId = $user['id'];

      if (
        LoginAttemptsController::isUserBlocked($userId) ||
        LoginAttemptsController::isIpBlocked($ip)
      ) {
        RedirectHelper::error(
          'login',
          'Demasiados intentos. Inténtalo en 5 minutos.'
        );
      }

      if (!password_verify($password, $user['password_hash'])) {
        $blocked = LoginAttemptsController::recordFailedAttempt($userId, $ip);
        $attempts = LoginAttemptsController::getAttempts($userId, $ip);

        if ($blocked) {
          AuditLogsController::logAuthEvent(
            $userId,
            'login_blocked',
            "Usuario {$username} bloqueado por demasiados intentos"
          );

          RedirectHelper::error(
            'login',
            'Demasiados intentos. Vuélvalo a intentar en 5 minutos.'
          );
        }

        if ($attempts === 4) {
          RedirectHelper::error(
            'login',
            'Te queda un intento antes de que debas esperar 5 minutos'
          );
        }

        RedirectHelper::error(
          'login',
          'Usuario o contraseña incorrectos.'
        );
      }

      LoginAttemptsController::reset($userId);

      session_regenerate_id(true);

      $_SESSION['user_id']  = $userId;
      $_SESSION['username'] = $user['username'];
      $_SESSION['role']     = $user['role'];

      AuditLogsController::logLogin($userId);

      RedirectHelper::to('dashboard');
    } catch (\PDOException $e) {

      RedirectHelper::error(
        'login',
        'Error de base de datos.'
      );
    }
  }

  public static function logout()
  {
    AuditLogsController::logLogout($_SESSION['user_id']);

    SessionController::logout();
  }
}
