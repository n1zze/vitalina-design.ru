<?php
declare(strict_types=1);
require dirname(__DIR__) . '/app/bootstrap.php';
require_auth();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
$page = ['slug' => '', 'title' => '', 'description' => '', 'body_html' => '', 'seo_title' => '', 'seo_description' => '', 'is_published' => 1];
$error = '';
if ($id) {
    $statement = $pdo->prepare('SELECT * FROM pages WHERE id = ?');
    $statement->execute([$id]);
    $stored = $statement->fetch();
    if (!$stored) { http_response_code(404); exit('Page not found.'); }
    $page = array_merge($page, $stored);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    $slug = strtolower(trim((string) ($_POST['slug'] ?? '')));
    if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug)) {
        $error = 'Slug должен содержать только латинские буквы, цифры и дефисы.';
    } else {
        $values = [trim((string) ($_POST['title'] ?? '')), $slug, trim((string) ($_POST['description'] ?? '')), (string) ($_POST['body_html'] ?? ''), trim((string) ($_POST['seo_title'] ?? '')), trim((string) ($_POST['seo_description'] ?? '')), isset($_POST['is_published']) ? 1 : 0];
        try {
            if ($id) $pdo->prepare('UPDATE pages SET title=?,slug=?,description=?,body_html=?,seo_title=?,seo_description=?,is_published=? WHERE id=?')->execute([...$values, $id]);
            else { $pdo->prepare('INSERT INTO pages (title,slug,description,body_html,seo_title,seo_description,is_published) VALUES (?,?,?,?,?,?,?)')->execute($values); $id = (int) $pdo->lastInsertId(); }
            $payload = json_encode(['title' => $values[0], 'slug' => $values[1], 'body_html' => $values[3]], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
            $pdo->prepare('INSERT INTO page_revisions (page_id,user_id,payload) VALUES (?,?,?)')->execute([$id, $_SESSION['user_id'], $payload]);
            header('Location: page.php?id=' . $id . '&saved=1'); exit;
        } catch (Throwable $exception) { $error = $exception->getCode() === '23000' ? 'Такой slug уже используется.' : 'Не удалось сохранить страницу.'; }
    }
}
?><!doctype html><html lang="ru"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Страница | CMS</title><style>body{font:16px system-ui;max-width:1000px;margin:40px auto;padding:0 20px}label{display:block;margin:15px 0 6px}input,textarea{width:100%;box-sizing:border-box;padding:10px}textarea{min-height:120px}button{padding:10px 18px;margin-top:18px}.error{color:#a00}</style></head><body><h1><?= $id ? 'Редактирование страницы' : 'Новая страница' ?></h1><?php if ($error): ?><p class="error"><?= e($error) ?></p><?php endif; ?><form method="post"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><label for="title">Название</label><input id="title" name="title" required value="<?= e($page['title']) ?>"><label for="slug">Slug</label><input id="slug" name="slug" required value="<?= e($page['slug']) ?>"><label for="description">Краткое описание</label><textarea id="description" name="description"><?= e($page['description']) ?></textarea><label for="body_html">Основной HTML-контент</label><textarea id="body_html" name="body_html"><?= e($page['body_html']) ?></textarea><label for="seo_title">SEO title</label><input id="seo_title" name="seo_title" value="<?= e($page['seo_title']) ?>"><label for="seo_description">SEO description</label><textarea id="seo_description" name="seo_description"><?= e($page['seo_description']) ?></textarea><label><input name="is_published" type="checkbox" <?= $page['is_published'] ? 'checked' : '' ?>> Опубликована</label><button type="submit">Сохранить</button></form><p><a href="pages.php">К списку страниц</a></p></body></html>
