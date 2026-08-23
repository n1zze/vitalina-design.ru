<?php
declare(strict_types=1);

require_once __DIR__ . '/publisher.php';

function storage_path(array $config, string $key): string
{
    $root = dirname(__DIR__, 2) . '/storage';
    $defaults = ['storage_dir' => $root, 'publish_dir' => $root . '/publish', 'backup_dir' => $root . '/backups'];
    $path = $config['app'][$key] ?? ($defaults[$key] ?? '');
    if (!is_string($path) || $path === '') {
        throw new RuntimeException('CMS storage is not configured.');
    }
    return rtrim($path, '/\\');
}

function normalized_relative_path(string $path): string
{
    $path = ltrim(str_replace('\\', '/', $path), '/');
    if ($path === '' || str_contains($path, '..') || preg_match('/^[A-Za-z]:/', $path)) {
        throw new InvalidArgumentException('Invalid publication path.');
    }
    return $path;
}

function ensure_directory(string $directory): void
{
    if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
        throw new RuntimeException('Unable to create CMS storage directory.');
    }
}

function publication_id(): string
{
    return date('Ymd-His') . '-' . bin2hex(random_bytes(4));
}

function managed_page_definitions(): array
{
    return [
        'about' => 'portfolio/about.htm',
        'service' => 'portfolio/service.htm',
        'contact' => 'portfolio/contact.html',
    ];
}

function page_staged_html(PDO $pdo, string $root, string $slug, string $relativeFile): ?string
{
    $statement = $pdo->prepare('SELECT body_html FROM pages WHERE slug = ? AND is_published = 1 LIMIT 1');
    $statement->execute([$slug]);
    $page = $statement->fetch();
    if (!$page) {
        return null;
    }
    $html = @file_get_contents($root . '/' . $relativeFile);
    if ($html === false) {
        throw new RuntimeException('Managed page is missing: ' . $relativeFile);
    }
    $start = '<!-- CMS:' . $slug . ':start -->';
    $end = '<!-- CMS:' . $slug . ':end -->';
    $startPosition = strpos($html, $start);
    $endPosition = strpos($html, $end);
    if ($startPosition === false || $endPosition === false || $endPosition <= $startPosition) {
        throw new RuntimeException('CMS markers are missing: ' . $slug);
    }
    return render_template('page-shell.php', [
        'before' => substr($html, 0, $startPosition + strlen($start)),
        'body' => $page['body_html'],
        'after' => substr($html, $endPosition),
    ]);
}

function build_publication_package(PDO $pdo, array $config, string $root, string $stagingRoot): array
{
    $projects = $pdo->query('SELECT * FROM projects WHERE is_published = 1 ORDER BY sort_order, id')->fetchAll();
    $files = [];
    $projectPaths = [];
    $catalogProjects = [];
    $imagesRoot = rtrim((string) $config['app']['upload_dir'], '/\\');

    foreach ($projects as $project) {
        $slug = trim((string) $project['slug']);
        if ($slug === '' || !preg_match('/^[a-z0-9-]+$/', $slug)) {
            throw new RuntimeException('Invalid published project slug.');
        }
        $images = project_images($pdo, (int) $project['id']);
        $cover = trim((string) ($project['cover_path'] ?: ($images[0]['path'] ?? '')));
        if ($cover === '' || $images === []) {
            throw new RuntimeException('Published project has no cover or images: ' . $slug);
        }
        if (!is_file($imagesRoot . '/' . normalized_relative_path($cover))) {
            throw new RuntimeException('Project cover is missing: ' . $cover);
        }
        foreach ($images as $image) {
            $imagePath = $imagesRoot . '/' . normalized_relative_path((string) $image['path']);
            if (!is_file($imagePath)) {
                throw new RuntimeException('Project image is missing: ' . $image['path']);
            }
        }
        $relativePath = 'portfolio/privateinterior/' . $slug . '.html';
        if (isset($files[$relativePath])) {
            throw new RuntimeException('Duplicate publication target: ' . $relativePath);
        }
        $files[$relativePath] = render_template('project.php', project_context($project, $images, $config['app']['site_url']));
        $projectPaths[$relativePath] = true;
        $project['cover_path'] = $cover;
        $catalogProjects[] = $project;
    }

    $files['portfolio/index.htm'] = render_template('catalog.php', catalog_context($catalogProjects, $config['app']['site_url']));
    foreach (managed_page_definitions() as $slug => $relativeFile) {
        $html = page_staged_html($pdo, $root, $slug, $relativeFile);
        if ($html !== null) {
            $files[$relativeFile] = $html;
        }
    }
    $previousPaths = previous_project_paths($pdo);
    $deletions = array_values(array_diff($previousPaths, array_keys($projectPaths)));
    foreach ($files as $relativePath => $contents) {
        write_staged_file($stagingRoot, $relativePath, $contents);
    }
    return ['files' => $files, 'deletions' => $deletions];
}

