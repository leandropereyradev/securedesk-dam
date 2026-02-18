<?php

// Inicializa la tabla de usuarios en la base de datos si no existe
function initUsersDatabase(PDO $pdo): void
{
  // SQL para crear la tabla 'users' con id, usuario, contraseña, rol y fecha de creación
  $sql = "CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            password_hash TEXT NOT NULL,
            role TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )";

  // Ejecuta el SQL para crear la tabla
  $pdo->exec($sql);
}
