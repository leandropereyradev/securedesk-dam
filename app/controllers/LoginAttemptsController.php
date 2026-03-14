<?php

namespace app\controllers;

use app\models\LoginAttemptsModel;

class LoginAttemptsController
{
  private const MAX_ATTEMPTS = 5;
  private const BLOCK_MINUTES = 5;

  public static function isUserBlocked(int $userId): bool
  {
    $blocks = LoginAttemptsModel::getUserBlocks($userId);

    foreach ($blocks as $blockedUntil) {
      if (strtotime($blockedUntil) > time()) {
        return true;
      }
    }

    return false;
  }

  public static function isIpBlocked(string $ip): bool
  {
    $blocks = LoginAttemptsModel::getIpBlocks($ip);

    foreach ($blocks as $blockedUntil) {
      if (strtotime($blockedUntil) > time()) {
        return true;
      }
    }

    return false;
  }

  public static function recordFailedAttempt(int $userId, string $ip): bool
  {
    $row = LoginAttemptsModel::get($userId, $ip);

    // Primer intento desde esta IP
    if (!$row) {
      LoginAttemptsModel::insertAttempt($userId, $ip);
      return false;
    }

    $attempts = $row['attempts'] + 1;
    $blockedUntil = null;

    if ($attempts >= self::MAX_ATTEMPTS) {
      $blockedUntil = gmdate(
        'Y-m-d H:i:s',
        strtotime('+' . self::BLOCK_MINUTES . ' minutes')
      );
    }

    LoginAttemptsModel::updateAttempt(
      $row['id'],
      $attempts,
      $blockedUntil
    );

    return $blockedUntil !== null;
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
