<?php

declare(strict_types=1);

namespace App\Model;

interface NewsRepositoryInterface
{
  /** @return list<News> */
  public function paginate(int $page, int $perPage): array;

  public function count(): int;

  public function latest(): ?News;

  public function find(int $id): ?News;
}
