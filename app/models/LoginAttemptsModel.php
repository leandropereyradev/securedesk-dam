<?php

namespace app\models;

use app\core\Database;

class LoginAttemptsModel
{
  public static function get(int $user_id, string $ip): ?array
  {
    $pdo = Database::getConnection();

    $stmt = $pdo->prepare("
      SELECT *
      FROM login_attempts
      WHERE user_id = :user_id
      AND ip_address = :ip
      LIMIT 1
    ");

    $stmt->execute([
      ':user_id' => $user_id,
      ':ip' => $ip
    ]);

    $row = $stmt->fetch(\PDO::FETCH_ASSOC);

    return $row ?: null;
  }

  public static function getUserBlocks(int $user_id): array
  {
    $pdo = Database::getConnection();

    $stmt = $pdo->prepare("
      SELECT blocked_until
      FROM login_attempts
      WHERE user_id = :user_id
      AND blocked_until IS NOT NULL
    ");

    $stmt->execute([
      ':user_id' => $user_id
    ]);

    return $stmt->fetchAll(\PDO::FETCH_COLUMN);
  }

  public static function getIpBlocks(string $ip): array
  {
    $pdo = Database::getConnection();

    $stmt = $pdo->prepare("
      SELECT blocked_until
      FROM login_attempts
      WHERE ip_address = :ip
      AND blocked_until IS NOT NULL
    ");

    $stmt->execute([
      ':ip' => $ip
    ]);

    return $stmt->fetchAll(\PDO::FETCH_COLUMN);
  }

  public static function insertAttempt(int $user_id, string $ip): void
  {
    $pdo = Database::getConnection();

    $stmt = $pdo->prepare("
      INSERT INTO login_attempts
      (user_id, ip_address, attempts, last_attempt)
      VALUES (:user_id, :ip, 1, CURRENT_TIMESTAMP)
    ");

    $stmt->execute([
      ':user_id' => $user_id,
      ':ip' => $ip
    ]);
  }

  public static function updateAttempt(
    int $id,
    int $attempts,
    ?string $blockedUntil
  ): void {

    $pdo = Database::getConnection();

    $stmt = $pdo->prepare("
      UPDATE login_attempts
      SET attempts = :attempts,
          blocked_until = :blocked_until,
          last_attempt = CURRENT_TIMESTAMP
      WHERE id = :id
    ");

    $stmt->execute([
      ':attempts' => $attempts,
      ':blocked_until' => $blockedUntil,
      ':id' => $id
    ]);
  }

  public static function deleteUserAttempts(int $user_id): void
  {
    $pdo = Database::getConnection();

    $stmt = $pdo->prepare("
      DELETE FROM login_attempts
      WHERE user_id = :user_id
    ");

    $stmt->execute([
      ':user_id' => $user_id
    ]);
  }
}
