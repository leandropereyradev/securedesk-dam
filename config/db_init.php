<?php
require_once __DIR__ . '/bootstrap.php';

$databases = [
  'SecureDesk' => [SECUREDESK_DB_PATH, fn($pdo) => initSecuredeskDatabase($pdo)],
  'Users'      => [USERS_DB_PATH, fn($pdo) => initUsersDatabase($pdo)],
  'Tickets'    => [TICKETS_DB_PATH, fn($pdo) => initTicketsDatabase($pdo)]
];

try {
  foreach ($databases as $name => [$path, $initFunction]) {

    // Detectar si la base es nueva ANTES de conectarse
    $isNewDatabase = file_exists($path);

    $pdo = getConnection($path);

    if ($name === 'Users') {

      // Crear tabla solo si no existía la DB
      if (!$isNewDatabase) {
        $initFunction($pdo);
        createInitialUsers($pdo);

        echo "🆕 {$name} database CREATED with initial users.\n";
      } else {
        echo "♻️ {$name} database already exists. No changes made.\n";
      }
    } else {

      // Otras DBs: solo creación del archivo si no existía
      if (!$isNewDatabase) {
        $initFunction($pdo);

        echo "🆕 {$name} database CREATED.\n";
      } else {
        echo "♻️ {$name} database already exists.\n";
      }
    }
  }
} catch (RuntimeException $e) {
  echo "❌ Runtime error: " . $e->getMessage() . "\n";
} catch (PDOException $e) {
  echo "❌ Database error: " . $e->getMessage() . "\n";
}
