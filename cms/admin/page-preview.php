<?php
declare(strict_types=1);
require dirname(__DIR__) . '/app/bootstrap.php';
require_auth();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
$statement = $pdo->prepare('SELECT title, body_html FROM pages WHERE id = ?');
$statement->execute([$id]);
$page = $statement->fetch();
if (!$page) { http_response_code(404); exit('Page not found.'); }
?><!doctype html><html lang="ru"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Preview: <?= e($page['title']) ?></title><link href="../../skins/saparova/css/main.css?v=14" rel="stylesheet"><style>.preview-bar{position:fixed;top:0;left:0;right:0;z-index:1000;padding:10px 20px;background:#111;color:#fff}.preview-bar a{color:#fff;margin-left:20px}main{padding-top:55px}</style></head><body><div class="preview-bar">Предпросмотр: <?= e($page['title']) ?><a href="page.php?id=<?= (int) $id ?>">Вернуться в CMS</a></div><main><?= $page['body_html'] ?></main></body></html>
