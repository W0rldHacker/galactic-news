<?php

declare(strict_types=1);

namespace App\Core;

final class View
{
  /** @param array<string, mixed> $data */
  public static function render(string $template, array $data = []): void
  {
    extract($data, EXTR_SKIP);
    ob_start();
    require dirname(__DIR__, 2) . '/views/' . $template . '.php';
    $content = (string) ob_get_clean();
    require dirname(__DIR__, 2) . '/views/layout.php';
  }

  private function __construct()
  {
  }
}
