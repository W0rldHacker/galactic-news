<article class="article container">
  <nav class="breadcrumbs" aria-label="Хлебные крошки">
    <a href="<?= e(url()) ?>">Главная</a><span>/</span><span><?= e($article->title) ?></span>
  </nav>

  <h1><?= e($article->title) ?></h1>
  <?php if ($article->date !== null): ?>
    <time class="article__date"
      datetime="<?= e(machineDate($article->date)) ?>"><?= e(formatDate($article->date)) ?></time>
  <?php else: ?>
    <span class="article__date">Дата не указана</span>
  <?php endif; ?>

  <div class="article__layout">
    <div class="article__body">
      <div class="article__announce"><?= safeArticleHtml($article->announce) ?></div>
      <div class="article__content"><?= safeArticleHtml($article->content) ?></div>
      <a class="button button--back" href="<?= e(url()) ?>"><span aria-hidden="true">←</span> Назад к новостям</a>
    </div>
    <figure class="article__image">
      <img src="images/<?= e(rawurlencode($article->image)) ?>"
        alt="Иллюстрация к новости &laquo;<?= e($article->title) ?>&raquo;">
    </figure>
  </div>
</article>
