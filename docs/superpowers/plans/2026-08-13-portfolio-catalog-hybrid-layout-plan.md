# Portfolio Catalog Hybrid Layout Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Refine the portfolio catalog into an Aleart-inspired editorial CSS Grid and make project floorplan comparisons full-width while keeping shared header and project behavior consistent.

**Architecture:** Add a catalog-specific modifier to `portfolio/index.htm` and scope catalog-only rules so project detail background heroes remain unaffected. Keep the shared `projects.css` as the single source for catalog/detail layout tokens, and adjust floorplan layout there without changing Juxtapose markup or project data.

**Tech Stack:** Static HTML, CSS, existing vanilla JavaScript, Juxtapose, Playwright, Impeccable detector.

## Global Constraints

- Do not use masonry layout.
- Full-bleed surfaces: project detail hero, project gallery imagery, floorplan comparison, contact split image/form.
- Wide editorial container: `max-width: 1400px`.
- Reading container: `max-width: 620-720px`.
- Shared header uses Manrope with identical logo scale, nav font sizing, line height, and gaps across about, service, catalog, and project detail.
- Preserve current warm neutral palette, terracotta accent, thin rules, and restrained motion.
- Do not change project data, image sources, routes, or gallery behavior.
- Do not add dependencies.
- Preserve responsive behavior at `1440px`, `991px`, `767px`, and `390px`.

---

### Task 1: Scope the Catalog Page

**Files:**
- Modify: `portfolio/index.htm:29,59-64`
- Test: catalog DOM assertions in Task 4

**Interfaces:**
- Consumes: existing catalog page header, intro, project card markup, and linked project routes.
- Produces: `.projects-catalog-page` hook that separates catalog behavior from detail pages.

- [ ] **Step 1: Add the catalog modifier**

Change the main element to:

```html
<main class="projects-page projects-catalog-page">
```

Keep the existing intro copy and project cards unchanged.

- [ ] **Step 2: Normalize the catalog font import**

Remove the catalog-only `Inter` family from the Google Fonts URL and retain only the existing Manrope family used by the shared header and portfolio surfaces.

- [ ] **Step 3: Verify the catalog hook and links**

Use Playwright to assert `.projects-catalog-page` exists, the intro contains no inline/background image, and all nine `.project-card` links still resolve to their original href values.

### Task 2: Implement Editorial Catalog Grid

**Files:**
- Modify: `portfolio/assets/projects.css:14-29,142-202,204-269`
- Test: catalog screenshots and computed layout in Task 4

**Interfaces:**
- Consumes: `.projects-catalog-page`, `.projects-catalog__grid`, and nine existing `.project-card` items.
- Produces: predictable CSS Grid catalog with one featured card and no CSS columns/masonry.

- [ ] **Step 1: Remove detail hero treatment from catalog intro**

Scope a compact catalog intro without background image, overlay, or detail-page min-height:

```css
.projects-catalog-page .projects-page__intro {
  min-height: 0;
  max-width: 1400px;
  margin: 0 auto;
  padding: 88px 25px 64px;
  background: #fff;
  color: #1a1714;
}

.projects-catalog-page .projects-page__title {
  max-width: 620px;
  margin-bottom: 18px;
  color: #1a1714;
  font-size: 56px;
}

.projects-catalog-page .projects-page__eyebrow {
  color: #a88e78;
}

.projects-catalog-page .projects-page__description {
  max-width: 620px;
  margin: 0;
  color: #6b6560;
}
```

- [ ] **Step 2: Replace equal-column grid with editorial CSS Grid**

Use a grid with an intentional featured first card:

```css
.projects-catalog-page .projects-catalog {
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 25px 100px;
}

.projects-catalog-page .projects-catalog__grid {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 56px 16px;
}

.projects-catalog-page .project-card:first-child {
  grid-column: span 2;
}

.projects-catalog-page .project-card:first-child .project-card__image {
  aspect-ratio: 16 / 10;
}

.projects-catalog-page .project-card:not(:first-child) .project-card__image {
  aspect-ratio: 4 / 3;
}
```

