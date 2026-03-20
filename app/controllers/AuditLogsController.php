<?php

namespace app\controllers;

use app\helpers\DateHelper;
use app\helpers\IpHelper;
use app\models\AuditLogsModel;

use InvalidArgumentException;

class AuditLogsController
{
  public static function logTicketCreate(
    int $ticketId,
    int $userId,
    string $title = ''
  ): bool {

    return self::logAction([
      'user_id'   => $userId,
      'action'    => 'ticket_create',
      'entity'    => 'ticket',
      'entity_id' => $ticketId,
      'details'   => "Título: {$title}"
    ]);
  }

  public static function logTicketEdit(
    int $ticketId,
    int $userId,
    array $changes
  ): void {

    foreach ($changes as $field => $values) {
      self::logAction([
        'user_id'   => $userId,
        'action'    => 'ticket_update',
        'entity'    => 'ticket',
        'entity_id' => $ticketId,
        'details'   => "{$field} cambiado de '{$values['old']}' a '{$values['new']}'"
      ]);
    }
  }

  public static function logCommentAdd(
    int $ticketId,
    int $commentId,
    int $userId,
    string $content = ''
  ): bool {

    return self::logAction([
      'user_id'   => $userId,
      'action'    => 'add_comment',
      'entity'    => 'comment',
      'entity_id' => $commentId,
      'details'   => "Ticket #{$ticketId}: {$content}"
    ]);
  }

  public static function logLogin(int $userId): bool
  {
    return self::logAction([
      'user_id'   => $userId,
      'action'    => 'login',
      'entity'    => 'auth',
      'details'   => "Inicia sesión"
    ]);
  }

  public static function logLogout(int $userId): bool
  {
    return self::logAction([
      'user_id'   => $userId,
      'action'    => 'logout',
      'entity'    => 'auth',
      'details'   => "Cierra sesión"
    ]);
  }

  public static function listAll(array $filters = []): array
  {
    SessionController::requireLogin();

    $filters = array_merge([
      'user_id' => null,
      'action' => null
    ], $filters);

    try {
      $data = AuditLogsModel::listAll($filters);

      foreach ($data['logs'] as &$log) {
        $log['created_at'] = DateHelper::utcToMadrid($log['created_at']);
      }

      return $data;
    } catch (\PDOException $e) {
      error_log('Error al listar auditorías: ' . $e->getMessage());
      return ['logs' => [], 'users' => []];
    }
  }

  public static function logAuthEvent(
    ?int $userId,
    string $action,
    string $details = ''
  ): void {

    try {

      self::logAction([
        'user_id'   => $userId,
        'action'    => $action,
        'entity'    => 'auth',
        'entity_id' => null,
        'details'   => $details
      ]);
    } catch (\PDOException $e) {

      error_log('Error registrando evento de autenticación: ' . $e->getMessage());
    }
  }

  public static function logExport(int $userId, string $type, string $context): void
  {
    self::logAction([
      'user_id' => $userId,
      'action'  => 'export',
      'entity'  => 'report',
      'details' => "Exporta $type. Contexto: $context"
    ]);
  }

  private static function logAction(array $data): bool
  {
    $ip = IpHelper::getClientIp();

    $required = ['user_id', 'action', 'entity'];

    foreach ($required as $key) {

      if (!isset($data[$key])) {
        throw new InvalidArgumentException("Falta el dato requerido: {$key}");
      }
    }

    return AuditLogsModel::logAction($data, $ip);
  }
}
