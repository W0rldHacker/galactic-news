<?php

declare(strict_types=1);

spl_autoload_register(static function (string $class): void {
  $prefix = 'App\\';
  if (!str_starts_with($class, $prefix)) {
    return;
  }

  $relativeClass = substr($class, strlen($prefix));
  $path = __DIR__ . '/' . str_replace('\\', '/', $relativeClass) . '.php';
  if (is_file($path)) {
    require $path;
  }
});

function env(string $name, string $default = ''): string
{
  $value = getenv($name);
  return $value === false ? $default : $value;
}

function e(?string $value): string
{
  return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function plainText(?string $html): string
{
  return trim(html_entity_decode(strip_tags($html ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
}

function safeArticleHtml(?string $html): string
{
  if ($html === null || trim($html) === '') {
    return '';
  }

  $text = preg_replace(
    ['~<br\s*/?>~i', '~</p\s*>\s*<p(?:\s[^>]*)?>~i', '~</?p(?:\s[^>]*)?>~i'],
    ["\n", "\n\n", ''],
    $html
  );
  if ($text === null) {
    return '';
  }

  $text = html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8');
  $paragraphs = preg_split('/(?:\R\s*){2,}/u', trim($text)) ?: [];
  $safeHtml = '';

  foreach ($paragraphs as $paragraph) {
    $paragraph = trim($paragraph);
    if ($paragraph === '') {
      continue;
    }

    $escaped = e($paragraph);
    $escaped = preg_replace('/\R/u', '<br>', $escaped) ?? $escaped;
    $safeHtml .= '<p>' . $escaped . '</p>';
  }

  return $safeHtml;
}

function formatDate(DateTimeInterface $date): string
{
  return $date->format('d.m.Y');
}

function machineDate(DateTimeInterface $date): string
{
  return $date->format('Y-m-d\TH:i:s');
}

function url(array $query = []): string
{
  return $query === [] ? './' : './?' . http_build_query($query);
}
