<?php

namespace app\controllers;

use app\helpers\DateHelper;
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

  public static function listAll(): array
  {
    SessionController::requireLogin();

    $filters = $_SESSION['audit_filters'] ?? [
      'user_id' => null,
      'action' => null
    ];

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

  private static function logAction(array $data): bool
  {
    $ip = self::getClientIp();

    $required = ['user_id', 'action', 'entity'];

    foreach ($required as $key) {

      if (!isset($data[$key])) {
        throw new InvalidArgumentException("Falta el dato requerido: {$key}");
      }
    }

    return AuditLogsModel::logAction($data, $ip);
  }

  private static function getClientIp(): string
  {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

    if ($ip === '::1') $ip = '127.0.0.1';

    // Revisar X-Forwarded-For por proxy
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      foreach (explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']) as $forwardedIp) {

        $forwardedIp = trim($forwardedIp);

        if (filter_var($forwardedIp, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
          $ip = $forwardedIp;
          break;
        }
      }
    }

    return $ip;
  }
}
