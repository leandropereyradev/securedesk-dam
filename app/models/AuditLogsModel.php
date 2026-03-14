<?php

namespace app\models;

use app\core\Database;
use PDO;

class AuditLogsModel
{
  public static function logAction(array $data, string $ip): bool
  {
    $pdo = Database::getConnection();

    $stmt = $pdo->prepare("
            INSERT INTO audit_logs
            (user_id, action, entity, entity_id, details, origin_ip)
            VALUES
            (:user_id, :action, :entity, :entity_id, :details, :origin_ip)
        ");

    return $stmt->execute([
      ':user_id'   => $data['user_id'],
      ':action'    => $data['action'],
      ':entity'    => $data['entity'],
      ':entity_id' => $data['entity_id'] ?? null,
      ':details'   => $data['details'] ?? null,
      ':origin_ip' => $ip
    ]);
  }

  public static function listAll(array $filters = []): array
  {
    $pdo = Database::getConnection();

    $conditions = [];
    $params = [];

    if (!empty($filters['user_id'])) {
      $conditions[] = 'al.user_id = :user_id';
      $params[':user_id'] = $filters['user_id'];
    }

    if (!empty($filters['action'])) {
      $conditions[] = 'al.action = :action';
      $params[':action'] = $filters['action'];
    }

    $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

    $stmt = $pdo->prepare("
            SELECT
                al.*,
                u.username AS username
            FROM audit_logs al
            LEFT JOIN users u ON al.user_id = u.id
            $where
            ORDER BY al.created_at DESC
        ");

    $stmt->execute($params);
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $users = self::getUsersFromAudit();

    return [
      'logs' => $logs,
      'users' => $users
    ];
  }

  private static function getUsersFromAudit(): array
  {
    $pdo = Database::getConnection();

    $stmt = $pdo->query("
            SELECT DISTINCT
                u.id,
                u.username
            FROM audit_logs al
            JOIN users u ON al.user_id = u.id
            ORDER BY u.username
        ");

    return $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
  }
}
