<?php

namespace app\controllers;

use app\helpers\DateHelper;
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
}
