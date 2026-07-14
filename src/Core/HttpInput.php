<?php

declare(strict_types=1);

namespace App\Core;

final class HttpInput
{
  /** @param array<string, mixed> $query */
  public static function positiveInt(array $query, string $name): ?int
  {
    $value = $query[$name] ?? null;
    if (!is_string($value) || $value === '' || !ctype_digit($value)) {
      return null;
    }

    $integer = filter_var(
      $value,
      FILTER_VALIDATE_INT,
      ['options' => ['min_range' => 1]]
    );

    return is_int($integer) ? $integer : null;
  }

  private function __construct()
  {
  }
}
