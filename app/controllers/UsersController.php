<?php

namespace app\controllers;

use app\models\UserModel;

class UsersController
{
  public static function listAll(): array
  {
    SessionController::requireLogin();

    return UserModel::getAll();
  }

  public static function getByUsername(string $username): array
  {
    return UserModel::getByUsername($username);
  }
}
