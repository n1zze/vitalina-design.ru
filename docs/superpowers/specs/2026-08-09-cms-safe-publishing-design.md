# CMS Safe Publishing Design

## Scope

This phase implements the practical first priority from `task.md`:

- separate publisher HTML into templates and services;
- publish through a validated staging directory with backups and atomic replacement;
- restore a previous publication from the CMS;
- document CMS operation and add `php cms/tools/check.php`.

This phase does not redesign public pages, introduce structured page fields, change authentication, optimize images, or change the FTP deployment model.

## Goals

- A failed publication must not modify production HTML files.
- A successful publication must be recoverable from the CMS.
- The existing public URLs, managed page markers, and visual markup remain compatible.
- HTML generation must be readable and editable by a junior developer.
- The CMS must provide a deterministic health check with a useful exit code.

## Template Architecture

The publisher will load three PHP templates:

- `cms/templates/catalog.php` renders `portfolio/index.htm`.
- `cms/templates/project.php` renders one project page.
- `cms/templates/page-shell.php` renders the managed body replacement for an existing page shell.

Templates receive explicit escaped values and rendered fragments through a context array. Data access and file operations remain in `cms/app/publisher.php` or small publisher helpers. Templates do not query the database or write files.

The current project URLs, catalog URL, asset paths, navigation, CMS markers, and existing page shells remain unchanged. The catalog and project templates preserve the current generated content rather than introducing a new public design.

## Safe Publication Flow

1. Create a unique staging directory under `storage/publish/<publication-id>/`.
2. Resolve the complete managed file set:
   - `portfolio/index.htm`;
   - one `portfolio/privateinterior/<slug>.html` per published project;
   - the managed page files for `about`, `service`, and `contact` that have published CMS data.
3. Render every file into staging only.
4. Validate the staged package:
   - every expected HTML file exists and is readable;
   - every published project has a non-empty slug, cover, and image list;
   - every referenced project image exists under the configured assets directory;
   - managed page files exist and contain matching CMS start/end markers;
   - no duplicate target paths are present.
5. Create a backup directory under `storage/backups/YYYY-MM-DD-HHMMSS[-suffix]/` and copy the current version of every target file there, recording existence and hashes in a manifest.
6. Replace production targets one at a time from staged files using temporary files and `rename()`.
7. Record the publication metadata and manifest in the database only after replacement succeeds.
8. Remove staging data after success; retain backups according to the existing filesystem policy (no automatic deletion in this phase).

If rendering or validation fails, no production file is touched. If replacement fails after at least one file has changed, the publisher attempts to restore the backup before returning an error. The publication record is marked failed with the error message.

## Publication History and Rollback

Add a migration for `publication_revisions` with:

- `id`;
- `user_id`;
- `status` (`published`, `failed`, or `rolled_back`);
- `comment`;
- `backup_path`;
- `manifest` JSON;
- `error_message`;
- `created_at`.

The manifest contains the relative target path, backup path, whether the file existed before publication, and before/after hashes. Paths are constrained to the managed public root and are never taken directly from a user request.

The publication admin page shows publication time, user, status, file count, and error summary. A rollback action accepts a CSRF token, loads the selected manifest, validates that all backup files are available, then restores the complete publication through a temporary directory. Missing pre-publication files are deleted only after the full backup has been validated. The rollback itself is recorded as a new publication history event.

## Health Check

`php cms/tools/check.php` runs without requiring an authenticated web session and reports named checks with exit code `0` only when all checks pass. It checks:

- PHP version against the supported minimum;
- `cms/config/config.php` existence and readability;
- database connection;
- required tables;
- write access to storage, staging, backup, and configured upload directories;
- existence of required public assets;
- CMS markers in managed pages.

It must not print database credentials or raw database exceptions.

## Documentation

Rewrite `cms/README.md` with sections for local setup, MySQL, administrator creation, import, editing, preview, publication, rollback, FTP deployment, and recovery after failure. Add `CHANGELOG.md` with the first safe-publishing release entry. Document that `create-admin.php` is a one-time setup tool and should be removed from the deployed public tree after use.

## Error Handling and Compatibility

- Existing user-facing CMS pages continue to use the current bootstrap and CSRF/authentication flow.
- Technical exceptions are logged or shown as a generic publication failure without exposing SQL, filesystem internals, or credentials.
- All filesystem paths are derived from configured roots and normalized relative paths.
- Existing unrelated working-tree changes are not reverted or reformatted.

## Verification

- Run `php -l` for all changed PHP files.
- Run `php cms/tools/check.php` and verify its result is understandable in an unconfigured environment.
- Exercise publisher validation with an invalid asset and confirm production files remain unchanged.
- Exercise a successful staged publication and rollback with a temporary fixture tree.
- Run `git diff --check`.
