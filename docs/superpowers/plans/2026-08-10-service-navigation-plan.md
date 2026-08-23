# Service Navigation Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Rework the service index and three service detail pages into one coherent visual system of three independent services, using equal sibling navigation rather than a sequential process.

**Architecture:** Add one shared sibling-navigation component as repeated static HTML in the four service pages, with page-specific active state. Add scoped CSS in `main.css` for the navigation and page rhythm; preserve existing detail-page content blocks and keep all service-card internals on `service.htm` unchanged. No JavaScript or dependency changes are required.

**Tech Stack:** Static HTML, existing CSS in `skins/saparova/css/main.css`, existing Manrope/Inter fonts, vanilla JavaScript already present on pages, Playwright 1.62.1, Impeccable detector.

## Global Constraints

- `portfolio/about.htm` remains the visual and tonal reference.
- The three services are independent: `Дизайн-проект`, `3D-визуализация`, and `Авторский надзор`.
- The navigation is not a process timeline and must not imply that one service is required before another.
- Preserve all existing service content and form behavior.
- Preserve all anchor IDs, navigation destinations, images, prices, project links, reveal classes, and inline scripts.
- Do not modify service-card internals on `portfolio/service.htm`.
- Do not add a new dependency or replace the existing static-page architecture.
- Use equal sibling options and highlight only the current service.
- Keep no horizontal overflow at 390px, 768px, or wide desktop widths.

---

### Task 1: Add Shared Sibling Navigation Markup

**Files:**
- Modify: `portfolio/service.htm:105-150`
- Modify: `portfolio/service/3d-visualization.html:93-101`
- Modify: `portfolio/service/author-supervision.html:93-101`
- Modify: `portfolio/service/interior-design.html:93-101`

**Interfaces:**
- Consumes: Existing service URLs and page-specific service identity.
- Produces: A repeated `.service-sibling-nav` with three equal links and one `.is-current` state per page.

- [ ] **Step 1: Add the index sibling navigation before the unchanged card grid**

  Insert this structure after the service intro and before the existing `<section>` containing `.service-wrapper`:

  ```html
  <nav class="service-sibling-nav" aria-label="Другие услуги">
    <a href="service/interior-design.html">Дизайн-проект</a>
    <a href="service/3d-visualization.html">3D-визуализация</a>
    <a href="service/author-supervision.html">Авторский надзор</a>
  </nav>
  ```

  Do not edit any `.service-photo`, `.service-desc`, `.service-title`, `.service-price`, `.service-btn`, image, or card link markup.

- [ ] **Step 2: Add the same sibling navigation to each detail hero**

  Place the navigation before the existing hero title content and use the correct relative links from `portfolio/service/`:

  ```html
  <nav class="service-sibling-nav" aria-label="Другие услуги">
    <a href="interior-design.html" class="is-current" aria-current="page">Дизайн-проект</a>
    <a href="3d-visualization.html">3D-визуализация</a>
    <a href="author-supervision.html">Авторский надзор</a>
  </nav>
  ```

  The current page receives `is-current` and `aria-current="page"`; the other two links do not. Apply the matching state to each detail file without changing the existing hero copy.

- [ ] **Step 3: Verify markup invariants before CSS changes**

  Run a Playwright DOM check for all four pages:

  ```js
  const nav = page.locator('.service-sibling-nav');
  if (await nav.count() !== 1) throw new Error(`${file}: sibling nav count`);
  if (await nav.locator('a').count() !== 3) throw new Error(`${file}: sibling link count`);
  if (await nav.locator('[aria-current="page"]').count() !== (file.endsWith('service.htm') ? 0 : 1)) {
    throw new Error(`${file}: active state count`);
  }
  ```

---

### Task 2: Style The Equal Service Navigation

**Files:**
- Modify: `skins/saparova/css/main.css` near the existing service-page overrides at the end of the file

**Interfaces:**
- Consumes: `.service-sibling-nav`, `.is-current`, `aria-current` from Task 1.
- Produces: Equal sibling navigation aligned to the `about.htm` container and responsive at mobile widths.

- [ ] **Step 1: Define the desktop navigation rhythm**

  Add scoped styles using the established palette:

  ```css
  .service-sibling-nav {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    max-width: 960px;
    margin: 0 auto;
    border-top: 1px solid #d4c5b5;
    border-bottom: 1px solid #d4c5b5;
  }

  .service-sibling-nav a {
    min-height: 68px;
    display: flex;
    align-items: center;
    padding: 16px 20px;
    border-right: 1px solid #d4c5b5;
    color: #6b6560;
    font-family: "Manrope", sans-serif;
    font-size: 13px;
    text-decoration: none;
    transition: color .2s ease, background-color .2s ease;
  }

  .service-sibling-nav a:last-child { border-right: 0; }
  .service-sibling-nav a:hover,
  .service-sibling-nav a:focus-visible,
  .service-sibling-nav a.is-current {
    color: #1a1714;
    background: #f8f5f0;
  }
  ```

  Keep the three options equal in width and visual weight. Do not add numbered progress indicators or next/previous labels.

