<?php

declare(strict_types=1);

use App\Controller\NewsController;
use App\Core\HttpInput;
use App\Model\News;
use App\Model\NewsRepositoryInterface;

require dirname(__DIR__) . '/src/bootstrap.php';

$assertions = 0;

/** @param mixed $actual */
function expectSame(mixed $expected, mixed $actual, string $message): void
{
  global $assertions;
  $assertions++;
  if ($expected !== $actual) {
    throw new RuntimeException(
      $message . sprintf(' (ожидалось %s, получено %s)', var_export($expected, true), var_export($actual, true))
    );
  }
}

function expectTrue(bool $condition, string $message): void
{
  expectSame(true, $condition, $message);
}

$article = News::fromArray([
  'id' => '3',
  'date' => '2412-03-25 00:00:00',
  'title' => 'Заголовок <script>',
  'announce' => '<p onclick="alert(1)">Анонс</p><script>alert(1)</script>',
  'content' => '<p>Первый абзац</p><p>Второй &amp; безопасный</p>',
  'image' => 'image.jpg',
]);

expectSame(3, $article->id, 'Идентификатор новости преобразуется в целое число');
expectTrue($article->date instanceof DateTimeImmutable, 'Непустая дата новости преобразуется в объект даты');
expectSame('25.03.2412', formatDate($article->date), 'Дата новости разбирается в строгом формате');
expectSame('2412-03-25T00:00:00', machineDate($article->date), 'Машинная дата использует HTML-формат');
expectSame(null, HttpInput::positiveInt(['id' => ['1']], 'id'), 'Значение параметра запроса в виде массива отклоняется');
expectSame(null, HttpInput::positiveInt(['page' => '0'], 'page'), 'Нулевое значение параметра запроса отклоняется');
expectSame(12, HttpInput::positiveInt(['page' => '12'], 'page'), 'Положительное целое число принимается');

$safeHtml = safeArticleHtml($article->announce);
expectTrue(!str_contains($safeHtml, '<script'), 'Элемент script удаляется');
expectTrue(!str_contains($safeHtml, 'onclick'), 'HTML-атрибуты удаляются');

$undatedArticle = News::fromArray([
  'id' => '4',
  'date' => null,
  'title' => 'Новость без даты',
  'announce' => null,
  'content' => null,
  'image' => 'image.jpg',
]);
expectSame(null, $undatedArticle->date, 'Значение SQL NULL для даты остаётся null в модели');

$repository = new class ($article) implements NewsRepositoryInterface {
  public function __construct(private readonly News $article)
  {
  }

  public function paginate(int $page, int $perPage): array
  {
    return [$this->article];
  }

  public function count(): int
  {
    return 5;
  }

  public function latest(): ?News
  {
    return $this->article;
  }

  public function find(int $id): ?News
  {
    return $id === $this->article->id ? $this->article : null;
  }
};

$controller = new NewsController($repository);
ob_start();
$controller->index(999);
$listHtml = (string) ob_get_clean();
expectTrue(str_contains($listHtml, 'aria-current="page">2</a>'), 'Номер страницы за пределами диапазона ограничивается допустимым значением');
expectTrue(str_contains($listHtml, '2412-03-25T00:00:00'), 'Список содержит корректную машинную дату');

http_response_code(200);
ob_start();
$controller->show(999);
$notFoundHtml = (string) ob_get_clean();
expectSame(404, http_response_code(), 'При отсутствии новости возвращается HTTP 404');
expectTrue(str_contains($notFoundHtml, 'Новость не найдена'), 'Отображается страница ошибки 404');

http_response_code(200);
ob_start();
$controller->show(3);
$articleHtml = (string) ob_get_clean();
expectTrue(str_contains($articleHtml, 'Заголовок &lt;script&gt;'), 'HTML-символы в заголовке экранируются');
expectTrue(!str_contains($articleHtml, '<script>'), 'Содержимое новости не позволяет внедрять элементы script');

echo "OK: {$assertions} проверок\n";