function previous_project_paths(PDO $pdo): array
{
    try {
        $statement = $pdo->query("SELECT manifest FROM publication_revisions WHERE status = 'published' ORDER BY id DESC LIMIT 1");
        $manifest = json_decode((string) $statement->fetchColumn(), true, 512, JSON_THROW_ON_ERROR);
        $paths = [];
        foreach ($manifest as $item) {
            $path = (string) ($item['relative_path'] ?? '');
            if (str_starts_with($path, 'portfolio/privateinterior/') && str_ends_with($path, '.html')) {
                $paths[] = normalized_relative_path($path);
            }
        }
        return $paths;
    } catch (Throwable $exception) {
        return [];
    }
}

function validate_publication_package(string $stagingRoot, array $files, string $root, array $deletions = []): array
{
    $manifest = [];
    foreach ($files as $relativePath => $contents) {
        $relativePath = normalized_relative_path($relativePath);
        $stagedPath = $stagingRoot . '/' . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
        if (!is_file($stagedPath) || !is_readable($stagedPath)) {
            throw new RuntimeException('Staged file is missing: ' . $relativePath);
        }
        $html = (string) file_get_contents($stagedPath);
        if ($html === '' || !str_contains(strtolower($html), '<!doctype html>') || !str_contains(strtolower($html), '<html') || !str_contains(strtolower($html), '</html>')) {
            throw new RuntimeException('Invalid staged HTML: ' . $relativePath);
        }
        if (preg_match('#^portfolio/(about|service|contact)\.(?:htm|html)$#', $relativePath, $match)) {
            $slug = $match[1];
            if (!str_contains($html, '<!-- CMS:' . $slug . ':start -->') || !str_contains($html, '<!-- CMS:' . $slug . ':end -->')) {
                throw new RuntimeException('CMS markers are missing in staged HTML: ' . $relativePath);
            }
        }
        $target = $root . '/' . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
        $manifest[] = [
            'relative_path' => $relativePath,
            'action' => 'write',
            'target_path' => $target,
            'existed' => is_file($target),
            'backup_path' => '',
            'before_hash' => is_file($target) ? hash_file('sha256', $target) : null,
            'after_hash' => hash_file('sha256', $stagedPath),
        ];
    }
    foreach ($deletions as $relativePath) {
        $relativePath = normalized_relative_path($relativePath);
        $target = $root . '/' . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
        $manifest[] = [
            'relative_path' => $relativePath,
            'action' => 'delete',
            'target_path' => $target,
            'existed' => is_file($target),
            'backup_path' => '',
            'before_hash' => is_file($target) ? hash_file('sha256', $target) : null,
            'after_hash' => null,
        ];
    }
    if ($manifest === []) {
        throw new RuntimeException('Publication package is empty.');
    }
    return $manifest;
}

function create_publication_backup(string $root, array &$manifest, string $backupRoot): void
{
    ensure_directory($backupRoot);
    foreach ($manifest as &$item) {
        $relativePath = $item['relative_path'];
        if (!$item['existed']) {
            continue;
        }
        $backupPath = $backupRoot . '/' . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
        ensure_directory(dirname($backupPath));
        if (!copy($item['target_path'], $backupPath)) {
            throw new RuntimeException('Unable to backup: ' . $relativePath);
        }
        $item['backup_path'] = $backupPath;
    }
    unset($item);
    file_put_contents($backupRoot . '/manifest.json', json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR), LOCK_EX);
}

function replace_publication(string $root, string $stagingRoot, array $manifest): void
{
    $replaced = [];
    try {
        foreach ($manifest as $item) {
            $target = $item['target_path'];
            $stagedPath = $stagingRoot . '/' . str_replace('/', DIRECTORY_SEPARATOR, $item['relative_path']);
            if (($item['action'] ?? 'write') === 'delete') {
                if (is_file($target) && !@unlink($target)) {
                    throw new RuntimeException('Unable to delete stale publication file: ' . $item['relative_path']);
                }
                $replaced[] = $item;
                continue;
            }
            ensure_directory(dirname($target));
            $temporary = $target . '.publish-' . bin2hex(random_bytes(4));
            if (!copy($stagedPath, $temporary) || !rename($temporary, $target)) {
                @unlink($temporary);
                throw new RuntimeException('Unable to replace: ' . $item['relative_path']);
            }
            $replaced[] = $item;
        }
    } catch (Throwable $exception) {
        $restoreError = null;
        foreach (array_reverse($replaced) as $item) {
            try {
                if ($item['existed'] && is_file($item['backup_path'])) {
                    restore_file_atomically($item['backup_path'], $item['target_path']);
                } elseif (!$item['existed']) {
                    if (is_file($item['target_path']) && !@unlink($item['target_path'])) {
                        throw new RuntimeException('Unable to remove partially published file.');
                    }
                }
            } catch (Throwable $restoreException) {
                $restoreError = $restoreException;
                cms_log('Publication recovery failed.', $restoreException);
            }
        }
        if ($restoreError !== null) {
            throw new RuntimeException('Publication failed and recovery was incomplete.', 0, $restoreError);
        }
        throw $exception;
    }
}

