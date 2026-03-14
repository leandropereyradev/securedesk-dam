<?php

namespace app\core;

use PDO;
use RuntimeException;

class Database
{
  private static ?PDO $connection = null;

  public static function getConnection(): PDO
  {
    if (self::$connection !== null) {
      return self::$connection;
    }

    $dbPath = ROOT . 'db/securedesk.sqlite';

    $dir = dirname($dbPath);

    if (!is_dir($dir) || !is_writable($dir)) {
      throw new RuntimeException('Directorio de BD no existe o no es escribible');
    }

    if (file_exists($dbPath) && !is_writable($dbPath)) {
      throw new RuntimeException('Archivo de BD no es escribible');
    }

    $pdo = new PDO('sqlite:' . $dbPath);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $pdo->exec('PRAGMA foreign_keys = ON');
    $pdo->exec("PRAGMA encoding = 'UTF-8'");

    self::$connection = $pdo;

    return self::$connection;
  }
}
