<?php

namespace app\models;

use PDO;

class TicketModel
{
  public static function findById(PDO $pdo, int $ticketId): ?array
  {
    $sql = "
            SELECT
                t.*,
                ua.username AS assigned_to_username
            FROM tickets t
            LEFT JOIN users ua ON t.assigned_to = ua.id
            WHERE t.id = :id
        ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $ticketId]);

    $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

    return $ticket ?: null;
  }

  public static function listAll(PDO $pdo, array $filters = []): array
  {
    $conditions = [];
    $params = [];

    if (!empty($filters['status'])) {
      $conditions[] = 't.status = :status';
      $params[':status'] = $filters['status'];
    }

    if (!empty($filters['priority'])) {
      $conditions[] = 't.priority = :priority';
      $params[':priority'] = $filters['priority'];
    }

    $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

    $stmt = $pdo->prepare("
            SELECT
                t.*,
                uc.username AS created_by_username,
                ua.username AS assigned_to_username
            FROM tickets t
            INNER JOIN users uc ON t.created_by = uc.id
            LEFT JOIN users ua ON t.assigned_to = ua.id
            $where
            ORDER BY t.created_at DESC
        ");

    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function create(PDO $pdo, array $data): int
  {
    $stmt = $pdo->prepare("
            INSERT INTO tickets
            (title, description, status, priority, category, created_by, assigned_to)
            VALUES
            (:title, :description, :status, :priority, :category, :created_by, :assigned_to)
        ");

    $stmt->execute($data);

    return (int)$pdo->lastInsertId();
  }

  public static function update(PDO $pdo, int $ticketId, array $fields): bool
  {
    $set = [];
    $params = [':id' => $ticketId];

    foreach ($fields as $field => $value) {
      $set[] = "$field = :$field";
      $params[":$field"] = $value;
    }

    $set[] = "updated_at = CURRENT_TIMESTAMP";

    $sql = "UPDATE tickets SET " . implode(', ', $set) . " WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    return $stmt->execute($params);
  }
}
