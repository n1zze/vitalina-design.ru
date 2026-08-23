CREATE TABLE publication_revisions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    status ENUM('published', 'failed', 'rolled_back') NOT NULL,
    comment VARCHAR(500) NOT NULL DEFAULT '',
    backup_path VARCHAR(500) NOT NULL DEFAULT '',
    manifest JSON NOT NULL,
    error_message TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_publication_revisions_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT,
    INDEX idx_publication_revisions_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
