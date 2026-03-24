<?php

namespace app\helpers;

use app\core\ViewContext;

class ErrorPageHelper
{
  public static function show(int $code, string $message = ''): string
  {
    $defaultMessages = [
      403 => [
        'title' => 'Acceso denegado',
        'message' => 'No tienes permisos para acceder a esta página.'
      ],
      404 => [
        'title' => 'Página no encontrada',
        'message' => 'La página que buscas no existe o ha sido movida.'
      ]
    ];

    $errorData = $defaultMessages[$code] ?? [
      'title' => 'Error',
      'message' => 'Ha ocurrido un error inesperado.'
    ];

    if ($message !== '') {
      $errorData['message'] = $message;
    }

    ViewContext::set('error_code', $code);
    ViewContext::set('error_title', $errorData['title']);
    ViewContext::set('error_message', $errorData['message']);
    ViewContext::set('is_logged', isset($_SESSION['user_id']));

    return ROOT . "app/views/content/error-view.php";
  }
}
