# CMS VITALINA DESIGN — анализ и предложения по улучшению

> Дата: 2026-08-16  
> Область: `cms/` — PHP CMS для управления проектами и страницами статического сайта vitalina-design.ru

---

## Содержание

1. [Назначение и архитектура](#назначение-и-архитектура)
2. [Модель данных](#модель-данных)
3. [Поток публикации](#поток-публикации)
4. [Сильные стороны](#сильные-стороны)
5. [Критические проблемы](#критические-проблемы)
6. [Предложения по улучшению](#предложения-по-улучшению)
7. [Карта файлов](#карта-файлов)
8. [Рекомендуемый порядок работ](#рекомендуемый-порядок-работ)

---

## Назначение и архитектура

CMS управляет контентом портфолио и отдельных страниц, а при публикации **генерирует статические HTML-файлы** в `portfolio/`. Публичный сайт не зависит от БД в runtime — подходит для shared hosting (PHP 8.5+, MySQL/MariaDB).

```
┌─────────────────┐     ┌──────────────┐     ┌─────────────────────────┐
│  cms/admin/     │────▶│   MySQL      │     │  portfolio/             │
│  project.php    │     │  projects    │     │  index.htm              │
│  page.php       │     │  pages       │     │  privateinterior/*.html │
│  publish.php    │     │  revisions   │     │  about.htm / service…   │
└────────┬────────┘     └──────────────┘     └─────────────────────────┘
         │
         ▼
┌────────────────────────────────────────────────────────────────────────┐
│  cms/app/publication.php                                               │
│  staging → validation → backup → atomic replace → history in DB        │
└────────────────────────────────────────────────────────────────────────┘
```

**Управляемые сущности:**

| Сущность | Где хранится | Куда публикуется |
|----------|--------------|------------------|
| Проекты | `projects`, `project_images` | `portfolio/index.htm`, `portfolio/privateinterior/<slug>.html` |
| Страницы | `pages` | Контент между маркерами `<!-- CMS:<slug>:start/end -->` в about, service, contact |
| История публикаций | `publication_revisions` | Backup в `storage/backups/<id>/` |

**Не входит в CMS:** главная (`index.html`), страницы услуг (`portfolio/service/*.html`), privacy.

---

## Модель данных

### Таблицы

| Таблица | Назначение |
|---------|------------|
| `users` | Администраторы (email + password_hash) |
| `projects` | Метаданные проекта: slug, title, category, city, area, status, SEO, cover_path, is_published, sort_order |
| `project_images` | Галерея: path, alt_text, sort_order |
| `revisions` | JSON-снимки правок проекта |
| `pages` | Управляемые страницы: slug, body_html, SEO |
| `page_revisions` | JSON-снимки правок страниц |
| `publication_revisions` | История публикаций: status, manifest, backup_path, comment |

### CMS-маркеры на страницах

```html
<!-- CMS:about:start -->
…редактируемый контент…
<!-- CMS:about:end -->
```

Аналогично для `service` и `contact` в файлах:

- `portfolio/about.htm`
- `portfolio/service.htm`
- `portfolio/contact.html`

---

## Поток публикации

Реализован в `cms/app/publication.php` — наиболее зрелая часть системы.

```
1. publication_lock()           — файловая блокировка
2. build_publication_package() — рендер в storage/publish/<id>/
3. validate_publication_package() — HTML, маркеры, наличие изображений
4. create_publication_backup()  — копия текущих файлов + manifest.json
5. replace_publication()        — атомарная замена через temp-файлы
6. Запись в publication_revisions (status: published | failed)
7. Очистка staging
```

**Rollback:** восстановление из backup выбранной публикации с предварительным safety-backup текущего состояния.

**Удаление устаревших файлов:** при публикации сравниваются пути проектов с manifest последней успешной публикации; HTML проектов, снятых с публикации, помечаются на удаление.

---

## Сильные стороны

| Область | Реализация |
|---------|------------|
| Безопасная публикация | Staging, backup, hash-проверки, path normalization, file lock |
| Аутентификация | `password_hash`, CSRF на POST, `session_regenerate_id`, httponly/samesite cookies |
| SQL | Prepared statements, `PDO::ATTR_EMULATE_PREPARES => false` |
| Шаблоны | Защита от path traversal в `render_template()` |
| Ops | `php cms/tools/check.php` с exit code 0/1, лог без credentials |
| Защита файлов | `.htaccess`: запрет листинга, блок SQL/MD; `tools/` — Require all denied |
| Импорт | `import.php` (project.md + assets), `import-pages.php` (маркеры → БД) |
| Тесты | `cms/tools/test-publication.php` — fixture-тесты pipeline без PHPUnit |

---

## Критические проблемы

### 1. Шаблоны CMS отстают от production

**Публикация через CMS сейчас деградирует сайт.** Шаблоны `cms/templates/project.php` и `cms/templates/catalog.php` существенно проще реальных HTML в `portfolio/`.

| Функция | Production (`portfolio/privateinterior/zhk-euro.html`) | CMS-шаблон |
|---------|--------------------------------------------------------|------------|
| Cover в intro | `style="--project-intro-image: url(...)"` | ❌ Нет |
| Мета-блок | 4 поля: Площадь, Локация, Год, Статус | ❌ Одна строка |
| Поле «Год» | Есть (2025) | ❌ Нет в БД |
| Before/After | Juxtapose-слайдер (`before.jpg` / `after.jpg`) | ❌ Отсутствует |
| OG / Twitter meta | Полный набор | ❌ Минимум |
| Навигация | Подменю «Услуги» (4 пункта) | ❌ Плоский список |
| Footer | Privacy, dev link, © 2026 | ❌ Упрощённый |
| `aria-label` | На секциях галереи и каталога | ❌ Нет |
| Каталог | `fetchpriority="high"` на первой карточке | ❌ Все `loading="lazy"` |

**Действие:** синхронизировать шаблоны с production **до первой публикации**.

### 2. Preview не отражает итоговую страницу

- `cms/admin/preview.php` — упрощённая галерея, не использует `render_template('project.php', ...)`.
- `cms/admin/page-preview.php` — только body без оболочки страницы.

### 3. Отсутствующие ассеты в репозитории

Шаблоны ссылаются на `skins/saparova/js/apps.js` — файл отсутствует (есть только `counter.js`).

### 4. Мёртвый код прямой публикации

`publish_projects()` и `publish_pages()` в `cms/app/publisher.php` пишут файлы **без staging и backup**. Админка их не вызывает, но риск случайного использования остаётся.

### 5. Первая публикация не удалит stale-файлы

`previous_project_paths()` возвращает пустой массив, если нет записей в `publication_revisions`. HTML старых проектов в `portfolio/privateinterior/` не будут удалены автоматически.

---

## Предложения по улучшению

### Приоритет 1 — блокеры перед production

#### 1.1. Синхронизировать шаблоны с production

- Взять эталон из `portfolio/privateinterior/zhk-euro.html` и `portfolio/index.htm`.
- Вынести header/footer в `cms/templates/partials/header.php`, `partials/footer.php`.
- Подключить partials в `project.php`, `catalog.php`.

#### 1.2. Расширить модель проекта

```sql
-- migration-003-project-fields.sql
ALTER TABLE projects
  ADD COLUMN year SMALLINT UNSIGNED NULL AFTER status,
  ADD COLUMN has_floorplan TINYINT(1) NOT NULL DEFAULT 0 AFTER year;
```

В `project_context()`:

- intro с CSS-переменной cover;
- структурированный `project-meta` (площадь, локация, год, статус);
- секция floorplan, если `has_floorplan = 1` и существуют `before.jpg` + `after.jpg`.

#### 1.3. Настоящий preview проектов

`preview.php` должен вызывать:

```php
echo render_template('project.php', project_context($project, $images, $config['app']['site_url']));
```

#### 1.4. Pre-publish checklist

На `publish.php` перед кнопкой «Опубликовать» показывать:

- количество файлов к записи / удалению;
- проекты без cover или с missing images;
- предупреждение при расхождении hash шаблона и текущего production (опционально).

---

### Приоритет 2 — UX редактора

#### 2.1. Управление изображениями

Текущие ограничения `project.php`:

- нельзя выбрать cover вручную;
- нельзя менять порядок;
- нельзя редактировать alt;
- alt при загрузке берётся из старого title.

**Нужно:** drag-and-drop сортировка, кнопка «Сделать обложкой», поле alt, индикатор before/after.

#### 2.2. Связать существующие экраны с UI

| Экран | Есть | Ссылка в UI |
|-------|------|-------------|
| `revisions.php` | ✅ | ❌ Нет в `project.php` |
| `preview.php` | ✅ | ❌ Нет в списке проектов |
| `page-preview.php` | ✅ | ❌ Нет в `page.php` |

#### 2.3. Редактор страниц

`body_html` — textarea с сырым HTML. Варианты:

- WYSIWYG с allowlist-тегами;
- блочный редактор (hero, FAQ, CTA);
- минимум: CodeMirror с подсветкой синтаксиса.

#### 2.4. Diff перед публикацией страниц

Показывать diff между текущим production HTML (между маркерами) и тем, что будет опубликовано.

---

### Приоритет 3 — надёжность и безопасность

#### 3.1. Rate limiting на login

Счётчик неудачных попыток (сессия или таблица `login_attempts`), блокировка на 15 минут после 5 попыток.

#### 3.2. Санитизация HTML

Allowlist для `body_html`: `p`, `h2`, `h3`, `a`, `img`, `section`, `ul`, `li`, `strong`, `em` и атрибуты `href`, `src`, `alt`, `class`.

#### 3.3. Удалить или deprecate legacy publish

Удалить `publish_projects()` / `publish_pages()` или пометить `@deprecated` и оставить только `publish_site()`.

#### 3.4. Смена slug

При смене slug:

- переименовать папку `portfolio/assets/projects/<old-slug>/` → `<new-slug>/`;
- обновить paths в `project_images`;
- пометить старый HTML на удаление при следующей публикации.

#### 3.5. Fallback для удаления stale-файлов

При отсутствии истории публикаций — сканировать `portfolio/privateinterior/*.html` и сравнивать с текущим списком опубликованных slug.

---

### Приоритет 4 — расширение охвата

#### 4.1. Страницы услуг в CMS

Добавить managed pages для `portfolio/service/*.html` или отдельный тип «service page» с маркерами.

#### 4.2. Оптимизация изображений при upload

- resize до max 2000px по длинной стороне;
- конвертация в WebP;
- генерация thumbnail для админки.

#### 4.3. Политика хранения backup

Автоочистка `storage/backups/` — хранить последние N публикаций или N дней (конфигурируемо).

#### 4.4. Требование PHP 8.5

`checks.php` требует PHP 8.5+. Рассмотреть понижение до 8.2+ если не используются фичи 8.5, либо явно документировать требование хостинга.

---

## Карта файлов

```
cms/
├── app/
│   ├── bootstrap.php       # сессия, PDO, CSRF, require_auth()
│   ├── publisher.php       # project_context(), catalog_context(), legacy publish_*
│   ├── publication.php     # safe publish + rollback ★
│   ├── template.php        # render_template(), write_staged_file()
│   └── checks.php          # run_cms_checks()
├── admin/
│   ├── index.php           # список проектов
│   ├── project.php         # CRUD проекта + upload изображений
│   ├── page.php            # CRUD страницы (body_html)
│   ├── publish.php         # триггер publish_site()
│   ├── publications.php    # история + rollback
│   ├── preview.php         # ⚠ упрощённый preview проекта
│   ├── page-preview.php    # preview body страницы
│   ├── revisions.php       # история правок проекта (не связана с UI)
│   ├── import.php          # импорт из project.md
│   └── import-pages.php    # импорт about/service/contact
├── templates/
│   ├── catalog.php         # ⚠ устарел vs production
│   ├── project.php         # ⚠ устарел vs production
│   └── page-shell.php      # OK — подстановка before/body/after
├── database/
│   ├── schema.sql
│   ├── migration-001-pages.sql
│   └── migration-002-publications.sql
├── config/
│   └── config.example.php
└── tools/
    ├── check.php
    ├── create-admin.php    # одноразовый, удалить с production
    └── test-publication.php
```

---

## Рекомендуемый порядок работ

| # | Задача | Effort | Impact |
|---|--------|--------|--------|
| 1 | Синхронизировать `project.php` + `catalog.php` с production | 1–2 дня | 🔴 Критично |
| 2 | Добавить `year`, floorplan, cover в intro (миграция + шаблон) | 0.5 дня | 🔴 Высокий |
| 3 | Preview через реальный шаблон | 0.5 дня | 🔴 Высокий |
| 4 | UI для изображений (cover, порядок, alt) | 1 день | 🟠 Высокий |
| 5 | Pre-publish checklist на `publish.php` | 0.5 дня | 🟠 Высокий |
| 6 | Связать revisions/preview в навигации админки | 2 часа | 🟡 Средний |
| 7 | Rate limit + HTML allowlist | 0.5 дня | 🟡 Средний |
| 8 | WYSIWYG / CodeMirror для страниц | 1–2 дня | 🟡 Средний |
| 9 | Страницы услуг в CMS | 1–2 дня | 🟢 Низкий |
| 10 | Оптимизация изображений при upload | 1 день | 🟢 Низкий |

---

## Связанные документы

- [cms/README.md](../cms/README.md) — операционная документация (локальный запуск, публикация, rollback)
- [docs/superpowers/specs/2026-08-09-cms-safe-publishing-design.md](superpowers/specs/2026-08-09-cms-safe-publishing-design.md) — дизайн safe publishing
- [CHANGELOG.md](../CHANGELOG.md) — история изменений CMS

---

## Итог

CMS имеет **зрелый pipeline публикации** (staging, backup, rollback, locks) — редкость для flat-file CMS на PHP без фреймворка.

Главный риск — **разрыв между шаблонами и production**: первая публикация заменит отработанные страницы упрощёнными. Первый обязательный шаг — выровнять шаблоны, затем улучшать UX редактора и расширять охват CMS.