No empty grid cells, `columns`, `column-count`, or CSS masonry rules may remain in catalog scope.

- [ ] **Step 3: Add responsive collapse**

At `max-width: 767px`, use one column, make the first card span one column, reduce intro/catalog padding, and use one consistent image ratio. At `max-width: 420px`, keep one column and reduce card gaps only if needed to preserve the established mobile rhythm.

- [ ] **Step 4: Normalize catalog card typography**

Ensure `.project-card__title`, `.project-card__meta`, and header links use Manrope and the existing warm ink/muted colors. Keep image hover scale and focus outline; do not add shadows or card backgrounds.

### Task 3: Make Project Floorplan Full-Width

**Files:**
- Modify: `portfolio/assets/projects.css:271-348`
- Test: representative project detail pages in Task 4

**Interfaces:**
- Consumes: existing `.project-floorplan`, `.project-floorplan__title`, `.project-floorplan__subtitle`, `.project-floorplan__slider`, and Juxtapose DOM.
- Produces: left-aligned heading block and full-width comparison image without changing before/after behavior.

- [ ] **Step 1: Make the floorplan section full-bleed**

Replace the centered narrow section rules with:

```css
.project-floorplan {
  max-width: none;
  margin: 0;
  padding: 0 0 80px;
  text-align: left;
}

.project-floorplan__title,
.project-floorplan__subtitle {
  max-width: 1400px;
  margin-right: auto;
  margin-left: auto;
  padding-right: 25px;
  padding-left: 25px;
}

.project-floorplan__title {
  margin-bottom: 12px;
  font-family: "Manrope", sans-serif;
  font-size: 36px;
  font-weight: 200;
  line-height: 1.15;
  letter-spacing: -.02em;
}

.project-floorplan__slider {
  max-width: none;
  margin: 40px 0 0;
}

.project-floorplan__slider .juxtapose {
  width: 100%;
}
```

- [ ] **Step 2: Define mobile floorplan spacing**

At `max-width: 767px`, use `padding-bottom: 64px`, heading font `28px`, horizontal heading padding `18px`, and reduce slider top margin to `28px`. Keep the comparison touch-friendly and full available width.

- [ ] **Step 3: Verify existing Juxtapose structure**

Do not alter the two source image elements, `data-startingposition`, labels, or the Juxtapose library script. Verify both images remain present and the slider width equals the viewport content width.

### Task 4: Verify Catalog, Header, And Detail Rhythm

**Files:**
- Inspect: `portfolio/index.htm`
- Inspect: `portfolio/assets/projects.css`
- Inspect: `portfolio/privateinterior/zhk-nebo.html`
- Inspect: `portfolio/privateinterior/zhk-vse-svoi.html`
- Inspect: shared header in `skins/saparova/css/main.css`

- [ ] **Step 1: Render target pages at all required viewports**

Run the local static server and render catalog plus two detail pages at `1440x900`, `991x900`, `767x900`, and `390x844`. Hide only the page-loading mask in the test harness after load.

- [ ] **Step 2: Assert catalog layout contracts**

For catalog, assert:

```js
await expect(page.locator('.projects-catalog-page')).toBeVisible();
await expect(page.locator('.projects-page__intro')).toHaveCSS('background-image', 'none');
await expect(page.locator('.projects-catalog__grid')).toHaveCSS('display', 'grid');
await expect(page.locator('.project-card')).toHaveCount(9);
```

Also assert document `scrollWidth <= clientWidth`.

- [ ] **Step 3: Assert shared header and floorplan contracts**

Compare computed `font-family`, `font-size`, and `line-height` for `#navigation a` on about, service, catalog, and project detail. Verify `.project-floorplan` is full-width, heading text is left-aligned, and the Juxtapose element contains two images.

- [ ] **Step 4: Run detector and final diff checks**

Run:

```powershell
node "C:\Users\nizze\.claude\skills\impeccable\scripts\detect.mjs" --json "portfolio/index.htm" "portfolio/assets/projects.css" "skins/saparova/css/main.css"
git diff --check
```

Review only new findings in catalog and floorplan scope; leave unrelated legacy worktree changes untouched.
