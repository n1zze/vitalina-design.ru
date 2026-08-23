<?php
declare(strict_types=1);
require dirname(__DIR__) . '/app/bootstrap.php';
require_auth();

$message = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    $source = dirname(__DIR__, 2) . '/project.md';
    $assetRoot = dirname(__DIR__, 2) . '/portfolio/assets/projects';
    if (!is_file($source) || !is_dir($assetRoot)) {
        $error = 'Не найден project.md или каталог проектов.';
    } else {
        $blocks = preg_split('/\R\s*\R/', trim((string) file_get_contents($source))) ?: [];
        $pdo->beginTransaction();
        try {
            foreach ($blocks as $block) {
                $lines = array_values(array_filter(array_map('trim', preg_split('/\R/', $block) ?: [])));
                if (!$lines) continue;
                $data = ['slug' => $lines[0], 'title' => '', 'category' => 'Частные интерьеры', 'city' => 'Краснодар', 'area' => '', 'status' => ''];
                foreach (array_slice($lines, 1) as $line) {
                    [$key, $value] = array_pad(explode(':', $line, 2), 2, '');
                    $map = ['Название' => 'title', 'Категория' => 'category', 'Город' => 'city', 'Площадь' => 'area', 'Статус' => 'status'];
                    if (isset($map[trim($key)])) $data[$map[trim($key)]] = trim($value);
                }
                if (!$data['title']) continue;
                $select = $pdo->prepare('SELECT id FROM projects WHERE slug = ?');
                $select->execute([$data['slug']]);
                $existingId = $select->fetchColumn();
                if ($existingId) {
                    $projectId = (int) $existingId;
                    $pdo->prepare('UPDATE projects SET title=?, category=?, city=?, area=?, status=? WHERE id=?')->execute([$data['title'], $data['category'], $data['city'], $data['area'], $data['status'], $projectId]);
                } else {
                    $pdo->prepare('INSERT INTO projects (slug,title,category,city,area,status,is_published) VALUES (?,?,?,?,?,?,1)')->execute([$data['slug'], $data['title'], $data['category'], $data['city'], $data['area'], $data['status']]);
                    $projectId = (int) $pdo->lastInsertId();
                }
                $folder = $assetRoot . '/' . $data['slug'];
                if (!is_dir($folder)) continue;
                $files = array_values(array_filter(scandir($folder) ?: [], static fn ($file) => preg_match('/\.(jpe?g|png|webp)$/i', $file)));
                sort($files, SORT_NATURAL);
                $pdo->prepare('DELETE FROM project_images WHERE project_id = ?')->execute([$projectId]);
                foreach ($files as $order => $file) {
                    $relative = $data['slug'] . '/' . $file;
                    $pdo->prepare('INSERT INTO project_images (project_id,path,alt_text,sort_order) VALUES (?,?,?,?)')->execute([$projectId, $relative, $data['title'] . ', интерьер', $order]);
                    if ($file === 'cover.jpg') $pdo->prepare('UPDATE projects SET cover_path = ? WHERE id = ?')->execute([$relative, $projectId]);
                }
            }
            $pdo->commit();
            $message = 'Проекты и изображения импортированы.';
        } catch (Throwable $exception) {
            $pdo->rollBack();
            $error = 'Импорт не выполнен.';
        }
    }
}
?><!doctype html><html lang="ru"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Импорт | CMS</title><style>body{font:16px system-ui;max-width:700px;margin:50px auto;padding:0 20px}button{padding:12px 18px}.ok{color:#176b3a}.error{color:#a00}</style></head><body><h1>Импорт проектов</h1><p>Импортирует записи из <code>project.md</code> и изображения из <code>portfolio/assets/projects</code>.</p><?php if ($message): ?><p class="ok"><?= e($message) ?></p><?php endif; ?><?php if ($error): ?><p class="error"><?= e($error) ?></p><?php endif; ?><form method="post"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><button type="submit">Импортировать</button></form><p><a href="index.php">Назад</a></p></body></html>
