<?php
declare(strict_types=1);
require dirname(__DIR__) . '/app/bootstrap.php';
require_auth();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Method not allowed.'); }
require_csrf();
$revisionId = filter_input(INPUT_POST, 'revision_id', FILTER_VALIDATE_INT);
$statement = $pdo->prepare('SELECT project_id, payload FROM revisions WHERE id = ?');
$statement->execute([$revisionId]);
$revision = $statement->fetch();
if (!$revision) { http_response_code(404); exit('Revision not found.'); }
$payload = json_decode($revision['payload'], true, 512, JSON_THROW_ON_ERROR);
$fields = ['title','slug','category','city','area','status','description','seo_title','seo_description','is_published','sort_order'];
$set = implode(',', array_map(static fn ($field) => $field . '=?', $fields));
$values = array_map(static fn ($field) => $payload[$field] ?? '', $fields);
$values[] = (int) $revision['project_id'];
$pdo->prepare("UPDATE projects SET {$set} WHERE id=?")->execute($values);
header('Location: project.php?id=' . (int) $revision['project_id'] . '&restored=1');
exit;
