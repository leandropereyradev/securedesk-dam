<?php

namespace app\controllers;

class AuthController
{
  public static function login()
  {
    SessionController::start();

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
      $pdo = getConnection(USERS_DB_PATH);

      $stmt = $pdo->prepare("
                SELECT id, username, password_hash, role
                FROM users
                WHERE username = :username
            ");
      $stmt->execute([':username' => $username]);
      $user = $stmt->fetch(\PDO::FETCH_ASSOC);

      if (!$user || !password_verify($password, $user['password_hash'])) {
        $_SESSION['login_error'] = 'Usuario o contraseña incorrectos.';
        header('Location: login');
        exit;
      }

      header('Location: dashboard');
      exit;
    } catch (\PDOException $e) {
      $_SESSION['login_error'] = 'Error de base de datos.';
      header('Location: login');
      exit;
    }
  }
}
