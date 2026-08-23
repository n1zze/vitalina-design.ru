<?php
declare(strict_types=1);

$configPath = dirname(__DIR__) . '/config/config.php';
if (!is_file($configPath)) {
    http_response_code(500);
    exit('CMS is not configured. Copy config.example.php to config.php.');
}

$config = require $configPath;

function cms_log(string $message, ?Throwable $exception = null): void
{
    global $config;
    $logDirectory = rtrim((string) ($config['app']['storage_dir'] ?? dirname(__DIR__, 2) . '/storage'), '/\\') . '/logs';
    if (!is_dir($logDirectory)) {
        @mkdir($logDirectory, 0775, true);
    }
    $detail = $exception ? ' ' . get_class($exception) . ': ' . $exception->getMessage() : '';
    @file_put_contents($logDirectory . '/cms.log', '[' . date('c') . '] ' . $message . $detail . PHP_EOL, FILE_APPEND | LOCK_EX);
}

session_name($config['app']['session_name']);
session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
    'cookie_secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
]);

try {
    $pdo = new PDO($config['db']['dsn'], $config['db']['user'], $config['db']['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $exception) {
    cms_log('Database connection failed.', $exception);
    http_response_code(500);
    exit('Database connection failed.');
}

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function require_csrf(): void
{
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(419);
        exit('Invalid CSRF token.');
    }
}

function require_auth(): void
{
    if (empty($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}
