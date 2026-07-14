<?php

declare(strict_types=1);

use App\Controller\NewsController;
use App\Core\Database;
use App\Core\HttpInput;
use App\Model\NewsRepository;

$root = dirname(__DIR__);
require $root . '/src/bootstrap.php';

try {
  $controller = new NewsController(new NewsRepository(Database::connection()));

  if (array_key_exists('id', $_GET)) {
    $controller->show(HttpInput::positiveInt($_GET, 'id') ?? 0);
  } else {
    $controller->index(HttpInput::positiveInt($_GET, 'page') ?? 1);
  }
} catch (Throwable $exception) {
  http_response_code(500);
  error_log($exception->__toString());
  require $root . '/views/errors/500.php';
}
