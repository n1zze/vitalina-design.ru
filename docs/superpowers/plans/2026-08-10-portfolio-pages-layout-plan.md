# Portfolio Pages Layout Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Bring `contact.html`, `service.htm`, and the three service detail pages into the established visual rhythm of `about.htm` without changing content, navigation, forms, or service-card behavior.

**Architecture:** Preserve the existing HTML and stylesheet ownership. Add a small shared page-shell layer in `main.css`, scoped contact refinements in `contact.css`, and minimal marker hooks in the five target HTML files. The service index card selectors remain untouched; detail pages keep their existing numbered lists, pricing rows, project strips, and form scripts.

**Tech Stack:** Static HTML, CSS, existing Manrope/Inter web fonts, vanilla JavaScript, Playwright 1.62.1, Impeccable detector.

## Global Constraints

- `portfolio/about.htm` remains unchanged and is the visual reference.
- Preserve all current content, links, anchors, form fields, validation, reveal states, map, images, prices, and navigation destinations.
- Service cards in `portfolio/service.htm` are out of scope: do not change their HTML, images, prices, links, overlay, hover behavior, or functionality.
- Use Manrope, warm off-white `#f8f5f0`, white surfaces, ink `#1a1714`, muted text `#6b6560`, terracotta `#c99471`, and beige rules around `#d4c5b5`.
- Use bracketed uppercase section markers such as `[ Стоимость ]` with the established tracked typography.
- Keep DOM order, keyboard order, and responsive single-column order unchanged.
- Do not modify JavaScript behavior or introduce dependencies.

---

### Task 1: Add Shared Service-Page Rhythm Hooks

**Files:**
- Modify: `portfolio/service/3d-visualization.html:93-200`
- Modify: `portfolio/service/author-supervision.html:93-200`
- Modify: `portfolio/service/interior-design.html:93-200`
- Modify: `skins/saparova/css/main.css:7749-8619`

**Interfaces:**
- Consumes: Existing `.service-hero`, `.scope-section`, `.pricing-section`, `.portfolio-section`, `.contact-section`, and `.section-title` markup.
- Produces: Consistent `.service-page-marker` hooks and scoped service-detail spacing/typography rules used by all three pages.

- [ ] **Step 1: Add only marker hooks to each service detail page**

  Add a marker immediately before the hero `h1` using the existing service number and label meaning, for example:

  ```html
  <span class="service-page-marker reveal">[ Услуга 02 · 3D-визуализация ]</span>
  ```

  Add matching markers before each existing section title: `[ Что входит ]`, `[ Стоимость ]`, `[ Проекты ]`, and `[ Обсудим ваш проект ]`. Do not alter any existing text, IDs, links, form fields, or scripts.

- [ ] **Step 2: Define scoped detail-page marker and section rules**

  In `main.css`, scope rules to the detail-page structures and use the `about.htm` values:

  ```css
  .service-page-marker {
    display: block;
    margin-bottom: 16px;
    color: #a88e78;
    font-family: "Manrope", sans-serif;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .18em;
    line-height: 1.3;
    text-transform: uppercase;
  }
  ```

  Keep the centered service hero composition, but align its title width, subtitle measure, section padding, section-title weight, and CTA spacing with the warm editorial system. Use 80px desktop section padding and 48-64px mobile section padding without changing the existing content topology.

- [ ] **Step 3: Preserve all detail-page interaction selectors**

  Review the resulting selectors to ensure `.reveal`, `.pricing-row`, `.contact-form`, `[id]`, and existing responsive rules still apply. Do not remove or rename selectors used by `apps.js` or inline form scripts.

- [ ] **Step 4: Run a focused structural smoke check**

  Run a Node/Playwright check against the three local detail pages and assert:

  ```js
  for (const pagePath of detailPages) {
    await page.goto(fileUrl(pagePath));
    if (await page.locator('.service-hero h1').count() !== 1) throw new Error(`${pagePath}: hero h1 count`);
    if (await page.locator('.scope-list__item').count() !== 3) throw new Error(`${pagePath}: scope item count`);
    if (await page.locator('.pricing-row').count() !== 3) throw new Error(`${pagePath}: pricing row count`);
    if (await page.locator('#contact-form').count() !== 1) throw new Error(`${pagePath}: contact form section count`);
  }
  ```

  Also run `git diff --check`.

---

### Task 2: Align Contact Page Shell And Section Rhythm

**Files:**
- Modify: `portfolio/contact.html:92-250`
- Modify: `skins/saparova/css/contact.css:10-421`

