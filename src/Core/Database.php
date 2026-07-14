<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

final class Database
{
  private static ?PDO $connection = null;

  public static function connection(): PDO
  {
    if (self::$connection !== null) {
      return self::$connection;
    }

    $host = \env('DB_HOST', '127.0.0.1');
    $port = \env('DB_PORT', '3306');
    $database = \env('DB_NAME', 'galactic_news');
    $charset = 'utf8mb4';

    self::$connection = new PDO(
      "mysql:host={$host};port={$port};dbname={$database};charset={$charset}",
      \env('DB_USER', 'news'),
      \env('DB_PASSWORD', 'news'),
      [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
      ]
    );

    return self::$connection;
  }

  private function __construct()
  {
  }
}
