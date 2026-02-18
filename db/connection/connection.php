<?php

// Función para crear y devolver una conexión PDO a una base de datos SQLite
function getConnection(string $dbPath): PDO
{
  // Obtiene la carpeta donde se encuentra la base de datos
  $dir = dirname($dbPath);

  // Comprueba si la carpeta existe y si se puede escribir en ella sino lanza error
  if (!is_dir($dir) || !is_writable($dir)) {
    throw new RuntimeException('Directorio de BD no existe o no es escribible');
  }

  // Si el archivo de base de datos existe, comprueba que sea escribible sino lanza error
  if (file_exists($dbPath) && !is_writable($dbPath)) {
    throw new RuntimeException('Archivo de BD no es escribible');
  }

  // Crea la conexión PDO a SQLite usando la ruta de la base de datos
  $pdo = new PDO('sqlite:' . $dbPath);

  // Configura PDO para que lance excepciones en caso de error
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Activa las claves foráneas en SQLite
  $pdo->exec('PRAGMA foreign_keys = ON');

  // Devuelve el objeto PDO listo para usar
  return $pdo;
}
