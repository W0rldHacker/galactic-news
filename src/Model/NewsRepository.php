<?php

declare(strict_types=1);

namespace App\Model;

use PDO;

final readonly class NewsRepository implements NewsRepositoryInterface
{
  public function __construct(private PDO $database)
  {
  }

  /** @return list<News> */
  public function paginate(int $page, int $perPage): array
  {
    $offset = ($page - 1) * $perPage;
    $statement = $this->database->prepare(
      'SELECT id, date, title, announce, content, image
             FROM news
             ORDER BY date DESC, id DESC
             LIMIT :limit OFFSET :offset'
    );
    $statement->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
    $statement->execute();

    return array_map(News::fromArray(...), $statement->fetchAll());
  }

  public function count(): int
  {
    return (int) $this->database->query('SELECT COUNT(*) FROM news')->fetchColumn();
  }

  public function latest(): ?News
  {
    $row = $this->database->query(
      'SELECT id, date, title, announce, content, image
             FROM news ORDER BY date DESC, id DESC LIMIT 1'
    )->fetch();

    return $row === false ? null : News::fromArray($row);
  }

  public function find(int $id): ?News
  {
    $statement = $this->database->prepare(
      'SELECT id, date, title, announce, content, image FROM news WHERE id = :id LIMIT 1'
    );
    $statement->execute(['id' => $id]);
    $row = $statement->fetch();

    return $row === false ? null : News::fromArray($row);
  }
}
