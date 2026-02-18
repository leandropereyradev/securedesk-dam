<?php
require_once 'config.php';

try {
  // Crear conexión PDO con SQLite
  $pdo = new PDO("sqlite:" . DB_PATH);

  // Configurar errores para que lance excepciones
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Consulta simple de prueba
  $result = $pdo->query("SELECT sqlite_version()");

  $version = $result->fetchColumn();

  echo "✅ Conexión correcta. Versión de SQLite: " . $version;
} catch (PDOException $e) {
  echo "❌ Error de conexión: " . $e->getMessage();
}
