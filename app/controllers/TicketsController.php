<?php

namespace app\controllers;

class TicketsController
{
  public static function listAll(): array
  {
    SessionController::requireLogin();

    $status = $_GET['status']   ?? null;
    $priority = $_GET['priority'] ?? null;

    $conditions = [];
    $params = [];

    $validStatus = ['new', 'in_process', 'resolved'];
    $validPriority = ['low', 'medium', 'high', 'critical'];

    if ($status && in_array($status, $validStatus, true)) {
      $conditions[] = 't.status = :status';
      $params[':status'] = $status;
    }

    if ($priority && in_array($priority, $validPriority, true)) {
      $conditions[] = 't.priority = :priority';
      $params[':priority'] = $priority;
    }

    $where = $conditions
      ? 'WHERE ' . implode(' AND ', $conditions)
      : '';

    try {
      $pdo = getConnection(TICKETS_DB_PATH);

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
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
      error_log('Error al listar tickets: ' . $e->getMessage());
      return [];
    }
  }
}
