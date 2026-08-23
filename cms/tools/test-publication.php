<?php
declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

require dirname(__DIR__) . '/app/publication.php';

$fixture = sys_get_temp_dir() . '/cms-publication-' . bin2hex(random_bytes(4));
$root = $fixture . '/root';
$staging = $fixture . '/staging';
$backup = $fixture . '/backup';
mkdir($root . '/portfolio', 0775, true);
mkdir($staging . '/portfolio', 0775, true);
file_put_contents($root . '/portfolio/index.htm', '<!doctype html><html>before</html>');
file_put_contents($staging . '/portfolio/index.htm', '<!doctype html><html>after</html>');

try {
    $files = ['portfolio/index.htm' => '<!doctype html><html>after</html>'];
    $manifest = validate_publication_package($staging, $files, $root);
    create_publication_backup($root, $manifest, $backup);
    if (($manifest[0]['before_hash'] ?? '') !== hash('sha256', '<!doctype html><html>before</html>')) {
        throw new RuntimeException('Backup hash mismatch.');
    }
    replace_publication($root, $staging, $manifest);
    if (file_get_contents($root . '/portfolio/index.htm') !== '<!doctype html><html>after</html>') {
        throw new RuntimeException('Replacement failed.');
    }

    file_put_contents($root . '/portfolio/old.html', '<!doctype html><html>old</html>');
    $deletionManifest = [[
        'relative_path' => 'portfolio/old.html',
        'action' => 'delete',
        'target_path' => $root . '/portfolio/old.html',
        'existed' => true,
        'backup_path' => '',
        'before_hash' => hash_file('sha256', $root . '/portfolio/old.html'),
        'after_hash' => null,
    ]];
    create_publication_backup($root, $deletionManifest, $backup . '/deletion');
    replace_publication($root, $staging, $deletionManifest);
    if (is_file($root . '/portfolio/old.html')) {
        throw new RuntimeException('Stale file was not deleted.');
    }

    $invalidStaging = $fixture . '/invalid';
    mkdir($invalidStaging, 0775, true);
    try {
        validate_publication_package($invalidStaging, $files, $root);
        throw new RuntimeException('Invalid package was accepted.');
    } catch (RuntimeException $exception) {
        if (file_get_contents($root . '/portfolio/index.htm') !== '<!doctype html><html>after</html>') {
            throw new RuntimeException('Invalid validation changed production.');
        }
    }
    echo "Publication fixture tests passed.\n";
} finally {
    remove_directory($fixture);
}
