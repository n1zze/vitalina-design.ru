<?php
declare(strict_types=1);

require_once __DIR__ . '/template.php';

function project_images(PDO $pdo, int $projectId): array
{
    $statement = $pdo->prepare('SELECT path, alt_text FROM project_images WHERE project_id = ? ORDER BY sort_order, id');
    $statement->execute([$projectId]);
    return $statement->fetchAll();
}

function project_context(array $project, array $images, string $siteUrl): array
{
    $title = (string) $project['title'];
    $description = (string) ($project['seo_description'] ?: ($title . ', ' . $project['area'] . ', ' . $project['city'] . '. ' . $project['status'] . ' от VITALINA DESIGN.'));
    $slug = (string) $project['slug'];
    $gallery = '';

    foreach ($images as $index => $image) {
        $url = '../assets/projects/' . e((string) $image['path']);
        $loading = $index === 0 ? 'fetchpriority="high"' : 'loading="lazy"';
        $alt = e((string) ($image['alt_text'] ?: $title . ', интерьер'));
        $gallery .= "    <a class=\"project-gallery__item fn-gallery\" href=\"{$url}\" data-fancybox=\"" . e($slug) . "\"><img src=\"{$url}\" alt=\"{$alt}\" {$loading} decoding=\"async\"></a>\n";
    }

    return [
        'title' => e($title),
        'description' => e($description),
        'canonical' => e($siteUrl . '/portfolio/privateinterior/' . $slug . '.html'),
        'eyebrow' => e($project['category'] . ' · ' . $project['city']),
        'meta' => e($project['area']) . ' · ' . e($project['status']) . ' · ' . e($project['city']),
        'gallery' => $gallery,
    ];
}

function catalog_context(array $projects, string $siteUrl): array
{
    $cards = '';
    foreach ($projects as $project) {
        $cover = (string) ($project['cover_path'] ?? '');
        $cards .= '<a class="project-card" href="privateinterior/' . e($project['slug']) . '.html">'
            . '<div class="project-card__image"><img src="assets/projects/' . e($cover) . '" alt="' . e($project['title']) . '" loading="lazy"></div>'
            . '<div class="project-card__body"><h2 class="project-card__title">' . e($project['title']) . '</h2>'
            . '<div class="project-card__meta">' . e($project['city'] . ' · ' . $project['area'] . ' · ' . $project['status']) . '</div></div></a>' . "\n";
    }

    return [
        'title' => 'Проекты - VITALINA DESIGN',
        'description' => 'Проекты VITALINA DESIGN',
        'canonical' => e($siteUrl . '/portfolio/'),
        'cards' => $cards,
    ];
}

function publish_projects(PDO $pdo, array $config): int
{
    $projects = $pdo->query('SELECT * FROM projects WHERE is_published = 1 ORDER BY sort_order, id')->fetchAll();
    $root = dirname(__DIR__, 2);
    $outputRoot = $root . '/portfolio';

    foreach ($projects as $project) {
        $images = project_images($pdo, (int) $project['id']);
        $context = project_context($project, $images, $config['app']['site_url']);
        $target = $outputRoot . '/privateinterior/' . $project['slug'] . '.html';
        file_put_contents($target . '.tmp', render_template('project.php', $context), LOCK_EX);
        rename($target . '.tmp', $target);
    }

    $catalogContext = catalog_context($projects, $config['app']['site_url']);
    $target = $outputRoot . '/index.htm';
    file_put_contents($target . '.tmp', render_template('catalog.php', $catalogContext), LOCK_EX);
    rename($target . '.tmp', $target);
    return count($projects);
}

function publish_pages(PDO $pdo): int
{
    $definitions = [
        'about' => 'portfolio/about.htm',
        'service' => 'portfolio/service.htm',
        'contact' => 'portfolio/contact.html',
    ];
    $root = dirname(__DIR__, 2);
    $published = 0;

    foreach ($definitions as $slug => $relativeFile) {
        $statement = $pdo->prepare('SELECT body_html FROM pages WHERE slug = ? AND is_published = 1 LIMIT 1');
        $statement->execute([$slug]);
        $page = $statement->fetch();
        if (!$page) {
            continue;
        }
        $target = $root . '/' . $relativeFile;
        $html = (string) file_get_contents($target);
        $start = '<!-- CMS:' . $slug . ':start -->';
        $end = '<!-- CMS:' . $slug . ':end -->';
        $startPosition = strpos($html, $start);
        $endPosition = strpos($html, $end);
        if ($startPosition === false || $endPosition === false || $endPosition <= $startPosition) {
            continue;
        }
        $before = substr($html, 0, $startPosition + strlen($start));
        $after = substr($html, $endPosition);
        $rendered = render_template('page-shell.php', ['before' => $before, 'body' => $page['body_html'], 'after' => $after]);
        file_put_contents($target . '.tmp', $rendered, LOCK_EX);
        rename($target . '.tmp', $target);
        $published++;
    }

    return $published;
}
