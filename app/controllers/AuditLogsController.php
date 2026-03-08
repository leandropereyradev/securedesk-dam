<?php

namespace app\controllers;

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
      'action'    => 'create',
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
        'action'    => 'update',
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
      'action'    => 'add',
      'entity'    => 'comment',
      'entity_id' => $commentId,
      'details'   => "Ticket #{$ticketId}: {$content}"
    ]);
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
