<?php

// Inicializa la tabla de tickets en la base de datos si no existe
function initTicketsDatabase(PDO $pdo): void
{
  // SQL para crear la tabla 'tickets' con id, título, descripción, estado,
  // prioridad y fecha de creación
  $sql = "CREATE TABLE IF NOT EXISTS tickets (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL UNIQUE,
            description TEXT NOT NULL,
            status TEXT NOT NULL,
            priority TEXT NOT NULL,
            created_by INTEGER NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (created_by) REFERENCES users(id)
        )";

  // Ejecuta el SQL para crear la tabla
  $pdo->exec($sql);
}
