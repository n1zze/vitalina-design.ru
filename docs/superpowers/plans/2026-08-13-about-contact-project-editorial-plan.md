# About Contact Project Editorial Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Apply one warm editorial visual system to the about hero, contact split form, project detail introductions, and the approved page-quality fixes.

**Architecture:** Keep the existing static HTML routes and vanilla JavaScript behavior. Use scoped CSS in the existing `main.css`, `contact.css`, and `portfolio/assets/projects.css`; change project detail pages through the shared project stylesheet rather than editing every generated page. Use `portfolio/hero/hero-photo.png` for the contact image and preserve all existing form contracts.

**Tech Stack:** Static HTML, vanilla JavaScript, CSS, existing Manrope/Proxima font setup, Playwright, Impeccable detector.

## Global Constraints

- Use the existing Manrope family for display and body hierarchy.
- Keep the warm neutral palette, terracotta accent, thin separators, and restrained corner radius already used by the portfolio.
- Use `80px` section rhythm on desktop and `64px` on mobile where the page's existing responsive rules establish those values.
- Use a maximum content width of `1400px` for broad editorial blocks.
- Keep mobile layouts single-column with no horizontal overflow.
- Avoid new dependencies and avoid introducing a second visual system.
- Preserve current routes, form endpoints, hidden fields, honeypots, IDs, names, phone masks, validation, success states, and project galleries.
- Do not invent project numbers, years, statuses, locations, or metrics.

---

### Task 1: Rebuild the About Hero Composition

**Files:**
- Modify: `portfolio/about.htm:115-131`
- Modify: `skins/saparova/css/main.css:6722-6949` and about hero overrides
- Test: Playwright layout assertions in Task 5

**Interfaces:**
- Consumes: existing `.hero-section`, `.hero-photo`, `.hero-content`, author copy, and current CTA/link behavior.
- Produces: desktop image-left/text-right editorial hero and explicit mobile stack without changing the page route.

- [ ] **Step 1: Make the hero copy match the approved editorial hierarchy**

Keep the author name and factual copy. Ensure the hero structure contains one eyebrow/label, one dominant `h1`, the lead and bio, and one clear link CTA. Keep the current destination for the about CTA. Correct the portrait alt text to:

```html
alt="Виталина Ромашкевич — дизайнер интерьеров"
```

- [ ] **Step 2: Set the desktop image/text split**

Use the existing asset and make the text column carry the reference's top rule and reading order:

```css
.hero-wrapper {
  display: grid;
  grid-template-columns: minmax(280px, .72fr) minmax(0, 1.28fr);
  gap: clamp(40px, 7vw, 96px);
  align-items: center;
}

.hero-photo {
  aspect-ratio: 4 / 5;
  max-height: none;
}

.hero-photo img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.hero-content {
  padding-top: 28px;
  border-top: 1px solid #e8e4df;
}
```

Preserve the established neutral background, typography family, and current reveal classes.

- [ ] **Step 3: Tune hero type and mobile collapse**

Use the page's existing display scale: `48px` name on desktop, `32px` on mobile, with readable lead/bio measure. At `max-width: 767px`, stack the image above the content, remove the desktop grid gap, and keep the CTA visible without horizontal overflow.

- [ ] **Step 4: Verify the about hero before moving on**

Run a Playwright check at `1440px` and `390px` asserting:

```js
expect(await page.locator('.hero-photo').boundingBox()).not.toBeNull();
expect(await page.locator('.hero-content__name').boundingBox()).not.toBeNull();
expect(await page.evaluate(() => document.documentElement.scrollWidth <= document.documentElement.clientWidth)).toBe(true);
```

### Task 2: Convert Contact Page to the Approved Split Block

**Files:**
- Modify: `portfolio/contact.html:153-213`
- Modify: `skins/saparova/css/contact.css:124-298`
- Modify: `skins/saparova/css/main.css:1888-1925` only if a scoped legacy input override is required
- Test: Form contract and responsive assertions in Task 5

