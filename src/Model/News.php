<?php

declare(strict_types=1);

namespace App\Model;

use DateTimeImmutable;
use UnexpectedValueException;

final readonly class News
{
  public function __construct(
    public int $id,
    public ?DateTimeImmutable $date,
    public string $title,
    public string $announce,
    public string $content,
    public string $image,
  ) {
  }

  /** @param array<string, mixed> $row */
  public static function fromArray(array $row): self
  {
    $rawDate = $row['date'] ?? null;
    $date = null;

    if ($rawDate !== null) {
      if (!is_string($rawDate) || $rawDate === '') {
        throw new UnexpectedValueException('Дата новости должна быть null или непустой строкой.');
      }

      $date = DateTimeImmutable::createFromFormat('!Y-m-d H:i:s', $rawDate);
      $dateErrors = DateTimeImmutable::getLastErrors();
      if (
        $date === false
        || ($dateErrors !== false && ($dateErrors['warning_count'] > 0 || $dateErrors['error_count'] > 0))
      ) {
        throw new UnexpectedValueException('Некорректная дата новости: ' . $rawDate);
      }
    }

    return new self(
      (int) $row['id'],
      $date,
      (string) $row['title'],
      (string) ($row['announce'] ?? ''),
      (string) ($row['content'] ?? ''),
      (string) $row['image'],
    );
  }
}
