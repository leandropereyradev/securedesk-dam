<?php

namespace app\models;

use app\core\Database;

class UserModel
{
  public static function listAll(array $filters = []): array
  {
    $pdo = Database::getConnection();

    $conditions = [];
    $params = [];

    if (!empty($filters['role'])) {
      $conditions[] = 'u.role = :role';
      $params[':role'] = $filters['role'];
    }

    $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

    $stmt = $pdo->prepare("
            SELECT
                u.id,
                u.username,
                u.role,
                u.created_at
            FROM users u
            $where");

    $stmt->execute($params);
    $users = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    return $users;
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
