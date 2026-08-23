<?php
declare(strict_types=1);

function cms_check(string $name, bool $ok, string $detail): array
{
    return ['name' => $name, 'ok' => $ok, 'detail' => $detail];
}

function run_cms_checks(array $config, ?PDO $pdo): array
{
    $root = dirname(__DIR__, 2);
    $configPath = dirname(__DIR__) . '/config/config.php';
    $results = [];
    $results[] = cms_check('php', PHP_VERSION_ID >= 80500, 'PHP ' . PHP_VERSION . (PHP_VERSION_ID >= 80500 ? '' : ' (требуется 8.5+)'));
    $results[] = cms_check('config', is_file($configPath) && is_readable($configPath), is_file($configPath) ? 'config.php доступен' : 'config.php отсутствует');

    if ($pdo === null) {
        $results[] = cms_check('database', false, 'база данных недоступна');
        $results[] = cms_check('tables', false, 'подключение к базе не выполнено');
    } else {
        $results[] = cms_check('database', true, 'подключение установлено');
        $requiredTables = ['users', 'projects', 'project_images', 'revisions', 'pages', 'page_revisions', 'publication_revisions'];
        foreach ($requiredTables as $table) {
            try {
                $statement = $pdo->query('SELECT 1 FROM `' . $table . '` LIMIT 1');
                $results[] = cms_check('table:' . $table, $statement !== false, $statement !== false ? 'таблица доступна' : 'таблица недоступна');
            } catch (Throwable $exception) {
                $results[] = cms_check('table:' . $table, false, 'таблица недоступна');
            }
        }
    }

    $paths = [
        'storage' => $config['app']['storage_dir'] ?? $root . '/storage',
        'publish' => $config['app']['publish_dir'] ?? $root . '/storage/publish',
        'backups' => $config['app']['backup_dir'] ?? $root . '/storage/backups',
        'uploads' => $config['app']['upload_dir'] ?? $root . '/portfolio/assets/projects',
    ];
    foreach ($paths as $name => $path) {
        $directory = (string) $path;
        $created = is_dir($directory) || @mkdir($directory, 0775, true);
        $results[] = cms_check('write:' . $name, $created && is_writable($directory), $created && is_writable($directory) ? 'директория доступна' : 'директория недоступна для записи');
    }

    foreach (['skins/saparova/css/main.css', 'skins/saparova/img/vi-black.svg', 'portfolio/about.htm', 'portfolio/service.htm', 'portfolio/contact.html'] as $asset) {
        $results[] = cms_check('asset:' . $asset, is_file($root . '/' . $asset), is_file($root . '/' . $asset) ? 'файл найден' : 'файл отсутствует');
    }
    foreach (['about' => 'portfolio/about.htm', 'service' => 'portfolio/service.htm', 'contact' => 'portfolio/contact.html'] as $slug => $asset) {
        $html = is_file($root . '/' . $asset) ? (string) file_get_contents($root . '/' . $asset) : '';
        $ok = str_contains($html, '<!-- CMS:' . $slug . ':start -->') && str_contains($html, '<!-- CMS:' . $slug . ':end -->');
        $results[] = cms_check('markers:' . $slug, $ok, $ok ? 'маркеры найдены' : 'маркеры отсутствуют');
    }
    return $results;
}

function print_check_results(array $results): int
{
    $failed = 0;
    foreach ($results as $result) {
        $status = $result['ok'] ? 'OK' : 'FAIL';
        echo '[' . $status . '] ' . $result['name'] . ': ' . $result['detail'] . PHP_EOL;
        if (!$result['ok']) {
            $failed++;
        }
    }
    echo $failed === 0 ? "CMS check passed.\n" : "CMS check failed: {$failed} check(s).\n";
    return $failed === 0 ? 0 : 1;
}
