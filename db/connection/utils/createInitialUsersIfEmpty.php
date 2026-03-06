<?php

function createInitialUsers(PDO $pdo): void
{
  $count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

  // Ya existen usuarios → no volver a crear
  if ($count > 0) {
    return;
  }

  $stmt = $pdo->prepare("
    INSERT INTO users (username, password_hash, role)
    VALUES (:username, :password_hash, :role)
  ");

  $users = [
    ['AdminLeandro',   'admin',   'admin'],
    ['TechLeandro', 'technician', 'technician'],
    ['ReaderLeandro',  'reader',  'reader'],
  ];

  foreach ($users as [$username, $password, $role]) {
    $stmt->execute([
      ':username'      => $username,
      ':password_hash' => password_hash($password, PASSWORD_DEFAULT),
      ':role'          => $role,
    ]);
  }
}
