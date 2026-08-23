<?php
declare(strict_types=1);
require dirname(__DIR__) . '/app/bootstrap.php';
require dirname(__DIR__) . '/app/publisher.php';
require_auth();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
$statement = $pdo->prepare('SELECT * FROM projects WHERE id = ?');
$statement->execute([$id]);
$project = $statement->fetch();
if (!$project) { http_response_code(404); exit('Project not found.'); }
$images = project_images($pdo, $id);
?><!doctype html><html lang="ru"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Preview: <?= e($project['title']) ?></title><style>body{font:16px system-ui;max-width:1200px;margin:40px auto;padding:0 20px}.meta{color:#666;margin-bottom:30px}.gallery{columns:3 280px;column-gap:14px}.gallery img{display:block;width:100%;height:auto;margin:0 0 14px;break-inside:avoid}@media(max-width:600px){.gallery{columns:1}}</style></head><body><p><a href="project.php?id=<?= (int) $id ?>">← Вернуться в CMS</a></p><h1><?= e($project['title']) ?></h1><div class="meta"><?= e($project['city'] . ' · ' . $project['area'] . ' · ' . $project['status']) ?></div><p><?= nl2br(e($project['description'])) ?></p><div class="gallery"><?php foreach ($images as $image): ?><img src="../../portfolio/assets/projects/<?= e($image['path']) ?>" alt="<?= e($image['alt_text']) ?>" loading="lazy"><?php endforeach; ?></div></body></html>
