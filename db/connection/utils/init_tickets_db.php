<?php

// Inicializa la tabla de tickets en la base de datos si no existe
function initTicketsDatabase(PDO $pdo): void
{
  // SQL para crear la tabla 'tickets'
  $sql = "
    CREATE TABLE IF NOT EXISTS tickets (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL UNIQUE,
        description TEXT NOT NULL,

        status TEXT NOT NULL
            CHECK (status IN ('new', 'in_process', 'resolved')),

        priority TEXT NOT NULL
            CHECK (priority IN ('low', 'medium', 'high', 'critical')),

        category TEXT NOT NULL,

        created_by INTEGER NOT NULL,
        assigned_to INTEGER,

        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,

        FOREIGN KEY (created_by) REFERENCES users(id),
        FOREIGN KEY (assigned_to) REFERENCES users(id)
    )";

  // Ejecuta el SQL para crear la tabla
  $pdo->exec($sql);
}
