<?php
declare(strict_types=1);
require dirname(__DIR__) . '/app/bootstrap.php';
require_auth();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
$project = ['title' => '', 'slug' => '', 'category' => 'Частные интерьеры', 'city' => 'Краснодар', 'area' => '', 'status' => '', 'description' => '', 'seo_title' => '', 'seo_description' => '', 'cover_path' => '', 'is_published' => 0, 'sort_order' => 0];
$images = [];
$error = '';

if ($id) {
    $statement = $pdo->prepare('SELECT * FROM projects WHERE id = ?');
    $statement->execute([$id]);
    $stored = $statement->fetch();
    if (!$stored) {
        http_response_code(404);
        exit('Project not found.');
    }
    $project = array_merge($project, $stored);
    $imageStatement = $pdo->prepare('SELECT * FROM project_images WHERE project_id = ? ORDER BY sort_order, id');
    $imageStatement->execute([$id]);
    $images = $imageStatement->fetchAll();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    $action = $_POST['action'] ?? 'save';

    if ($action === 'delete-image') {
        $imageId = filter_input(INPUT_POST, 'image_id', FILTER_VALIDATE_INT);
        $statement = $pdo->prepare('SELECT path FROM project_images WHERE id = ? AND project_id = ?');
        $statement->execute([$imageId, $id]);
        $image = $statement->fetch();
        if ($image) {
            $pdo->prepare('DELETE FROM project_images WHERE id = ?')->execute([$imageId]);
            $assetRoot = realpath(dirname(__DIR__, 2) . '/portfolio/assets/projects');
            $absolute = $assetRoot ? realpath($assetRoot . '/' . ltrim($image['path'], '/\\')) : false;
            if ($absolute && str_starts_with($absolute, $assetRoot . DIRECTORY_SEPARATOR) && is_file($absolute)) unlink($absolute);
        }
        header('Location: project.php?id=' . $id);
        exit;
    }

    $slug = strtolower(trim((string) ($_POST['slug'] ?? '')));
    if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug)) {
        $error = 'Slug должен содержать только латинские буквы, цифры и дефисы.';
    } else {
        $values = [
            trim((string) ($_POST['title'] ?? '')),
            $slug,
            trim((string) ($_POST['category'] ?? '')),
            trim((string) ($_POST['city'] ?? '')),
            trim((string) ($_POST['area'] ?? '')),
            trim((string) ($_POST['status'] ?? '')),
            trim((string) ($_POST['description'] ?? '')),
            trim((string) ($_POST['seo_title'] ?? '')),
            trim((string) ($_POST['seo_description'] ?? '')),
            isset($_POST['is_published']) ? 1 : 0,
            (int) ($_POST['sort_order'] ?? 0),
        ];
        try {
            $pdo->beginTransaction();
            if ($id) {
                $query = 'UPDATE projects SET title=?, slug=?, category=?, city=?, area=?, status=?, description=?, seo_title=?, seo_description=?, is_published=?, sort_order=? WHERE id=?';
                $pdo->prepare($query)->execute([...$values, $id]);
            } else {
                $query = 'INSERT INTO projects (title, slug, category, city, area, status, description, seo_title, seo_description, is_published, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                $pdo->prepare($query)->execute($values);
                $id = (int) $pdo->lastInsertId();
            }

            $uploadDir = rtrim($config['app']['upload_dir'], '/\\') . '/' . $slug;
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            if (!empty($_FILES['images']['name'][0])) {
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                foreach ($_FILES['images']['tmp_name'] as $index => $tmp) {
                    if ($_FILES['images']['error'][$index] !== UPLOAD_ERR_OK || $_FILES['images']['size'][$index] > 10 * 1024 * 1024) continue;
                    $mime = $finfo->file($tmp);
                    $extension = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'][$mime] ?? null;
                    if (!$extension) continue;
                    $filename = bin2hex(random_bytes(12)) . '.' . $extension;
                    if (move_uploaded_file($tmp, $uploadDir . '/' . $filename)) {
                        $relative = $slug . '/' . $filename;
                        $pdo->prepare('INSERT INTO project_images (project_id, path, alt_text, sort_order) VALUES (?, ?, ?, ?)')->execute([$id, $relative, $project['title'], $index]);
                        if (empty($project['cover_path']) && $index === 0) {
                            $pdo->prepare('UPDATE projects SET cover_path = ? WHERE id = ?')->execute([$relative, $id]);
                        }
                    }
                }
            }

            $payload = json_encode(['title' => $values[0], 'slug' => $values[1], 'category' => $values[2], 'city' => $values[3], 'area' => $values[4], 'status' => $values[5], 'description' => $values[6], 'seo_title' => $values[7], 'seo_description' => $values[8], 'is_published' => $values[9], 'sort_order' => $values[10]], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
            $pdo->prepare('INSERT INTO revisions (project_id, user_id, payload) VALUES (?, ?, ?)')->execute([$id, $_SESSION['user_id'], $payload]);
            $pdo->commit();
            header('Location: project.php?id=' . $id . '&saved=1');
            exit;
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $error = $exception->getCode() === '23000' ? 'Такой slug уже используется.' : 'Не удалось сохранить проект.';
        }
    }
}
?><!doctype html>
<html lang="ru"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?= $id ? 'Редактирование' : 'Новый проект' ?> | CMS</title><style>body{font:16px system-ui;max-width:900px;margin:35px auto;padding:0 20px}label{display:block;margin:15px 0 6px}input,textarea{width:100%;box-sizing:border-box;padding:10px}textarea{min-height:110px}button{padding:10px 16px;margin-top:18px;cursor:pointer}.grid{display:grid;grid-template-columns:1fr 1fr;gap:0 20px}.error{color:#a00}.images{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-top:20px}.images img{width:100%;aspect-ratio:1;object-fit:cover}.images form{font-size:12px}.toolbar{display:flex;justify-content:space-between;align-items:center}</style></head>
<body><div class="toolbar"><h1><?= $id ? 'Редактирование проекта' : 'Новый проект' ?></h1><a href="index.php">← К списку</a></div><?php if ($error): ?><p class="error"><?= e($error) ?></p><?php endif; ?><form method="post" enctype="multipart/form-data"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><div class="grid"><div><label for="title">Название</label><input id="title" name="title" required value="<?= e($project['title']) ?>"></div><div><label for="slug">Slug</label><input id="slug" name="slug" required pattern="[a-z0-9-]+" value="<?= e($project['slug']) ?>"></div><div><label for="category">Категория</label><input id="category" name="category" value="<?= e($project['category']) ?>"></div><div><label for="city">Город</label><input id="city" name="city" value="<?= e($project['city']) ?>"></div><div><label for="area">Площадь</label><input id="area" name="area" value="<?= e($project['area']) ?>"></div><div><label for="status">Статус</label><input id="status" name="status" value="<?= e($project['status']) ?>"></div></div><label for="description">Описание</label><textarea id="description" name="description"><?= e($project['description']) ?></textarea><label for="seo_title">SEO title</label><input id="seo_title" name="seo_title" value="<?= e($project['seo_title']) ?>"><label for="seo_description">SEO description</label><textarea id="seo_description" name="seo_description"><?= e($project['seo_description']) ?></textarea><label for="images">Добавить изображения</label><input id="images" name="images[]" type="file" accept="image/jpeg,image/png,image/webp" multiple><label><input name="is_published" type="checkbox" <?= $project['is_published'] ? 'checked' : '' ?>> Опубликован</label><label for="sort_order">Порядок</label><input id="sort_order" name="sort_order" type="number" value="<?= (int) $project['sort_order'] ?>"><button type="submit">Сохранить</button></form><?php if ($images): ?><h2>Изображения</h2><div class="images"><?php foreach ($images as $image): ?><div><img src="<?= e($config['app']['upload_url'] . '/' . $image['path']) ?>" alt=""><form method="post"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="delete-image"><input type="hidden" name="image_id" value="<?= (int) $image['id'] ?>"><button type="submit">Удалить</button></form></div><?php endforeach; ?></div><?php endif; ?></body></html>
