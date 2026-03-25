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

  public static function upload(int $ticketId): array
  {
    SessionController::requireLogin();

    try {

      $file = $_FILES['attachment'] ?? null;

      $error = FileValidatorHelper::validate($file);

      if ($error) {
        return [
          'error' => $error
        ];
      }

      if (!is_dir(self::STORAGE_PATH)) {
        mkdir(self::STORAGE_PATH, 0755, true);
      }

      $storedName = bin2hex(random_bytes(16));
      $destination = self::STORAGE_PATH . '/' . $storedName;

      if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return [
          'error' => 'No se pudo mover el archivo.'
        ];
      }

      $attachmentId = AttachmentsModel::upload(
        $ticketId,
        $file,
        $storedName,
        $_SESSION['user_id']
      );

      if (!$attachmentId) {
        return [
          'error' => 'No se pudo guardar el archivo en base de datos.'
        ];
      }

      return [
        'attachmentId' => $attachmentId,
        "success" => 'Archivo subido correctamente'
      ];
    } catch (\Throwable $e) {
      return [
        'error' => 'Error inesperado al subir el archivo.'
      ];
    }
  }

  public static function download(int $attachmentId): array
  {
    SessionController::requireLogin();

    try {

      $file = AttachmentsModel::download($attachmentId);

      if (!$file) {
        return [
          'error' => 'Archivo no encontrado.'
        ];
      }

      $fileDesteny = self::STORAGE_PATH . '/' . $file['stored_name'];

      if (!file_exists($fileDesteny)) {
        return [
          'error' => 'Archivo no disponible en el servidor.'
        ];
      }

      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename="' . basename($file['filename']) . '"');
      header('Content-Length: ' . $file['size']);

      readfile($fileDesteny);

      exit;
    } catch (\Throwable $e) {

      return [
        'error' => 'Error al descargar el archivo.'
      ];
    }
  }
}
