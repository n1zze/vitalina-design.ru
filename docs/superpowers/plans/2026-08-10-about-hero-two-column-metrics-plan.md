# About Hero Two-Column Metrics Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans (recommended). Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Refine `portfolio/about.htm` into a reference-like two-column author hero followed by a four-metric horizontal proof row, without a hero CTA.

**Architecture:** Keep the existing hero markup and portrait/content split, remove only the hero CTA, and replace the two-item stats grid with four existing-supported metrics. Add late scoped CSS in `main.css` for the four-column desktop row and two-column mobile row. No JavaScript or dependency changes.

**Tech Stack:** Static HTML, existing CSS, Manrope, existing stats/reveal behavior, Playwright 1.62.1, Impeccable detector.

## Global Constraints

- Preserve the current portrait, author name, bio, existing stats animation hooks, and section order except for removing the hero CTA and replacing the stats structure.
- Use exactly four metrics: `15+` / `лет опыта`, `120+` / `реализованных проектов`, `31` / `помещение`, `3300 м²` / `площади`.
- Do not invent or alter the four metric values or labels.
- Do not add a hero button or replace it with another CTA.
- Do not change the about proof section, other pages, JavaScript, or dependencies.
- Keep Manrope, warm palette, thin rules, and editorial typography.
- Keep no horizontal overflow at 1440px, 768px, or 390px.
- Reduced-motion removes transforms and delays.

---

### Task 1: Update About Hero Markup

**Files:**
- Modify: `portfolio/about.htm:115-147`

**Interfaces:**
- Consumes: Existing hero split, `.hero-stats`, `.hero-stats__item`, and reveal hooks.
- Produces: Hero without CTA and four exact metric items.

- [ ] **Step 1: Remove only the hero CTA**

  Remove the `.hero-cta` anchor inside the about hero. Preserve the same `contact.html` CTA elsewhere on the page and all other links.

- [ ] **Step 2: Replace the two stats items with four**

  Keep `.hero-stats`, `role="list"`, and the accessible `aria-label`. Replace the existing two items/divider with four `.hero-stats__item` elements:

  ```html
  <div class="hero-stats__item" role="listitem">
    <span class="hero-stats__number" data-max="15" data-suffix="+">15+</span>
    <span class="hero-stats__label">Лет опыта</span>
  </div>
  <div class="hero-stats__item" role="listitem">
    <span class="hero-stats__number" data-max="120" data-suffix="+">120+</span>
    <span class="hero-stats__label">Реализованных проектов</span>
  </div>
  <div class="hero-stats__item" role="listitem">
    <span class="hero-stats__number" data-max="31">31</span>
    <span class="hero-stats__label">Помещение</span>
  </div>
  <div class="hero-stats__item" role="listitem">
    <span class="hero-stats__number" data-max="3300">3300 м²</span>
    <span class="hero-stats__label">Площади</span>
  </div>
  ```

  Do not add a `.hero-stats__divider`; the four-column row uses CSS sibling borders.

- [ ] **Step 3: Verify markup invariants**

  Assert one about hero, zero hero CTAs inside `.hero-section`, exactly four `.hero-stats__item`, exact number/label text pairs, and the existing contact CTA elsewhere remains present.

---

### Task 2: Style Two-Column Hero And Four Metrics

**Files:**
- Modify: `skins/saparova/css/main.css` after existing about normalization overrides

**Interfaces:**
- Consumes: Existing hero classes and four-item stats markup from Task 1.
- Produces: Reference-like two-column desktop hero and balanced metrics row.

- [ ] **Step 1: Tighten hero spacing without changing content**

  Reduce about-only hero section padding and internal gaps by approximately 15-20% on desktop. Keep portrait/content split and align both columns vertically. Keep the author name dominant and the bio readable.

- [ ] **Step 2: Define four-column desktop metrics**

  Override `.hero-stats` for four equal columns:

  ```css
  .hero-stats {
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 0;
  }

  .hero-stats__item + .hero-stats__item {
    border-left: 1px solid #d4c5b5;
  }
  ```

  Keep numbers terracotta and labels muted, but reduce their scale enough that the author name remains the first visual focus.

- [ ] **Step 3: Define two-column mobile metrics**

  At `max-width: 767px`, use `grid-template-columns: repeat(2, minmax(0, 1fr))`, add internal row/column rules without double borders, and keep labels wrapping naturally. Preserve portrait -> copy -> stats order.

- [ ] **Step 4: Preserve reveal/reduced-motion**

  Keep `data-max` animation hooks. Ensure the stats row is visible in reduced-motion mode and no transform/delay causes content clipping.

---

### Task 3: Verify Rhythm And Regression Safety

**Files:**
- Test: ephemeral Node/Playwright script; no production test file

**Interfaces:**
- Consumes: `about.htm`, existing proof section, and service card markup.
- Produces: Evidence for exact metrics, no CTA, hero geometry, and protected content.

- [ ] **Step 1: Verify hero content**

  At 1440px, 768px, and 390px assert one hero, zero hero CTA anchors, exactly four metric items with exact values/labels, photo and content visible, and no overflow.

- [ ] **Step 2: Verify geometry**

  Assert desktop hero uses two visible columns; metric row has four columns at 1440px and two at 390px; metric separators do not create horizontal overflow; hero height is reduced from the previous baseline without hiding content.

- [ ] **Step 3: Verify protected content**

  Assert proof section remains after hero, existing approach section follows proof, page contact CTA/form remains present, and `service.htm` card tuples remain unchanged.

- [ ] **Step 4: Run final checks**

  ```bash
  node C:\Users\nizze\.claude\skills\impeccable\scripts\detect.mjs --json portfolio/about.htm portfolio/service.htm
  git diff --check -- portfolio/about.htm portfolio/service.htm skins/saparova/css/main.css
  ```

  Confirm no JavaScript files, dependency manifests, service detail pages, or floating icon controls changed.
