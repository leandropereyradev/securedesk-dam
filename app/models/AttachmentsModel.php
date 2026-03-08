<?php

namespace app\models;

use PDO;
use app\helpers\DateHelper;

class AttachmentsModel
{
  private const STORAGE_PATH = ROOT . 'storage/attachments';
  private const MAX_SIZE = 5 * 1024 * 1024; // 5MB

  public static function getByTicket(int $ticketId): array
  {
    $pdo = getConnection(TICKETS_DB_PATH);

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

  public static function upload(int $ticketId, array $file, int $userId): bool
  {
    $pdo = getConnection(TICKETS_DB_PATH);

    if ($file['error'] !== UPLOAD_ERR_OK) {
      return false;
    }

    if ($file['size'] > self::MAX_SIZE) {
      return false;
    }

    if (!is_dir(self::STORAGE_PATH)) {
      mkdir(self::STORAGE_PATH, 0755, true);
    }

    $storedName = bin2hex(random_bytes(16));
    $destination = self::STORAGE_PATH . '/' . $storedName;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
      return false;
    }

    $stmt = $pdo->prepare("
            INSERT INTO attachments
            (ticket_id, filename, stored_name, size, uploaded_by)
            VALUES
            (:ticket_id, :filename, :stored_name, :size, :uploaded_by)
        ");

    return $stmt->execute([
      ':ticket_id'   => $ticketId,
      ':filename'    => $file['name'],
      ':stored_name' => $storedName,
      ':size'        => $file['size'],
      ':uploaded_by' => $userId,
    ]);
  }

  public static function download(int $attachmentId): ?array
  {
    $pdo = getConnection(TICKETS_DB_PATH);

    $stmt = $pdo->prepare("SELECT * FROM attachments WHERE id = :id");
    $stmt->execute([':id' => $attachmentId]);
    $att = $stmt->fetch(\PDO::FETCH_ASSOC);

    if (!$att) {
      return null;
    }

    $filePath = self::STORAGE_PATH . '/' . $att['stored_name'];

    if (!file_exists($filePath)) {
      return null;
    }

    return [
      'path' => $filePath,
      'filename' => $att['filename'],
      'size' => $att['size'],
    ];
  }
}
