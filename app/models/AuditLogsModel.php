<?php

namespace app\models;

class AuditLogsModel
{
  public static function logAction(array $data, string $ip): bool
  {
    $pdo = getConnection(TICKETS_DB_PATH);

    $stmt = $pdo->prepare("
            INSERT INTO audit_logs
            (user_id, action, entity, entity_id, details, origin_ip)
            VALUES
            (:user_id, :action, :entity, :entity_id, :details, :origin_ip)
        ");

    return $stmt->execute([
      ':user_id'   => $data['user_id'],
      ':action'    => $data['action'],
      ':entity'    => $data['entity'],
      ':entity_id' => $data['entity_id'] ?? null,
      ':details'   => $data['details'] ?? null,
      ':origin_ip' => $ip
    ]);
  }
}
