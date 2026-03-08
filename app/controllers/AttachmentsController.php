<?php

namespace app\controllers;

use app\models\AttachmentsModel;

class AttachmentsController
{
  public static function getAttachmentsByTicket(int $ticketId): array
  {
    return AttachmentsModel::getByTicket($ticketId);
  }

  public static function upload(int $ticketId): void
  {
    SessionController::requireLogin();

    if (($_SESSION['role'] ?? '') === 'reader') {
      http_response_code(403);
      exit('No tienes permiso para subir archivos.');
    }

    $file = $_FILES['attachment'] ?? null;
    if (!$file) {
      $_SESSION['attachment_error'] = 'Error al subir el archivo.';
      return;
    }

    $success = AttachmentsModel::upload($ticketId, $file, $_SESSION['user_id']);
    if (!$success) {
      $_SESSION['attachment_error'] = 'No se pudo subir el archivo (verifica tamaño o permisos).';
    }
  }

  public static function download(int $attachmentId): void
  {
    SessionController::requireLogin();

    $file = AttachmentsModel::download($attachmentId);

    if (!$file) {
      http_response_code(404);
      exit('Archivo no disponible.');
    }

    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file['filename']) . '"');
    header('Content-Length: ' . $file['size']);
    readfile($file['path']);
    exit;
  }
}
