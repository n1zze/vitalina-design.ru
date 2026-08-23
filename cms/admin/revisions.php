<?php
declare(strict_types=1);
require dirname(__DIR__) . '/app/bootstrap.php';
require_auth();
$projectId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
$statement = $pdo->prepare('SELECT id, title FROM projects WHERE id = ?');
$statement->execute([$projectId]);
$project = $statement->fetch();
if (!$project) { http_response_code(404); exit('Project not found.'); }
$revisions = $pdo->prepare('SELECT id, user_id, created_at FROM revisions WHERE project_id = ? ORDER BY id DESC');
$revisions->execute([$projectId]);
?><!doctype html><html lang="ru"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>История | CMS</title><style>body{font:16px system-ui;max-width:800px;margin:40px auto;padding:0 20px}table{width:100%;border-collapse:collapse}td,th{text-align:left;border-bottom:1px solid #ddd;padding:12px 8px}button{padding:7px}</style></head><body><h1>История: <?= e($project['title']) ?></h1><table><thead><tr><th>Версия</th><th>Пользователь</th><th>Дата</th><th></th></tr></thead><tbody><?php foreach ($revisions as $revision): ?><tr><td>#<?= (int) $revision['id'] ?></td><td><?= (int) $revision['user_id'] ?></td><td><?= e($revision['created_at']) ?></td><td><form method="post" action="rollback.php"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="revision_id" value="<?= (int) $revision['id'] ?>"><button type="submit">Откатить</button></form></td></tr><?php endforeach; ?></tbody></table><p><a href="project.php?id=<?= (int) $projectId ?>">Назад</a></p></body></html>
