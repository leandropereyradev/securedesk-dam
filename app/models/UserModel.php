<?php

namespace app\models;

use app\core\Database;

class UserModel
{
  public static function getAll(): array
  {
    $pdo = Database::getConnection();

    $sql = "SELECT * FROM users";
    $stmt = $pdo->query($sql);

    $users = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    return $users ?: [];
  }

  public static function getByUsername(string $username): array
  {
    $pdo = Database::getConnection();

    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':username' => $username]);

    $user = $stmt->fetch(\PDO::FETCH_ASSOC);

    return $user ?: [];
  }
}
