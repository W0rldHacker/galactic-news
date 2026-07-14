<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\View;
use App\Model\NewsRepositoryInterface;

final class NewsController
{
  private const PER_PAGE = 4;

  public function __construct(private readonly NewsRepositoryInterface $repository)
  {
  }

  public function index(int $requestedPage): void
  {
    $total = $this->repository->count();
    $pages = max(1, (int) ceil($total / self::PER_PAGE));
    $page = min($requestedPage, $pages);

    View::render('news/index', [
      'pageTitle' => 'Новости – Галактический вестник',
      'news' => $this->repository->paginate($page, self::PER_PAGE),
      'latest' => $this->repository->latest(),
      'page' => $page,
      'pages' => $pages,
    ]);
  }

  public function show(int $id): void
  {
    $article = $id > 0 ? $this->repository->find($id) : null;
    if ($article === null) {
      http_response_code(404);
      View::render('errors/404', ['pageTitle' => 'Новость не найдена']);
      return;
    }

    View::render('news/show', [
      'pageTitle' => $article->title . ' – Галактический вестник',
      'article' => $article,
    ]);
  }
}
