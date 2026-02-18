<?php

// Inicializa la tabla de securedesk en la base de datos si no existe
function initSecuredeskDatabase(PDO $pdo): void
{
  // SQL para crear la tabla 'securedesk' con id y fecha de creación
  $sql = "CREATE TABLE IF NOT EXISTS securedesk (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )";

  // Ejecuta el SQL para crear la tabla
  $pdo->exec($sql);
}