**Interfaces:**
- Consumes: existing `#contactForm`, current field IDs/names, FormSubmit action, validation script, and `portfolio/hero/hero-photo.png`.
- Produces: inline image-left/form-right split block with the same form behavior and accessible visible labels.

- [ ] **Step 1: Replace only the contact form image source and preserve its alternative text**

Change the media image to:

```html
<img src="hero/hero-photo.png" alt="Виталина Ромашкевич — дизайнер интерьеров" loading="lazy">
```

The path is relative to `portfolio/contact.html`; do not change form action or hidden fields.

- [ ] **Step 2: Define the reference composition**

Use the existing `.contact-form-section` and `.contact-form-grid` classes. Make the outer block broad and split approximately `40% / 60%`, with the image filling the left panel and the form using a white right panel:

```css
.contacts-container {
  max-width: 1400px;
}

.contact-form-section {
  margin: 80px 0;
  border: 0;
  border-radius: 0;
}

.contact-form-grid {
  grid-template-columns: minmax(300px, .78fr) minmax(0, 1.22fr);
  min-height: 720px;
}

.contact-form-grid__body {
  padding: clamp(48px, 6vw, 88px);
}
```

Keep the image object-fit cover and do not add modal close behavior.

- [ ] **Step 3: Align the form controls to the reference**

Keep labels above controls and use the existing two-column `.contact-form__row` for name/phone. Keep object type/location as a two-column row, area full width, and CTA full width. Use the existing `contact-form__input`, `contact-form__label`, `contact-form__error`, `contact-form__checkbox`, and `contact-form__submit` styles rather than creating a second form language.

- [ ] **Step 4: Define mobile behavior and preserve states**

At `max-width: 767px`, use one column, reduce the media height to a stable `min-height: 360px`, set the body padding to `32px 20px 40px`, keep all input rows one column, and ensure consent/error/success content wraps inside the viewport. Preserve `required` fields and existing JS event listeners.

- [ ] **Step 5: Verify contact form behavior before moving on**

Use Playwright to assert the image source, action, field names, button label, visible labels, no overflow, and phone mask:

```js
await expect(page.locator('.contact-form-grid__media img')).toHaveAttribute('src', 'hero/hero-photo.png');
await expect(page.locator('#contactForm')).toHaveAttribute('action', /formsubmit\.co/);
await expect(page.locator('#cf-name')).toHaveAttribute('name', 'name');
await expect(page.locator('#cf-phone')).toHaveAttribute('name', 'phone');
await page.locator('#cf-phone').fill('79033475152');
await expect(page.locator('#cf-phone')).toHaveValue('+7 (903) 347-51-52');
```

### Task 3: Restyle Shared Project Detail Introductions

**Files:**
- Modify: `portfolio/assets/projects.css:2-83` and responsive rules at `151-198`
- Test: `portfolio/privateinterior/zhk-vse-svoi.html` and `portfolio/privateinterior/private-house-krd.html` in Task 5

**Interfaces:**
- Consumes: existing `.projects-page__intro`, `.projects-page__eyebrow`, `.projects-page__title`, `.project-meta`, and `.project-gallery` markup/data.
- Produces: left-aligned editorial project intro without changing gallery links or project facts.

- [ ] **Step 1: Set the intro container and left alignment**

Replace the centered intro treatment with a broad left-aligned block:

```css
.projects-page__intro {
  max-width: 1400px;
  padding: 96px 25px 112px;
  text-align: left;
}

.projects-page__eyebrow {
  margin-bottom: 18px;
}

.projects-page__title {
  max-width: 760px;
  margin: 0 0 56px;
  font-size: clamp(36px, 6vw, 72px);
  line-height: 1.02;
  letter-spacing: -.04em;
}
```

Do not add a fake project index or unsupported metadata.

- [ ] **Step 2: Turn project metadata into the reference-style grid**

