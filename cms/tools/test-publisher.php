<?php
declare(strict_types=1);

require dirname(__DIR__) . '/app/template.php';

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$catalog = render_template('catalog.php', [
    'title' => 'Проекты - VITALINA DESIGN',
    'description' => 'Каталог',
    'canonical' => 'https://example.test/portfolio/',
    'cards' => '<a class="project-card" href="privateinterior/test.html">Test</a>',
]);

if (!str_contains($catalog, 'privateinterior/test.html') || !str_contains($catalog, '<!doctype html>')) {
    fwrite(STDERR, "catalog template failed\n");
    exit(1);
}

$project = render_template('project.php', [
    'title' => e('Test <script>blocked</script>'),
    'description' => e('Description'),
    'canonical' => e('https://example.test/portfolio/privateinterior/test.html'),
    'eyebrow' => e('Частные · Краснодар'),
    'meta' => e('50 м²') . ' · ' . e('Готово'),
    'gallery' => '<img alt="test">',
]);

if (!str_contains($project, '&lt;script&gt;blocked&lt;/script&gt;') || str_contains($project, '<script>blocked')) {
    fwrite(STDERR, "project template escaping failed\n");
    exit(1);
}

echo "Publisher template smoke test passed.\n";
