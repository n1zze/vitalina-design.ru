<?php
declare(strict_types=1);

return [
    'db' => [
        'dsn' => 'mysql:host=localhost;dbname=CHANGE_ME;charset=utf8mb4',
        'user' => 'CHANGE_ME',
        'password' => 'CHANGE_ME',
    ],
    'app' => [
        'site_url' => 'https://vitalina-design.ru',
        'base_path' => '/cms',
        'upload_dir' => dirname(__DIR__, 2) . '/portfolio/assets/projects',
        'upload_url' => '/portfolio/assets/projects',
        'session_name' => 'vitalina_cms',
        'storage_dir' => dirname(__DIR__, 2) . '/storage',
        'publish_dir' => dirname(__DIR__, 2) . '/storage/publish',
        'backup_dir' => dirname(__DIR__, 2) . '/storage/backups',
    ],
];
