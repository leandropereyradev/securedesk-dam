<?php

// Carga la configuración y las funciones necesarias del proyecto
require_once __DIR__ . '/bootstrap.php';

try {
  // Conexión e inicialización de las bases de datos
  $securedeskPdo = getConnection(SECUREDESK_DB_PATH);
  initSecuredeskDatabase($securedeskPdo);

  $usersPdo = getConnection(USERS_DB_PATH);
  initUsersDatabase($usersPdo);

  $ticketsPdo = getConnection(TICKETS_DB_PATH);
  initTicketsDatabase($ticketsPdo);

  echo "✅ Bases de datos inicializadas correctamente.";

} catch (RuntimeException $e) {
  echo "❌ Error de entorno: " . $e->getMessage();
  
} catch (PDOException $e) {
  echo "❌ Error de base de datos: " . $e->getMessage();
}
