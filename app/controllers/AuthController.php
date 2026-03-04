<?php

namespace app\controllers;

class AuthController
{
  public static function login()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header('Location: login');
      exit;
    }

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
      $_SESSION['login_error'] = 'Debes completar todos los campos.';
      header('Location: login');
      exit;
    }

    try {
      $user = UsersController::getByUsername($username);

      if (!$user || !password_verify($password, $user['password_hash'])) {
        $_SESSION['login_error'] = 'Usuario o contraseña incorrectos.';
        header('Location: login');
        exit;
      }

      // ✔ Login exitoso - guardamos datos en sesión
      $_SESSION['user_id']  = $user['id'];
      $_SESSION['username'] = $user['username'];
      $_SESSION['role']     = $user['role'];

      header('Location: dashboard');
      exit;
    } catch (\PDOException $e) {
      $_SESSION['login_error'] = 'Error de base de datos.';
      header('Location: login');
      exit;
    }
  }
}
