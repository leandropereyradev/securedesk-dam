<?php

namespace app\controllers;

use app\helpers\DateHelper;
use PDO;

class AttachmentsController
{
  private const STORAGE_PATH = ROOT . 'storage/attachments';
  private const MAX_SIZE = 5 * 1024 * 1024; // 5MB

  // Lista adjuntos de un ticket
  public static function getAttachmentsByTicket(PDO $pdo, int $ticketId): array
  {
    $stmt = $pdo->prepare("
            SELECT a.id, a.filename, a.size, a.uploaded_at, u.username AS uploaded_by
            FROM attachments a
            INNER JOIN users u ON a.uploaded_by = u.id
            WHERE a.ticket_id = :ticket_id
            ORDER BY a.uploaded_at DESC
        ");
    $stmt->execute([':ticket_id' => $ticketId]);
    $attachments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($attachments as &$att) {
      $att['uploaded_at'] = DateHelper::utcToMadrid($att['uploaded_at']);
    }


    return $attachments;
  }

  // Subir adjunto
  public static function upload(PDO $pdo, int $ticketId): void
  {
    SessionController::requireLogin();

    // Lector NO puede subir
    if (($_SESSION['role'] ?? '') === 'reader') {
      http_response_code(403);
      exit('No tienes permiso para subir archivos.');
    }

    if (!isset($_FILES['attachment']) || $_FILES['attachment']['error'] !== UPLOAD_ERR_OK) {
      $_SESSION['attachment_error'] = 'Error al subir el archivo.';
      return;
    }

    $file = $_FILES['attachment'];

    if ($file['size'] > self::MAX_SIZE) {
      $_SESSION['attachment_error'] = 'Archivo demasiado grande.';
      return;
    }

    // Crear carpeta si no existe
    if (!is_dir(self::STORAGE_PATH)) {
      mkdir(self::STORAGE_PATH, 0755, true);
    }

    $storedName = bin2hex(random_bytes(16));
    $destination = self::STORAGE_PATH . '/' . $storedName;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
      $_SESSION['attachment_error'] = 'No se pudo guardar el archivo.';
      return;
    }

    // Guardar en DB
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
      ':uploaded_by' => $_SESSION['user_id'],
    ]);
  }

  // Descargar adjunto
  public static function download(PDO $pdo, int $attachmentId): void
  {
    SessionController::requireLogin();

    $stmt = $pdo->prepare("SELECT * FROM attachments WHERE id = :id");
    $stmt->execute([':id' => $attachmentId]);
    $att = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$att) {
      http_response_code(404);
      exit('Archivo no encontrado.');
    }

    $filePath = self::STORAGE_PATH . '/' . $att['stored_name'];

    if (!file_exists($filePath)) {
      http_response_code(404);
      exit('Archivo no disponible.');
    }

    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($att['filename']) . '"');
    header('Content-Length: ' . $att['size']);
    readfile($filePath);
    exit;
  }
}
