<?php
declare(strict_types=1);

function render_template(string $template, array $context): string
{
    $templatePath = dirname(__DIR__) . '/templates/' . ltrim($template, '/');
    $templatesRoot = realpath(dirname(__DIR__) . '/templates');
    $resolvedPath = realpath($templatePath);

    if ($templatesRoot === false || $resolvedPath === false || !str_starts_with($resolvedPath, $templatesRoot . DIRECTORY_SEPARATOR)) {
        throw new InvalidArgumentException('Invalid template path.');
    }

    extract($context, EXTR_SKIP);
    ob_start();
    try {
        require $resolvedPath;
        return (string) ob_get_clean();
    } catch (Throwable $exception) {
        ob_end_clean();
        throw $exception;
    }
}

function write_staged_file(string $stagingRoot, string $relativePath, string $contents): void
{
    $relativePath = ltrim(str_replace('\\', '/', $relativePath), '/');
    if ($relativePath === '' || str_contains($relativePath, '..')) {
        throw new InvalidArgumentException('Invalid staged file path.');
    }

    $target = rtrim($stagingRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
    $parent = dirname($target);
    if (!is_dir($parent) && !mkdir($parent, 0775, true) && !is_dir($parent)) {
        throw new RuntimeException('Unable to create staging directory.');
    }
    if (file_put_contents($target, $contents, LOCK_EX) === false) {
        throw new RuntimeException('Unable to write staged file.');
    }
}
