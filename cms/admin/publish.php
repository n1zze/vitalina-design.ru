<?php
declare(strict_types=1);
require dirname(__DIR__) . '/app/bootstrap.php';
require_auth();
require dirname(__DIR__) . '/app/publication.php';
$message = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    try {
        $result = publish_site($pdo, $config, (int) $_SESSION['user_id'], trim((string) ($_POST['comment'] ?? '')));
        $message = 'Публикация #' . $result['id'] . ' завершена. Файлов: ' . $result['files'] . '.';
    } catch (Throwable $exception) {
        $error = 'Публикация не выполнена. Подробности записаны в журнал CMS.';
    }
}
?><!doctype html><html lang="ru"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Публикация | CMS</title><style>body{font:16px system-ui;max-width:700px;margin:50px auto;padding:0 20px}button{padding:12px 18px;cursor:pointer}.ok{color:#176b3a}.error{color:#a00}textarea{width:100%;min-height:80px;box-sizing:border-box}</style></head><body><h1>Публикация сайта</h1><p>Сначала создаётся проверенный пакет и backup, затем файлы заменяются атомарно.</p><?php if ($message): ?><p class="ok"><?= e($message) ?></p><?php endif; ?><?php if ($error): ?><p class="error"><?= e($error) ?></p><?php endif; ?><form method="post"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><label for="comment">Комментарий</label><textarea id="comment" name="comment" maxlength="500"></textarea><button type="submit">Опубликовать</button></form><p><a href="publications.php">История публикаций</a> · <a href="index.php">Назад</a></p></body></html>