**Interfaces:**
- Consumes: Existing contact page DOM, forms, map, contact rows, reveal classes, and `about.htm` marker language.
- Produces: Editorial contact intro and consistent contact section spacing without changing order or functionality.

- [ ] **Step 1: Add minimal marker hooks**

  Add `[ Контакты ]` to the page introduction and markers for the existing contact groups, map, and two project-contact sections. Keep all current headings and descriptions intact; markers are additional hierarchy labels, not replacement copy.

- [ ] **Step 2: Replace only the legacy contact shell styling**

  In `contact.css`, scope the page introduction to `.contact-page #category-section` and align it with `about.htm`: warm background, left-aligned shared container, light Manrope `h1`, constrained description measure, and 80px desktop / 48-64px mobile padding.

  Keep the current `contacts-container`, contact columns, consultation form, full-width map, project form, and footer order. Use spacing and rules instead of adding nested card decoration.

- [ ] **Step 3: Normalize contact block details**

  Preserve the current field structure and colors while aligning section titles, labels, row dividers, form padding, focus states, button shape, and responsive breakpoints with the established palette. Keep the map height behavior and all `iframe` attributes unchanged.

- [ ] **Step 4: Validate contact functionality and structure**

  Use Playwright to verify one page title, two contact columns at desktop, one contact column at 390px, one map iframe, exactly two forms with IDs `contactFormTop` and `contactForm`, all `required` fields, and unchanged form IDs. Run `git diff --check`.

---

### Task 3: Align Service Index Heading Without Touching Cards

**Files:**
- Modify: `portfolio/service.htm:105-150`
- Modify: `skins/saparova/css/main.css:4913-4935` or add a later scoped service-index override near the service rules

**Interfaces:**
- Consumes: Existing category heading and unchanged `.service-photo` card grid.
- Produces: Editorial service-index intro and matching surrounding spacing.

- [ ] **Step 1: Add the page marker only**

  Add `[ Услуги ]` to the heading shell before the existing `Услуги` `h1`. Keep the existing `category-title`, card order, image sources, prices, links, and card markup byte-for-byte unchanged outside the marker insertion.

- [ ] **Step 2: Scope heading and surrounding spacing**

  Add a page-specific selector for the service index heading and adjust only the title shell, `#category-section` spacing, and `.service-wrapper` outer spacing. Do not edit `.service-photo`, `.service-desc`, `.service-title`, `.service-price`, `.service-btn`, or their hover rules.

- [ ] **Step 3: Prove the cards are unchanged**

  Before and after the style edit, extract the three card anchors' `href`, image `src`, alt text, price text, and title text with Playwright and compare the arrays. Verify all three cards remain present at 1440px and 390px.

---

### Task 4: Cross-Page Responsive And Accessibility Verification

**Files:**
- Modify: only files identified by the preceding tasks if verification exposes a scoped defect
- Test: an ephemeral Node/Playwright smoke script run from the repository root; do not add a production dependency or alter page scripts

**Interfaces:**
- Consumes: All five updated pages and the unchanged `about.htm` reference.
- Produces: Verified layout metrics, no overflow, preserved interactive structure, and detector-clean changed targets.

- [ ] **Step 1: Inspect all required viewport states**

  Use Playwright at 1440px, 768px, and 390px for:

  ```text
  portfolio/contact.html
  portfolio/service.htm
  portfolio/service/3d-visualization.html
  portfolio/service/author-supervision.html
  portfolio/service/interior-design.html
  portfolio/about.htm
  ```

  Capture screenshots or DOM measurements sufficient to confirm heading hierarchy, marker alignment, section cadence, and card preservation.

- [ ] **Step 2: Check overflow and interaction order**

  For every target page, assert `document.documentElement.scrollWidth <= window.innerWidth`, visible focus outlines exist for representative links/buttons, and the DOM order of headings, forms, map, and service content matches the intended reading order.

- [ ] **Step 3: Run final mechanical checks**

  Run:

  ```bash
  node C:\Users\nizze\.claude\skills\impeccable\scripts\detect.mjs --json --scope layout portfolio/contact.html portfolio/service.htm portfolio/service/3d-visualization.html portfolio/service/author-supervision.html portfolio/service/interior-design.html
  git diff --check
  ```

  Review every detector result; do not suppress a finding without documenting why the existing design constraint makes it intentional.

- [ ] **Step 4: Confirm protected files and behavior**

  Use `git diff -- portfolio/about.htm` to confirm the reference file is unchanged. Compare service-card content and all form IDs/anchors against the pre-edit DOM snapshots. Confirm no JavaScript files or dependency manifests changed.
