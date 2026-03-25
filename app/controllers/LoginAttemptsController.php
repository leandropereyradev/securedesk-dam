<?php

namespace app\controllers;

use app\models\LoginAttemptsModel;

class LoginAttemptsController
{
  private const MAX_ATTEMPTS = 5;
  private const BLOCK_MINUTES = 5;

  public static function isUserBlocked(int $userId): bool
  {
    return !empty(LoginAttemptsModel::getUserBlocks($userId));
  }

  public static function isIpBlocked(string $ip): bool
  {
    return !empty(LoginAttemptsModel::getIpBlocks($ip));
  }

  public static function recordFailedAttempt(int $userId, string $ip): bool
  {
    $row = LoginAttemptsModel::get($userId, $ip);

    // Primer intento
    if (!$row) {
      LoginAttemptsModel::insertAttempt($userId, $ip);
      return false;
    }

    $attempts = $row['attempts'] + 1;

    if ($attempts >= self::MAX_ATTEMPTS) {
      LoginAttemptsModel::block($row['id'], $attempts, self::BLOCK_MINUTES);
      return true;
    }

    // Solo actualiza intentos
    LoginAttemptsModel::updateAttempts($row['id'], $attempts);

    return false;
  }

  public static function getAttempts(int $userId, string $ip): int
  {
    $row = LoginAttemptsModel::get($userId, $ip);

    return $row ? (int)$row['attempts'] : 0;
  }

  public static function reset(int $userId): void
  {
    LoginAttemptsModel::deleteUserAttempts($userId);
  }
}
