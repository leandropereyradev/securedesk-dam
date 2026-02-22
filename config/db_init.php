<?php
require_once __DIR__ . '/bootstrap.php';

$databases = [
  'SecureDesk' => [SECUREDESK_DB_PATH],
  'Users' => [USERS_DB_PATH],
  'Tickets' => [TICKETS_DB_PATH]
];

try {
  foreach ($databases as $name => [$path]) {
    $pdo = getConnection($path);
    
    echo "✅ {$name} database initialized successfully.\n";
  }
} catch (RuntimeException $e) {
  echo "❌ Runtime error: " . $e->getMessage() . "\n";
} catch (PDOException $e) {
  echo "❌ Database error: " . $e->getMessage() . "\n";
}
