<?php

namespace app\controllers;

use app\helpers\DateHelper;
use app\helpers\PasswordHelper;
use app\models\UserModel;

class UsersController
{
  public static function listAll(array $filters = []): array
  {
    SessionController::requireLogin();

    $filters = array_merge([
      'role' => null
    ], $filters);

    try {
      $users = UserModel::listAll($filters);

      foreach ($users as &$user) {
        $user['created_at'] = DateHelper::utcToMadrid($user['created_at']);
        $user['updated_at'] = DateHelper::utcToMadrid($user['updated_at']);
      }

      return $users;
    } catch (\PDOException $e) {

      error_log('Error al listar usuarios: ' . $e->getMessage());
      return [];
    }
  }

  public static function getByUsername(string $username): array
  {
    try {
      $user = UserModel::getByUsername($username);

      $user['created_at'] = DateHelper::utcToMadrid($user['created_at']);

      return $user;
    } catch (\PDOException $e) {

      error_log('Error al mostrar usuario: ' . $e->getMessage());
      return [];
    }
  }

  public static function changePassword(
    int $userId,
    string $password,
    string $confirm
  ): array {

    if ($password !== $confirm) {
      return [
        'success' => false,
        'error' => 'Las contraseñas no coinciden'
      ];
    }

    $errors = PasswordHelper::validate($password);;

    if (!empty($errors)) {
      return [
        'success' => false,
        'error' => 'La contraseña debe tener: ' . implode(', ', $errors)
      ];
    }

    try {

      $hash = password_hash($password, PASSWORD_DEFAULT);

      $updated = UserModel::updatePassword($userId, $hash);

      if (!$updated) {
        return [
          'success' => false,
          'error' => 'No se pudo actualizar la contraseña'
        ];
      }

      return ['success' => true];
    } catch (\PDOException $e) {

      return [
        'success' => false,
        'error' => 'Error interno' . $e->getMessage()
      ];
    }
  }
}
