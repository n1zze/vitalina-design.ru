<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') exit("CLI only\n");
$configPath = dirname(__DIR__) . '/config/config.php';
if (!is_file($configPath)) exit("Copy config.example.php to config.php first.\n");
$config = require $configPath;
$email = $argv[1] ?? '';
$password = $argv[2] ?? '';
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 12) exit("Usage: php create-admin.php admin@example.com 'password-at-least-12'\n");
$pdo = new PDO($config['db']['dsn'], $config['db']['user'], $config['db']['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$statement = $pdo->prepare('INSERT INTO users (email, password_hash) VALUES (?, ?)');
$statement->execute([$email, password_hash($password, PASSWORD_DEFAULT)]);
echo "Admin created. Remove this script from the server.\n";
