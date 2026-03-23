<?php

namespace app\models;

use app\core\Database;
use PDO;
use app\helpers\DateHelper;

class AttachmentsModel
{
  public static function getByTicket(int $ticketId): array
  {
    $pdo = Database::getConnection();

    $stmt = $pdo->prepare("
            SELECT a.id, a.filename, a.size, a.uploaded_at, u.username AS uploaded_by
            FROM attachments a
            INNER JOIN users u ON a.uploaded_by = u.id
            WHERE a.ticket_id = :ticket_id
            ORDER BY a.uploaded_at DESC
        ");
    $stmt->execute([':ticket_id' => $ticketId]);
    $attachments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return array_map(function ($att) {
      $att['uploaded_at'] = DateHelper::utcToMadrid($att['uploaded_at']);
      return $att;
    }, $attachments);
  }

  public static function upload(
    int $ticketId,
    array $file,
    string $storedName,
    int $userId,
  ): int {
    $pdo = Database::getConnection();

    $stmt = $pdo->prepare("
            INSERT INTO attachments
            (ticket_id, filename, stored_name, size, uploaded_by)
            VALUES
            (:ticket_id, :filename, :stored_name, :size, :uploaded_by)
        ");

    $stmt->execute([
      ':ticket_id'   => $ticketId,
      ':filename'    => $file['name'],
      ':stored_name' => $storedName,
      ':size'        => $file['size'],
      ':uploaded_by' => $userId,
    ]);

    return (int)$pdo->lastInsertId();
  }

  public static function download(int $attachmentId): array
  {
    $pdo = Database::getConnection();

    $stmt = $pdo->prepare("SELECT * FROM attachments WHERE id = :id");
    $stmt->execute([':id' => $attachmentId]);
    $att = $stmt->fetch(\PDO::FETCH_ASSOC);

    return $att;
  }
}
