<?php
declare(strict_types=1);
require dirname(__DIR__) . '/app/bootstrap.php';
require dirname(__DIR__) . '/app/publication.php';
require_auth();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed.');
}
require_csrf();
$publicationId = filter_input(INPUT_POST, 'publication_id', FILTER_VALIDATE_INT) ?: 0;
try {
    rollback_publication($pdo, $config, $publicationId, (int) $_SESSION['user_id']);
    header('Location: publications.php?rolled_back=1');
} catch (Throwable $exception) {
    cms_log('Publication rollback failed.', $exception);
    header('Location: publications.php?rollback_error=1');
}
exit;
