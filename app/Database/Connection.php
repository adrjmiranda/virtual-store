<?php

namespace App\Database;

use PDO;

class Connection
{
  private static ?PDO $pdo = null;

  public function get(): PDO
  {
    if (self::$pdo === null) {
      $dsn = sprintf(
        "pgsql:host=%s;port=%s;dbname=%s;user=%s;password=%s",
        env('DB_HOST'),
        env('DB_PORT'),
        env('DB_NAME'),
        env('DB_USER'),
        env('DB_PASS')
      );

      self::$pdo = new PDO($dsn, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
      ]);
    }

    return self::$pdo;
  }
}