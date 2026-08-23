<?php
declare(strict_types=1);

require dirname(__DIR__) . '/app/checks.php';

$configPath = dirname(__DIR__) . '/config/config.php';
$config = [];
$pdo = null;
if (is_file($configPath) && is_readable($configPath)) {
    try {
        $config = require $configPath;
        $pdo = new PDO($config['db']['dsn'], $config['db']['user'], $config['db']['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (Throwable $exception) {
        $pdo = null;
    }
}

exit(print_check_results(run_cms_checks($config, $pdo)));