function restore_file_atomically(string $source, string $target): void
{
    if (!is_file($source)) {
        throw new RuntimeException('Backup file is missing.');
    }
    ensure_directory(dirname($target));
    $temporary = $target . '.restore-' . bin2hex(random_bytes(4));
    if (!copy($source, $temporary) || !rename($temporary, $target)) {
        @unlink($temporary);
        throw new RuntimeException('Unable to restore backup file.');
    }
    if (hash_file('sha256', $target) !== hash_file('sha256', $source)) {
        throw new RuntimeException('Restored backup hash mismatch.');
    }
}

function restore_manifest(array $manifest): void
{
    foreach (array_reverse($manifest) as $item) {
        if (($item['existed'] ?? false) && is_file((string) ($item['backup_path'] ?? ''))) {
            restore_file_atomically((string) $item['backup_path'], (string) $item['target_path']);
        } elseif (!($item['existed'] ?? false) && is_file((string) $item['target_path'])) {
            if (!@unlink((string) $item['target_path'])) {
                throw new RuntimeException('Unable to remove partially published file.');
            }
        }
    }
}

function publication_lock(array $config)
{
    $lockPath = storage_path($config, 'storage_dir') . '/publish.lock';
    ensure_directory(dirname($lockPath));
    $handle = fopen($lockPath, 'c');
    if ($handle === false || !flock($handle, LOCK_EX)) {
        throw new RuntimeException('Unable to acquire publication lock.');
    }
    return $handle;
}

function publish_site(PDO $pdo, array $config, int $userId, string $comment = ''): array
{
    $root = dirname(__DIR__, 2);
    $id = publication_id();
    $stagingRoot = storage_path($config, 'publish_dir') . '/' . $id;
    $backupRoot = storage_path($config, 'backup_dir') . '/' . $id;
    $manifest = [];
    ensure_directory($stagingRoot);
    $lock = publication_lock($config);
    $productionReplaced = false;
    $history = $pdo->prepare('INSERT INTO publication_revisions (user_id,status,comment,backup_path,manifest,error_message) VALUES (?,?,?,?,?,?)');
    $history->execute([$userId, 'failed', $comment, $backupRoot, '{}', 'Publication failed.']);
    $historyId = (int) $pdo->lastInsertId();
    try {
        $package = build_publication_package($pdo, $config, $root, $stagingRoot);
        $manifest = validate_publication_package($stagingRoot, $package['files'], $root, $package['deletions']);
        create_publication_backup($root, $manifest, $backupRoot);
        replace_publication($root, $stagingRoot, $manifest);
        $productionReplaced = true;
        $statement = $pdo->prepare('UPDATE publication_revisions SET status=?, backup_path=?, manifest=?, error_message=? WHERE id=?');
        $statement->execute(['published', $backupRoot, json_encode($manifest, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR), '', $historyId]);
        return ['id' => $historyId, 'files' => count($manifest), 'backup_path' => $backupRoot];
    } catch (Throwable $exception) {
        cms_log('Publication failed.', $exception);
        if ($productionReplaced) {
            try {
                restore_manifest($manifest);
            } catch (Throwable $restoreException) {
                cms_log('Publication recovery failed.', $restoreException);
            }
        }
        try {
            $statement = $pdo->prepare('UPDATE publication_revisions SET manifest=?, error_message=? WHERE id=?');
            $statement->execute([json_encode($manifest, JSON_UNESCAPED_SLASHES), 'Publication failed.', $historyId]);
        } catch (Throwable $ignored) {
            cms_log('Unable to record failed publication.', $ignored);
        }
        throw new RuntimeException('Publication failed. Check CMS logs.', 0, $exception);
    } finally {
        remove_directory($stagingRoot);
        flock($lock, LOCK_UN);
        fclose($lock);
    }
}

function remove_directory(string $directory): void
{
    if (!is_dir($directory)) {
        return;
    }
    $items = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($items as $item) {
        $item->isDir() ? @rmdir($item->getPathname()) : @unlink($item->getPathname());
    }
    @rmdir($directory);
}

function list_publications(PDO $pdo): array
{
    return $pdo->query('SELECT p.id, p.status, p.comment, p.backup_path, p.manifest, p.error_message, p.created_at, u.email FROM publication_revisions p JOIN users u ON u.id = p.user_id ORDER BY p.id DESC')->fetchAll();
}