- [ ] **Step 2: Add mobile reflow and touch targets**

  At `max-width: 767px`, use one column with 44px minimum interactive height, remove internal vertical borders, and retain the same DOM order. The active state must remain visible without hover.

- [ ] **Step 3: Place navigation in the page shells**

  Give the index navigation a deliberate gap between its intro and the unchanged cards. Give detail navigation a compact relationship to the hero without creating a second competing hero. Use the same left edge as the section markers and detail headings.

- [ ] **Step 4: Run alignment checks**

  At 1440px, 768px, and 390px, assert the three links have equal widths on desktop, all links are inside the viewport, and the active link has a computed non-default background or color difference.

---

### Task 3: Refine Detail Page Composition Around Independent Services

**Files:**
- Modify: `skins/saparova/css/main.css` service-detail overrides only
- Modify: `portfolio/service/3d-visualization.html` only if a page-shell hook is required
- Modify: `portfolio/service/author-supervision.html` only if a page-shell hook is required
- Modify: `portfolio/service/interior-design.html` only if a page-shell hook is required

**Interfaces:**
- Consumes: Existing `.service-hero`, `.scope-section`, `.pricing-section`, `.portfolio-section`, `.contact-section` and Task 2 navigation.
- Produces: A shared editorial shell with one text axis, varied section density, and independent-service framing.

- [ ] **Step 1: Establish one hero axis**

  Align `.service-sibling-nav`, `.service-page-marker`, `.service-hero__title`, and `.service-hero__subtitle` to the same content edge. Keep the existing service title, description, CTA, and hero order. Ensure the `h1` is no more than three lines at supported widths.

- [ ] **Step 2: Keep the current content journey intact**

  Preserve the order `hero -> scope -> pricing -> projects -> contact form`. Do not rewrite scope copy, pricing copy, project labels, form labels, or success/error messages. Use spacing and section contrast to create visual chapters rather than adding unrelated UI.

- [ ] **Step 3: Vary density without introducing cards**

  Keep scope as the existing numbered divider list, pricing as existing rows, projects as the existing image strip, and contact as the existing form. Use the section marker and heading relationship from `about.htm`; do not turn these blocks into a repetitive card grid.

- [ ] **Step 4: Confirm independent-service semantics**

  Verify that no visible text says `01 -> 02 -> 03`, `следующий этап`, `маршрут`, or otherwise suggests mandatory sequencing. The only active state is the current service in sibling navigation.

---

### Task 4: Preserve Service Cards And Verify All Pages

**Files:**
- Test: ephemeral Node/Playwright smoke script; do not add a production test file

**Interfaces:**
- Consumes: Four updated pages and the unchanged card markup.
- Produces: Evidence that visual changes did not alter content, links, forms, or responsive safety.

- [ ] **Step 1: Snapshot service-card data before and after styling**

  Extract from `portfolio/service.htm` the three card tuples:

  ```js
  [href, image.src, image.alt, title.textContent.trim(), price.textContent.trim()]
  ```

  Assert the current values remain:

  ```text
  service/3d-visualization.html | img/visual.jpg | 3D визуализация | 3D ВИЗУАЛИЗАЦИЯ | 1 000 р/м2
  service/interior-design.html | img/design.jpg | Дизайн-проект | ДИЗАЙН-ПРОЕКТ | 3 000 р/м2
  service/author-supervision.html | img/work.jpg | Авторский надзор | АВТОРСКИЙ НАДЗОР | от 30 000 р
  ```

- [ ] **Step 2: Verify detail-page structure**

  For every detail page, assert one sibling navigation, one hero `h1`, three `.scope-list__item`, three `.pricing-row`, one `#contact-form`, and the existing project links.

- [ ] **Step 3: Verify responsive and accessibility behavior**

  At 1440px, 768px, and 390px, assert no horizontal overflow, three desktop navigation options, one-column mobile navigation, one active `aria-current="page"` on each detail page, and visible `:focus-visible` styles for navigation links and the primary CTA.

- [ ] **Step 4: Run final mechanical checks**

  Run:

  ```bash
  node C:\Users\nizze\.claude\skills\impeccable\scripts\detect.mjs --json --scope layout portfolio/service.htm portfolio/service/3d-visualization.html portfolio/service/author-supervision.html portfolio/service/interior-design.html
  git diff --check -- portfolio/service.htm portfolio/service/3d-visualization.html portfolio/service/author-supervision.html portfolio/service/interior-design.html skins/saparova/css/main.css
  ```

  Review every detector result and distinguish existing functional audit risks from changes introduced by this visual implementation.
