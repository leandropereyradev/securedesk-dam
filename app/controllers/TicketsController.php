<?php

namespace app\controllers;

use PDO;
use InvalidArgumentException;
use app\helpers\DateHelper;

class TicketsController
{
  public static function getTicketById(PDO $pdo, int $ticketId): ?array
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

    if (!$ticket) return null;

    $ticket['updated_at'] = DateHelper::utcToMadrid($ticket['updated_at']);
    $ticket['created_at'] = DateHelper::utcToMadrid($ticket['created_at']);

    return $ticket;
  }

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
      $tickets = $stmt->fetchAll(\PDO::FETCH_ASSOC);

      foreach ($tickets as &$ticket) {
        $ticket['created_at'] = DateHelper::utcToMadrid($ticket['created_at']);
        $ticket['updated_at'] = DateHelper::utcToMadrid($ticket['updated_at']);
      }

      return $tickets;
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

  public static function updateTicket(PDO $pdo, int $ticketId, array $data): bool
  {
    $validStatus = ['new', 'in_process', 'resolved'];
    $validPriority = ['low', 'medium', 'high', 'critical'];

    $fields = [];
    $params = [':id' => $ticketId];

    if (isset($data['status'])) {
      if (!in_array($data['status'], $validStatus)) {
        throw new InvalidArgumentException("Status inválido: {$data['status']}");
      }
      $fields[] = 'status = :status';
      $params[':status'] = $data['status'];
    }

    if (isset($data['priority'])) {
      if (!in_array($data['priority'], $validPriority)) {
        throw new InvalidArgumentException("Priority inválido: {$data['priority']}");
      }
      $fields[] = 'priority = :priority';
      $params[':priority'] = $data['priority'];
    }

    if (isset($data['description'])) {
      $fields[] = 'description = :description';
      $params[':description'] = $data['description'];
    }

    if (array_key_exists('assigned_to', $data)) {
      $assignedTo = $data['assigned_to'] === '' ? null : $data['assigned_to'];
      $fields[] = 'assigned_to = :assigned_to';
      $params[':assigned_to'] = $assignedTo;
    }

    // Nada que actualizar
    if (empty($fields)) {
      return false;
    }

    $fields[] = 'updated_at = CURRENT_TIMESTAMP';

    $sql = "
        UPDATE tickets
        SET " . implode(', ', $fields) . "
        WHERE id = :id
    ";

    $stmt = $pdo->prepare($sql);
    return $stmt->execute($params);
  }
}
