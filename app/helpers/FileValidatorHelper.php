<?php

namespace app\helpers;

class FileValidatorHelper
{
  private const MAX_SIZE = 5 * 1024 * 1024;

  private const ALLOWED_FILES = [
    'pdf' => ['application/pdf'],
    'png' => ['image/png'],
    'txt' => []
  ];

  public static function validate(array $file): ?string
  {
    if (!$file) {
      return 'Archivo no recibido.';
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
      return 'Error en la subida del archivo.';
    }

    if ($file['size'] > self::MAX_SIZE) {
      return 'El archivo supera el tamaño máximo de 5MB.';
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!array_key_exists($ext, self::ALLOWED_FILES)) {
      return 'Formato no permitido. Solo PDF, PNG o TXT.';
    }

    if ($ext !== 'txt') {
      $finfo = new \finfo(FILEINFO_MIME_TYPE);
      $mimeType = $finfo->file($file['tmp_name']);

      if (!in_array($mimeType, self::ALLOWED_FILES[$ext])) {
        return 'El archivo no coincide con el formato esperado.';
      }
    }

    return null;
  }
}
