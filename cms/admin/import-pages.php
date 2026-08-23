<?php
declare(strict_types=1);
require dirname(__DIR__) . '/app/bootstrap.php';
require_auth();

$definitions = [
    'about' => ['file' => 'portfolio/about.htm', 'title' => 'Об авторе', 'marker' => 'about'],
    'service' => ['file' => 'portfolio/service.htm', 'title' => 'Услуги', 'marker' => 'service'],
    'contact' => ['file' => 'portfolio/contact.html', 'title' => 'Контакты', 'marker' => 'contact'],
];
$message = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    try {
        foreach ($definitions as $slug => $definition) {
            $file = dirname(__DIR__, 2) . '/' . $definition['file'];
            $html = (string) file_get_contents($file);
            $start = '<!-- CMS:' . $definition['marker'] . ':start -->';
            $end = '<!-- CMS:' . $definition['marker'] . ':end -->';
            $startPosition = strpos($html, $start);
            $endPosition = strpos($html, $end);
            if ($startPosition === false || $endPosition === false || $endPosition <= $startPosition) throw new RuntimeException('Не найдены CMS-маркеры: ' . $slug);
            $body = trim(substr($html, $startPosition + strlen($start), $endPosition - $startPosition - strlen($start)));
            $select = $pdo->prepare('SELECT id FROM pages WHERE slug = ?');
            $select->execute([$slug]);
            $id = $select->fetchColumn();
            if ($id) $pdo->prepare('UPDATE pages SET title=?, body_html=? WHERE id=?')->execute([$definition['title'], $body, $id]);
            else $pdo->prepare('INSERT INTO pages (slug,title,body_html,is_published) VALUES (?,?,?,1)')->execute([$slug, $definition['title'], $body]);
        }
        $message = 'Контент about, service и contact импортирован.';
    } catch (Throwable $exception) { $error = $exception->getMessage(); }
}
?><!doctype html><html lang="ru"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Импорт страниц | CMS</title><style>body{font:16px system-ui;max-width:700px;margin:50px auto;padding:0 20px}button{padding:12px 18px}.ok{color:#176b3a}.error{color:#a00}</style></head><body><h1>Импорт страниц</h1><p>Сохраняет текущий HTML-контент about, service и contact в CMS без изменения дизайна.</p><?php if ($message): ?><p class="ok"><?= e($message) ?></p><?php endif; ?><?php if ($error): ?><p class="error"><?= e($error) ?></p><?php endif; ?><form method="post"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><button type="submit">Импортировать страницы</button></form><p><a href="pages.php">К списку страниц</a></p></body></html>
