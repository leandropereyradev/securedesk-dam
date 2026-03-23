<?php

namespace app\controllers;

use app\helpers\FileValidatorHelper;
use app\helpers\RedirectHelper;
use app\models\AttachmentsModel;

class AttachmentsController
{
  private const STORAGE_PATH = ROOT . 'storage/attachments';

  public static function getAttachmentsByTicket(int $ticketId): array
  {
    return AttachmentsModel::getByTicket($ticketId);
  }

  public static function upload(int $ticketId): ?int
  {
    SessionController::requireLogin();

    try {

      $file = $_FILES['attachment'] ?? null;

      $error = FileValidatorHelper::validate($file);

      if ($error) {
        RedirectHelper::attachmentError($error, $ticketId);
        return null;
      }

      if (!is_dir(self::STORAGE_PATH)) {
        mkdir(self::STORAGE_PATH, 0755, true);
      }

      $storedName = bin2hex(random_bytes(16));
      $destination = self::STORAGE_PATH . '/' . $storedName;

      if (!move_uploaded_file($file['tmp_name'], $destination)) {
        RedirectHelper::attachmentError(
          'No se pudo mover el archivo.',
          $ticketId
        );
        return null;
      }

      $attachmentId = AttachmentsModel::upload(
        $ticketId,
        $file,
        $storedName,
        $_SESSION['user_id']
      );

      if (!$attachmentId) {
        RedirectHelper::attachmentError(
          'No se pudo guardar el archivo en base de datos.',
          $ticketId
        );
        return null;
      }

      return $attachmentId;
    } catch (\Throwable $e) {
      RedirectHelper::attachmentError(
        'Error inesperado al subir el archivo.',
        $ticketId
      );
      return null;
    }
  }

  public static function download(int $attachmentId, int $ticketId): void
  {
    SessionController::requireLogin();

    try {

      $file = AttachmentsModel::download($attachmentId);

      if (!$file) {
        RedirectHelper::attachmentError(
          'Archivo no encontrado.',
          $ticketId
        );
      }

      $fileDesteny = self::STORAGE_PATH . '/' . $file['stored_name'];

      if (!file_exists($fileDesteny)) {
        RedirectHelper::attachmentError(
          'Archivo no disponible en el servidor.',
          $ticketId
        );
      }

      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename="' . basename($file['filename']) . '"');
      header('Content-Length: ' . $file['size']);

      readfile($fileDesteny);
      exit;
    } catch (\Throwable $e) {

      RedirectHelper::attachmentError(
        'Error al descargar el archivo.',
        $ticketId
      );
    }
  }
}
