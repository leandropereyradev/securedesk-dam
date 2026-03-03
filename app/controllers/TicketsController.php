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

  public static function create()
  {
    SessionController::requireLogin();

    if (($_SESSION['role'] ?? '') === 'reader') {
      header('Location: tickets');
      exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header('Location: tickets');
      exit;
    }

    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status      = 'new';
    $priority    = $_POST['priority'] ?? 'medium';
    $category    = trim($_POST['category'] ?? '');
    $assignedTo  = $_POST['assigned_to'] ?? null;

    if ($title === '') {
      $_SESSION['ticket_error'] = 'El título es obligatorio.';
      header('Location: tickets');
      exit;
    }

    $validPriority = ['low', 'medium', 'high', 'critical'];

    if (!in_array($priority, $validPriority, true)) {
      $priority = 'medium';
    }

    if ($assignedTo === '' || $assignedTo === null) {
      $assignedTo = null;
    }

    try {
      $pdo = getConnection(TICKETS_DB_PATH);

      $stmt = $pdo->prepare("
        INSERT INTO tickets
          (title, description, status, priority, category, created_by, assigned_to)
        VALUES
          (:title, :description, :status, :priority, :category, :created_by, :assigned_to)
      ");

      $stmt->execute([
        ':title'       => $title,
        ':description' => $description,
        ':status'      => $status,
        ':priority'    => $priority,
        ':category'    => $category,
        ':created_by'  => $_SESSION['user_id'],
        ':assigned_to' => $assignedTo,
      ]);

      header('Location: tickets');
      exit;
    } catch (\PDOException $e) {
      error_log('Error al crear ticket: ' . $e->getMessage());
      $_SESSION['ticket_error'] = 'Error al crear el ticket.';
      header('Location: tickets');
      exit;
    }
  }
}
