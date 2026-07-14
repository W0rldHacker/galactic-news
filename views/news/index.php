<?php if ($latest !== null): ?>
  <a class="hero" href="<?= e(url(['id' => $latest->id])) ?>"
    style="--hero-image: url('/images/<?= e(rawurlencode($latest->image)) ?>')">
    <div class="hero__shade"></div>
    <div class="container hero__content">
      <h1><?= e($latest->title) ?></h1>
      <p><?= e(plainText($latest->announce)) ?></p>
    </div>
  </a>
<?php endif; ?>

<section class="news-list container">
  <h2>Новости</h2>

  <div class="news-grid">
    <?php foreach ($news as $item): ?>
      <article class="news-card">
        <a class="news-card__link" href="<?= e(url(['id' => $item->id])) ?>">
          <?php if ($item->date !== null): ?>
            <time class="news-card__date"
              datetime="<?= e(machineDate($item->date)) ?>"><?= e(formatDate($item->date)) ?></time>
          <?php else: ?>
            <span class="news-card__date">Дата не указана</span>
          <?php endif; ?>
          <h3><?= e($item->title) ?></h3>
          <p><?= e(plainText($item->announce)) ?></p>
          <span class="button">
            Подробнее <span aria-hidden="true">→</span>
          </span>
        </a>
      </article>
    <?php endforeach; ?>
    <?php if ($news === []): ?>
      <p class="news-list__empty">Новостей пока нет.</p>
    <?php endif; ?>
  </div>

  <?php if ($pages > 1): ?>
    <nav class="pagination" aria-label="Страницы новостей">
      <?php for ($number = 1; $number <= $pages; $number++): ?>
        <a class="pagination__page <?= $number === $page ? 'is-current' : '' ?>" href="<?= e(url(['page' => $number])) ?>"
          <?= $number === $page ? 'aria-current="page"' : '' ?>><?= $number ?></a>
      <?php endfor; ?>
      <?php if ($page < $pages): ?>
        <a class="pagination__next" href="<?= e(url(['page' => $page + 1])) ?>" aria-label="Следующая страница">→</a>
      <?php endif; ?>
    </nav>
  <?php endif; ?>
</section>
