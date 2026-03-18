<?php

namespace app\controllers;

use app\models\TicketModel;
use app\helpers\DateHelper;
use app\helpers\ValidationHelper;

class TicketsController
{
  public static function getTicketById(int $ticketId): ?array
  {
    $ticket = TicketModel::findById($ticketId);

    if (!$ticket) {
      return null;
    }

    $ticket['updated_at'] = DateHelper::utcToMadrid($ticket['updated_at']);
    $ticket['created_at'] = DateHelper::utcToMadrid($ticket['created_at']);

    return $ticket;
  }

  public static function listAll(): array
  {
    SessionController::requireLogin();

    $filters = $_SESSION['tickets_filters'] ?? [
      'status' => null,
      'priority' => null
    ];

    try {

      $tickets = TicketModel::listAll($filters);

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

  public static function create(): array
  {
    SessionController::requireLogin();

    if (($_SESSION['role'] ?? '') === 'reader') {
      return [
        'success' => false,
        'error' => 'No tienes permisos para crear tickets.'
      ];
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      return [
        'success' => false,
        'error' => 'Método no permitido.'
      ];
    }

    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $priority    = $_POST['priority'] ?? 'medium';
    $category    = trim($_POST['category'] ?? '');
    $assignedTo  = $_POST['assigned_to'] ?? null;

    $validPriority = ['low', 'medium', 'high', 'critical'];

    // Validar título obligatorio
    if (!ValidationHelper::required($title)) {
      return [
        'success' => false,
        'error' => 'El título es obligatorio.'
      ];
    }

    // Longitud del título
    if (!ValidationHelper::maxLength($title, 255)) {
      return [
        'success' => false,
        'error' => 'El título no puede superar los 255 caracteres.'
      ];
    }

    // Validar prioridad
    if (!ValidationHelper::inArray($priority, $validPriority)) {
      return [
        'success' => false,
        'error' => 'Prioridad inválida.'
      ];
    }

    if ($assignedTo === '' || $assignedTo === null) {
      $assignedTo = null;
    }

    try {

      $ticketId = TicketModel::create([
        'title'       => $title,
        'description' => $description,
        'status'      => 'new',
        'priority'    => $priority,
        'category'    => $category,
        'created_by'  => $_SESSION['user_id'],
        'assigned_to' => $assignedTo
      ]);

      AuditLogsController::logTicketCreate(
        $ticketId,
        $_SESSION['user_id'],
        $title
      );

      return [
        'success' => true,
        'ticket_id' => $ticketId
      ];
    } catch (\PDOException $e) {

      error_log('Error al crear ticket: ' . $e->getMessage());

      return [
        'success' => false,
        'error' => 'Error al crear el ticket.'
      ];
    }
  }

  public static function updateTicket(
    int $ticketId,
    int $userId,
    array $data
  ): array {

    $validStatus = ['new', 'in_process', 'resolved'];
    $validPriority = ['low', 'medium', 'high', 'critical'];

    try {

      $current = TicketModel::findById($ticketId);

      if (!$current) {
        return [
          'success' => false,
          'error' => 'Ticket no encontrado'
        ];
      }

      $fields = [];
      $changesForLog = [];

      $criticalFields = ['status', 'priority', 'assigned_to'];

      foreach ($criticalFields as $field) {

        if (!array_key_exists($field, $data)) {
          continue;
        }

        $newValue = $data[$field] === '' ? null : $data[$field];

        if ($field === 'status' && !ValidationHelper::inArray($newValue, $validStatus)) {
          return [
            'success' => false,
            'error' => "Status inválido"
          ];
        }

        if ($field === 'priority' && !ValidationHelper::inArray($newValue, $validPriority)) {
          return [
            'success' => false,
            'error' => "Prioridad inválida"
          ];
        }

        $oldValue = $current[$field] ?? null;

        if ($oldValue != $newValue) {

          TicketHistoryController::logChange([
            'ticket_id' => $ticketId,
            'user_id'   => $userId,
            'field'     => $field,
            'old_value' => $oldValue,
            'new_value' => $newValue,
          ]);

          $fields[$field] = $newValue;

          $changesForLog[$field] = [
            'old' => $oldValue ?? "Sin asignar",
            'new' => $newValue ?? "Sin asignar"
          ];
        }
      }

      if (isset($data['description'])) {
        $fields['description'] = trim($data['description']);
      }

      if (empty($fields)) {
        return ['success' => true];
      }

      TicketModel::update($ticketId, $fields);

      AuditLogsController::logTicketEdit(
        $ticketId,
        $userId,
        $changesForLog
      );

      return [
        'success' => true
      ];
    } catch (\PDOException $e) {

      error_log("Error updating ticket {$ticketId}: " . $e->getMessage());

      return [
        'success' => false,
        'error' => 'Error al actualizar el ticket'
      ];
    }
  }

  public static function getDashboardStats(): array
  {
    SessionController::requireLogin();

    try {
      $stats = TicketModel::getDashboardStats();

      return [
        'stats' => $stats,
        'updated_at' => DateHelper::utcToMadrid(gmdate('Y-m-d H:i:s'))
      ];
    } catch (\PDOException $e) {
      error_log('Error dashboard: ' . $e->getMessage());

      return [
        'stats' => [],
        'updated_at' => null
      ];
    }
  }
}