function rollback_publication(PDO $pdo, array $config, int $publicationId, int $userId): int
{
    $lock = publication_lock($config);
    $statement = $pdo->prepare('SELECT backup_path, manifest FROM publication_revisions WHERE id = ? AND status = ?');
    $statement->execute([$publicationId, 'published']);
    $publication = $statement->fetch();
    if (!$publication) {
        flock($lock, LOCK_UN);
        fclose($lock);
        throw new RuntimeException('Publication not found.');
    }
    $manifest = json_decode((string) $publication['manifest'], true, 512, JSON_THROW_ON_ERROR);
    $backupRoot = realpath((string) $publication['backup_path']);
    $configuredBackupRoot = realpath(storage_path($config, 'backup_dir'));
    $root = realpath(dirname(__DIR__, 2));
    if ($backupRoot === false || $configuredBackupRoot === false || $root === false || !str_starts_with($backupRoot, $configuredBackupRoot . DIRECTORY_SEPARATOR)) {
        throw new RuntimeException('Publication backup is unavailable.');
    }
    foreach ($manifest as $item) {
        $relativePath = normalized_relative_path((string) ($item['relative_path'] ?? ''));
        $target = $root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
        if (!str_starts_with($target, $root . DIRECTORY_SEPARATOR)) {
            throw new RuntimeException('Invalid rollback target.');
        }
        if (!empty($item['existed'])) {
            $backup = $backupRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
            if (!is_file($backup)) {
                throw new RuntimeException('Rollback backup file is missing: ' . $relativePath);
            }
        }
    }

    $currentStatement = $pdo->query("SELECT manifest FROM publication_revisions WHERE status = 'published' ORDER BY id DESC LIMIT 1");
    $currentManifest = json_decode((string) $currentStatement->fetchColumn(), true, 512, JSON_THROW_ON_ERROR);
    $selectedPaths = [];
    $selectedState = [];
    foreach ($manifest as $item) {
        $path = normalized_relative_path((string) $item['relative_path']);
        $selectedPaths[$path] = true;
        $selectedState[$path] = ($item['action'] ?? 'write') !== 'delete';
    }
    $statePaths = array_unique(array_merge(array_keys($selectedPaths), array_map(static fn (array $item): string => normalized_relative_path((string) $item['relative_path']), $currentManifest)));
    $safetyRoot = storage_path($config, 'backup_dir') . '/rollback-' . publication_id();
    $safetyManifest = [];
    foreach ($statePaths as $relativePath) {
        $target = $root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
        $safetyManifest[] = [
            'relative_path' => $relativePath,
            'action' => 'write',
            'target_path' => $target,
            'existed' => is_file($target),
            'backup_path' => '',
            'before_hash' => is_file($target) ? hash_file('sha256', $target) : null,
            'after_hash' => null,
        ];
    }
    create_publication_backup($root, $safetyManifest, $safetyRoot);
    $rollbackRoot = storage_path($config, 'publish_dir') . '/rollback-' . publication_id();
    ensure_directory($rollbackRoot);
    try {
        $rollbackManifest = [];
        foreach ($safetyManifest as $safetyItem) {
            $relativePath = $safetyItem['relative_path'];
            $target = $safetyItem['target_path'];
            $desiredExists = $selectedState[$relativePath] ?? false;
            $selectedItem = null;
            foreach ($manifest as $item) {
                if (normalized_relative_path((string) $item['relative_path']) === $relativePath) {
                    $selectedItem = $item;
                    break;
                }
            }
            if ($desiredExists) {
                if ($selectedItem === null || ($selectedItem['action'] ?? 'write') === 'delete') {
                    throw new RuntimeException('Selected publication content is unavailable: ' . $relativePath);
                }
                $selectedBackup = $backupRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
                write_staged_file($rollbackRoot, $relativePath, (string) file_get_contents($selectedBackup));
            }
            $rollbackManifest[] = [
                'relative_path' => $relativePath,
                'target_path' => $target,
                'action' => $desiredExists ? 'write' : 'delete',
                'existed' => $safetyItem['existed'],
                'backup_path' => $safetyItem['backup_path'],
                'before_hash' => $safetyItem['before_hash'],
                'after_hash' => $desiredExists ? hash_file('sha256', $backupRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath)) : null,
            ];
        }
        replace_publication($root, $rollbackRoot, $rollbackManifest);
        $insert = $pdo->prepare('INSERT INTO publication_revisions (user_id,status,comment,backup_path,manifest,error_message) VALUES (?,?,?,?,?,?)');
        $insert->execute([$userId, 'rolled_back', 'Rollback publication #' . $publicationId, $safetyRoot, json_encode($rollbackManifest, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR), '']);
        return (int) $pdo->lastInsertId();
    } finally {
        remove_directory($rollbackRoot);
        flock($lock, LOCK_UN);
        fclose($lock);
    }
}