Keep the existing metadata spans and make them readable as a two-column grid with generous row spacing:

```css
.project-meta {
  display: grid;
  grid-template-columns: repeat(2, minmax(180px, 280px));
  justify-content: start;
  gap: 28px 72px;
  margin-top: 0;
  text-align: left;
}
```

If a page currently has only three metadata values, retain three values and leave no blank placeholder cell.

- [ ] **Step 3: Increase the gallery separation and collapse on mobile**

Keep the gallery as the dominant next section. Use the intro bottom padding to create the reference whitespace, and at `max-width: 767px` use `padding: 64px 18px 72px`, one-column metadata, `gap: 18px`, and a title scale that fits within two or three lines.

### Task 4: Apply Page-Wide Content and UX Corrections

**Files:**
- Modify: `portfolio/about.htm:136-157,385,519`
- Modify: `portfolio/contact.html:251`
- Modify: `skins/saparova/css/main.css` only for narrowly scoped legacy form rules if required by Task 2

**Interfaces:**
- Consumes: existing visible strings, review modal script, footer markup, and metrics data.
- Produces: corrected copy, non-empty modal image fallback, and current copyright year without changing navigation or form behavior.

- [ ] **Step 1: Correct stale about content**

Change the about portrait alt text to `Виталина Ромашкевич — дизайнер интерьеров`. Change metric labels to `Помещений` and `Общая площадь` only if the existing `3300` value represents total area; otherwise retain the factual value and use the most accurate existing label.

- [ ] **Step 2: Remove the empty review image state**

Set the modal image to an existing real image as its initial source while preserving the runtime replacement:

```html
<img class="review-modal__image" data-review-image-view src="assets/projects/zhk-vse-svoi/cover.jpg" alt="Фото проекта">
```

Keep `openReview(image)` assigning the clicked review image and keep the existing error fallback.

- [ ] **Step 3: Update footer year consistently**

Change the visible footer year in about/contact pages to `2026`, preserving the existing footer structure and links.

### Task 5: Full Multi-Surface Verification

**Files:**
- Inspect: `portfolio/about.htm`
- Inspect: `portfolio/contact.html`
- Inspect: `portfolio/privateinterior/zhk-vse-svoi.html`
- Inspect: `portfolio/privateinterior/private-house-krd.html`
- Inspect: `skins/saparova/css/main.css`
- Inspect: `skins/saparova/css/contact.css`
- Inspect: `portfolio/assets/projects.css`

- [ ] **Step 1: Render all target surfaces at desktop and mobile sizes**

Start the existing static server and capture target elements at `1440x900`, `991x900`, `767x900`, and `390x844`. Hide only the existing page-loading mask in the test harness after page load so screenshots show the actual surface.

- [ ] **Step 2: Assert layout contracts**

For each target page verify:

```js
expect(await page.evaluate(() => document.documentElement.scrollWidth <= document.documentElement.clientWidth)).toBe(true);
expect(await page.locator('h1, h2').count()).toBeGreaterThan(0);
```

Additionally verify about hero image/text order, contact image/form order, project intro left alignment, and footer gap values `80px` desktop / `64px` mobile.

- [ ] **Step 3: Run accessibility and visual detector checks**

Run:

```powershell
node "C:\Users\nizze\.claude\skills\impeccable\scripts\detect.mjs" --json "portfolio/about.htm" "portfolio/contact.html" "portfolio/assets/projects.css" "skins/saparova/css/main.css" "skins/saparova/css/contact.css"
```

Review only new findings in the changed surfaces; existing unrelated legacy warnings must be documented rather than broadly refactored.

- [ ] **Step 4: Review final diff boundaries**

Run:

```powershell
git diff --check
git diff -- portfolio/about.htm portfolio/contact.html portfolio/assets/projects.css skins/saparova/css/main.css skins/saparova/css/contact.css
```

Expected: only the approved about/contact/project editorial changes and page-quality corrections are present; existing unrelated worktree changes remain untouched.
