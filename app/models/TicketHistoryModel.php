<?php

namespace app\models;

use app\core\Database;
use PDO;

class TicketHistoryModel
{
  public static function logChange(array $data): bool
  {
    $pdo = Database::getConnection();

    self::validate($data);

    if ($data['old_value'] === $data['new_value']) {
      return true;
    }

    $stmt = $pdo->prepare("
            INSERT INTO ticket_history
            (ticket_id, user_id, field, old_value, new_value)
            VALUES (:ticket_id, :user_id, :field, :old_value, :new_value)
        ");

    return $stmt->execute([
      ':ticket_id' => $data['ticket_id'],
      ':user_id'   => $data['user_id'],
      ':field'     => $data['field'],
      ':old_value' => $data['old_value'],
      ':new_value' => $data['new_value'],
    ]);
  }

  public static function getHistory(int $ticketId): array
  {
    $pdo = Database::getConnection();

    $sql = "
            SELECT
                h.id,
                h.ticket_id,
                h.user_id,
                h.field,
                h.old_value,
                h.new_value,
                h.changed_at,
                u.username AS changed_by_username,
                ua_old.username AS old_assigned_username,
                ua_new.username AS new_assigned_username
            FROM ticket_history h
            INNER JOIN users u ON h.user_id = u.id
            LEFT JOIN users ua_old ON h.old_value = ua_old.id
            LEFT JOIN users ua_new ON h.new_value = ua_new.id
            WHERE h.ticket_id = :ticket_id
            ORDER BY h.changed_at DESC
        ";

    try {
      $stmt = $pdo->prepare($sql);
      $stmt->execute([':ticket_id' => $ticketId]);
      $history = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
      return [];
    }

    return array_map(function ($row) {
      if ($row['field'] === 'assigned_to') {
        $row['old_value'] = $row['old_assigned_username'] ?? 'Sin asignar';
        $row['new_value'] = $row['new_assigned_username'] ?? 'Sin asignar';
      }
      return $row;
    }, $history);
  }

  private static function validate(array $data): void
  {
    $required = ['ticket_id', 'user_id', 'field', 'old_value', 'new_value'];

    foreach ($required as $key) {
      if (!array_key_exists($key, $data)) {
        throw new \InvalidArgumentException("Falta el dato requerido: {$key}");
      }
    }
  }
}
