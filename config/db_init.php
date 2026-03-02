<?php
require_once __DIR__ . '/bootstrap.php';

try {
  // Conectamos a la base unificada
  $pdo = getConnection(SECUREDESK_DB_PATH);

  // Crear tablas
  initUsersDatabase($pdo);
  initTicketsDatabase($pdo);

  // Insertar usuarios iniciales si no existen
  createInitialUsers($pdo);

  echo "✅ Base de datos unificada creada con tablas users, tickets y securedesk.\n";
} catch (RuntimeException $e) {
  echo "❌ Runtime error: " . $e->getMessage() . "\n";
} catch (PDOException $e) {
  echo "❌ Database error: " . $e->getMessage() . "\n";
}
