# CMS Safe Publishing Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Replace the current direct string-based publisher with readable templates, validated staged publication, publication backups, CMS rollback, and operational documentation/checks.

**Architecture:** Keep the existing PHP/MySQL CMS and public URLs. Split HTML into PHP templates, keep database access in publisher services, render the complete managed file set into a staging directory, validate it, back up current targets, then replace targets atomically. Store publication manifests in the database and use them for complete rollback.

**Tech Stack:** PHP 8.5, PDO MySQL/MariaDB, MySQL JSON, filesystem APIs, existing session/CSRF bootstrap, CLI PHP.

## Global Constraints

- Existing public URLs, managed page markers, asset paths, and visual markup remain compatible.
- A failed publication must not modify production HTML files.
- All filesystem paths are derived from configured roots and normalized relative paths.
- Templates do not query the database or write files.
- Existing unrelated working-tree changes must not be reverted or reformatted.
- Do not add framework dependencies.
- Technical exceptions must not expose SQL, filesystem internals, or credentials.

---

### Task 1: Add Publisher Boundaries And Templates

**Files:**
- Create: `cms/templates/catalog.php`
- Create: `cms/templates/project.php`
- Create: `cms/templates/page-shell.php`
- Create: `cms/app/template.php`
- Modify: `cms/app/publisher.php`
- Test: `cms/tools/test-publisher.php`

**Interfaces:**
- `render_template(string $template, array $context): string` loads only files under `cms/templates` and extracts the context into a buffered PHP template.
- `build_project_context(array $project, array $images, string $siteUrl): array` returns escaped scalar values and the rendered gallery fragment.
- `build_catalog_context(array $projects, string $siteUrl): array` returns the catalog heading and rendered project-card fragment.
- `write_staged_file(string $stagingRoot, string $relativePath, string $contents): void` creates parent directories and writes only under the staging root.

- [ ] **Step 1: Add a failing template smoke test**

Create a CLI test that calls `render_template()` with a minimal context and asserts that the returned catalog/project HTML contains the escaped title, expected link, and no raw unescaped `<script>` content.

- [ ] **Step 2: Run the smoke test and verify it fails**

Run: `php cms/tools/test-publisher.php`

Expected: FAIL because the template renderer and template files do not exist.

- [ ] **Step 3: Implement the renderer and templates**

Move the current catalog and project HTML structure out of `publisher.php` into the three templates. Preserve these current paths and markup contracts:

```php
$html = render_template('project.php', [
    'title' => e($project['title']),
    'description' => e($description),
    'slug' => e($project['slug']),
    'gallery' => $gallery,
]);
```

The page-shell template must accept `before`, `body`, and `after`, preserving `<!-- CMS:<slug>:start -->` and `<!-- CMS:<slug>:end -->` in the resulting file.

- [ ] **Step 4: Run the smoke test and verify it passes**

Run: `php cms/tools/test-publisher.php`

Expected: PASS with a zero exit code.

---

### Task 2: Add Staging, Validation, Backup, And Publication Migration

**Files:**
- Create: `cms/app/publication.php`
- Create: `cms/database/migration-002-publications.sql`
- Modify: `cms/app/publisher.php`
- Modify: `cms/config/config.example.php`
- Modify: `cms/app/bootstrap.php`
- Modify: `.gitignore`
- Test: `cms/tools/test-publication.php`

**Interfaces:**
- `managed_publication_files(PDO $pdo): array` returns relative target paths and generated contents.
- `validate_publication_package(string $stagingRoot, array $files, array $config): array` returns a manifest or throws a safe validation exception.
- `create_publication_backup(string $root, array $files, string $backupRoot): array` copies existing target files and returns the manifest.
- `replace_publication(string $root, string $stagingRoot, array $manifest): void` atomically replaces or removes the complete target set.
- `publish_site(PDO $pdo, array $config, int $userId, string $comment = ''): array` returns publication ID, file count, and backup path.
- `record_publication_failure(PDO $pdo, int $userId, string $comment, Throwable $exception): void` records only a generic safe message plus a server-side log entry.

- [ ] **Step 1: Add failing filesystem tests**

Create a temporary fixture tree with one existing target, one generated staged target, and one missing image. Assert that validation rejects the package and that the existing target content is unchanged. Add a second fixture with valid files and assert backup creation records the original hash.

- [ ] **Step 2: Run the tests and verify they fail**

Run: `php cms/tools/test-publication.php`

Expected: FAIL because publication helpers and migration-backed history do not exist.

- [ ] **Step 3: Add storage configuration and publication schema**

Extend the config example with `storage_dir`, `publish_dir`, and `backup_dir`, all rooted under the project root. Add `publication_revisions` with `user_id`, `status`, `comment`, `backup_path`, `manifest`, `error_message`, and timestamps, plus foreign keys to `users`.

Ensure bootstrap creates no directories implicitly for web requests and logs unexpected exceptions to `storage/logs/cms.log` without displaying database details.

- [ ] **Step 4: Implement staging and validation**

