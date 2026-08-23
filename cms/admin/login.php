<?php
declare(strict_types=1);
require dirname(__DIR__) . '/app/bootstrap.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    $statement = $pdo->prepare('SELECT id, password_hash FROM users WHERE email = ? LIMIT 1');
    $statement->execute([trim((string) ($_POST['email'] ?? ''))]);
    $user = $statement->fetch();
    if ($user && password_verify((string) ($_POST['password'] ?? ''), $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        header('Location: index.php');
        exit;
    }
    $error = 'Неверный email или пароль.';
}
?><!doctype html>
<html lang="ru"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Вход в CMS</title><style>body{font:16px system-ui;max-width:420px;margin:10vh auto;padding:20px}label{display:block;margin:14px 0 6px}input,button{width:100%;padding:11px;box-sizing:border-box}button{margin-top:18px;cursor:pointer}.error{color:#a00}</style></head>
<body><h1>Вход в CMS</h1><?php if ($error): ?><p class="error"><?= e($error) ?></p><?php endif; ?><form method="post"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><label for="email">Email</label><input id="email" name="email" type="email" required autocomplete="username"><label for="password">Пароль</label><input id="password" name="password" type="password" required autocomplete="current-password"><button type="submit">Войти</button></form></body></html>
