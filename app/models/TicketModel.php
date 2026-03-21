<?php

namespace app\models;

use app\core\Database;
use PDO;

class TicketModel
{
  public static function findById(int $ticketId): ?array
  {
    $pdo = Database::getConnection();

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

    if ($ticket['assigned_to_username'] === null) {
      $ticket['assigned_to_username'] = 'Sin Asignar';
    }

    return $ticket ?: null;
  }

  public static function listAll(array $filters = []): array
  {
    $pdo = Database::getConnection();

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

    if (array_key_exists('assigned_to', $filters)) {

      if ($filters['assigned_to'] === 'null') {
        $conditions[] = 't.assigned_to IS NULL';
      } elseif ($filters['assigned_to'] !== null && $filters['assigned_to'] !== '') {
        $conditions[] = 't.assigned_to = :assigned_to';
        $params[':assigned_to'] = (int)$filters['assigned_to'];
      }
    }

    if (!empty($filters['q'])) {

      $search = '%' . $filters['q'] . '%';

      if ($filters['search_in'] === 'title') {
        $conditions[] = 't.title LIKE :search';
        $params[':search'] = $search;
      } elseif ($filters['search_in'] === 'description') {
        $conditions[] = 't.description LIKE :search';
        $params[':search'] = $search;
      } else {
        $conditions[] = '(t.title LIKE :search OR t.description LIKE :search)';
        $params[':search'] = $search;
      }
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

    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($tickets as &$ticket) {
      if ($ticket['assigned_to_username'] === null) {
        $ticket['assigned_to_username'] = 'Sin Asignar';
      }
    }
    unset($ticket);

    return $tickets;
  }

  public static function create(array $data): int
  {
    $pdo = Database::getConnection();

    $stmt = $pdo->prepare("
            INSERT INTO tickets
            (title, description, status, priority, category, created_by, assigned_to)
            VALUES
            (:title, :description, :status, :priority, :category, :created_by, :assigned_to)
        ");

    $stmt->execute($data);

    return (int)$pdo->lastInsertId();
  }

  public static function update(int $ticketId, array $fields): bool
  {
    $pdo = Database::getConnection();

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

  public static function getStats(): array
  {
    $pdo = Database::getConnection();

    $sql = "
    SELECT
      COUNT(*) as total,

      SUM(CASE WHEN status = 'new' THEN 1 ELSE 0 END) as new,
      SUM(CASE WHEN status = 'in_process' THEN 1 ELSE 0 END) as in_process,
      SUM(CASE WHEN status = 'resolved' THEN 1 ELSE 0 END) as resolved,

      SUM(CASE WHEN priority = 'low' THEN 1 ELSE 0 END) as low,
      SUM(CASE WHEN priority = 'medium' THEN 1 ELSE 0 END) as medium,
      SUM(CASE WHEN priority = 'high' THEN 1 ELSE 0 END) as high,
      SUM(CASE WHEN priority = 'critical' THEN 1 ELSE 0 END) as critical

    FROM tickets";

    $stmt = $pdo->query($sql);

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
  }

  public static function getDistributionStats(): array
  {
    $pdo = Database::getConnection();

    $stmt = $pdo->query("
        SELECT category, COUNT(*) AS total
        FROM tickets
        GROUP BY category
    ");
    $byCategory = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("
        SELECT status, COUNT(*) AS total
        FROM tickets
        GROUP BY status
    ");
    $byStatus = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return [
      'category' => $byCategory,
      'status' => $byStatus
    ];
  }
}