Make `publish_site()` render every managed target into a unique staging directory, validate HTML existence, project slug/cover/images, duplicate targets, and CMS markers, then create a backup before replacement. Use relative paths in manifests and reject `..`, absolute paths, and paths outside the configured project root.

- [ ] **Step 5: Implement atomic replacement and failure restoration**

Copy staged contents to target-specific temporary files in the target directory and rename them into place. On a replacement exception, restore every backed-up target before rethrowing. Do not update the database as published until all files are replaced.

- [ ] **Step 6: Run the filesystem tests and verify they pass**

Run: `php cms/tools/test-publication.php`

Expected: PASS for invalid-package safety, valid backup hashes, and replacement/restore behavior.

---

### Task 3: Wire Publication UI And Published-File Rollback

**Files:**
- Modify: `cms/admin/publish.php`
- Modify: `cms/admin/index.php`
- Create: `cms/admin/publications.php`
- Create: `cms/admin/publication-rollback.php`
- Modify: `cms/app/publication.php`

**Interfaces:**
- `list_publications(PDO $pdo): array` returns publication metadata and user email.
- `rollback_publication(PDO $pdo, array $config, int $publicationId, int $userId): int` validates the manifest, restores the complete publication, and records a rollback event.

- [ ] **Step 1: Add the publication history query and rollback service**

Join `publication_revisions` to `users`, order newest first, and show only safe fields. For rollback, validate every backup file before changing any production target, restore absent-file state as recorded in the manifest, and create a new `rolled_back` history row.

- [ ] **Step 2: Update the publish page**

Replace direct calls to `publish_projects()` and `publish_pages()` with `publish_site()`. Display success with publication ID and file count. Display a generic error and link to publication history; log the detailed exception.

- [ ] **Step 3: Add history and rollback forms**

Add CSRF-protected forms with POST-only rollback. Require authentication, reject unknown IDs, and redirect with a status message. Add navigation links from project admin and publication pages.

- [ ] **Step 4: Verify the admin flow statically**

Run:

```powershell
php -l cms/admin/publish.php
php -l cms/admin/publications.php
php -l cms/admin/publication-rollback.php
```

Expected: all files report no syntax errors.

---

### Task 4: Implement The CMS Health Check

**Files:**
- Create: `cms/tools/check.php`
- Create: `cms/app/checks.php`
- Modify: `cms/config/config.example.php`

**Interfaces:**
- `run_cms_checks(array $config, ?PDO $pdo): array` returns an array of `{name, ok, detail}` results.
- `print_check_results(array $results): int` prints one line per check and returns `0` only when all checks pass.

- [ ] **Step 1: Add the check runner**

Implement named checks for PHP 8.5 compatibility floor, config readability, DB connection, required tables, storage/staging/backup/upload writability, public assets, and CMS markers in `about.htm`, `service.htm`, and `contact.html`.

- [ ] **Step 2: Hide technical errors**

Catch PDO and filesystem exceptions per check. Print a concise failure reason such as `database: unavailable`, never DSNs, credentials, SQL, or stack traces.

- [ ] **Step 3: Run the CLI check in the current environment**

Run: `php cms/tools/check.php`

Expected: a readable named report and a non-zero exit code if `config/config.php` or the database is not configured; no fatal error and no credential output.

---

### Task 5: Rewrite Operational Documentation

**Files:**
- Modify: `cms/README.md`
- Create: `CHANGELOG.md`
- Modify: `.gitignore`

- [ ] **Step 1: Document the full workflow**

Add sections for local launch, MySQL setup, admin creation/removal, import, project/page editing, preview, staging publication, publication history, rollback, FTP deployment, and recovery after a failed publication.

- [ ] **Step 2: Document required storage and migration setup**

Include the migration command/order, required writable directories, backup retention expectations, and the exact health-check command:

```bash
php cms/tools/check.php
```

- [ ] **Step 3: Add the first changelog entry and ignore secrets/runtime data**

Add a safe-publishing entry to `CHANGELOG.md` and ignore `cms/config/config.php`, `storage/logs/`, `storage/publish/`, and `storage/backups/` without removing or altering existing user ignore rules.

---

### Task 6: Run Full Verification

**Files:**
- Test: all changed PHP files and CLI fixtures

- [ ] **Step 1: Run PHP syntax checks**

Run:

```powershell
$files = Get-ChildItem -Recurse -Filter '*.php' cms | ForEach-Object { $_.FullName }
foreach ($file in $files) { php -l $file }
```

Expected: every file reports no syntax errors.

- [ ] **Step 2: Run focused CLI checks**

Run:

```powershell
php cms/tools/test-publisher.php
php cms/tools/test-publication.php
php cms/tools/check.php
```

Expected: publisher/publication fixture tests pass; health check returns a readable result and may fail only for intentionally absent local config/database.

- [ ] **Step 3: Check the resulting diff**

Run: `git diff --check`

Expected: no whitespace errors. Confirm no unrelated working-tree files were modified by the implementation.
