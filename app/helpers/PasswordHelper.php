<?php

namespace app\helpers;

class PasswordHelper
{
  public static function validate(string $password): array
  {
    $errors = [];

    if (strlen($password) < 8) $errors[] = 'mínimo 8 caracteres';
    if (!preg_match('/[A-Z]/', $password)) $errors[] = 'una mayúscula';
    if (!preg_match('/[a-z]/', $password)) $errors[] = 'una minúscula';
    if (!preg_match('/[^A-Za-z0-9]/', $password)) $errors[] = 'un carácter especial';
    if (!preg_match('/[0-9]/', $password)) $errors[] = 'un número';

    return $errors;
  }
}
