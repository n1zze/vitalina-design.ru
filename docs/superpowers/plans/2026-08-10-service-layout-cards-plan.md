# Service Layout And Cards Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Align service section widths with `about.htm` and replace the service index card presentation with the supplied Inspaire-inspired image-overlay composition and bounded motion.

**Architecture:** Keep the existing static HTML and service-card content. Move only the service-index sibling navigation into the shared container, then add late scoped CSS overrides in `main.css` for the broad section axis, portrait card grid, image overlay, arrow affordance, and reduced-motion behavior. No JavaScript or dependency changes are needed.

**Tech Stack:** Static HTML, existing CSS, Manrope/Font Awesome already loaded by the site, vanilla browser interactions, Playwright 1.62.1, Impeccable detector.

## Global Constraints

- `portfolio/about.htm` is the layout reference and remains unchanged.
- Markers, page headings, section headings, and sibling service navigation align to the full shared `.container` axis.
- Supporting paragraphs may remain constrained to approximately 620px for readable line length, but their left edge follows the section axis.
- Preserve service-card image source/alt, title, price, link, and visible `Подробнее` label.
- Do not change service-card content, image paths, names, prices, links, or the `Подробнее` text.
- Do not add dependencies or alter JavaScript behavior.
- Preserve accessible link semantics, focus states, and touch targets.
- `@media (prefers-reduced-motion: reduce)` disables transforms and uses instant state changes.
- Keep no horizontal overflow at 390px, 768px, or wide desktop widths.

---

### Task 1: Correct Service Page Content Axis

**Files:**
- Modify: `portfolio/service.htm:115-122`
- Modify: `skins/saparova/css/main.css:10137-10356`

**Interfaces:**
- Consumes: Existing `.container`, `.service-sibling-nav`, `.service-page-marker`, `.service-hero__title`, `.section-title`, `.scope-list`, `.pricing-strip`, and `.portfolio-strip`.
- Produces: Shared wide alignment matching `about.htm` without changing page order or service content.

- [ ] **Step 1: Put the index sibling nav inside the shared container**

  Change only the wrapper around the existing nav so the index uses the same horizontal axis as its category heading and cards:

  ```html
  <section>
    <div class="container">
      <nav class="service-sibling-nav" aria-label="Другие услуги">
        ...existing three links...
      </nav>
    </div>
    <div class="service-wrapper container">
      ...existing unchanged cards...
    </div>
  </section>
  ```

  Do not modify any `.service-photo` descendant or card link.

- [ ] **Step 2: Remove the artificial 760px cap from service headings and markers**

  Add late scoped overrides so these elements use the full container width:

  ```css
  .service-hero .service-page-marker,
  .service-hero__title,
  .scope-section .service-page-marker,
  .pricing-section .service-page-marker,
  .portfolio-section .service-page-marker,
  .contact-section .service-page-marker,
  .scope-section .section-title,
  .pricing-section .section-title,
  .portfolio-section .section-title,
  .contact-section .section-title {
    max-width: none;
    width: 100%;
  }
  ```

  Keep `.service-hero__subtitle` at `max-width: 620px`, left-aligned to the same container edge. Set `.service-sibling-nav` to `width: 100%; max-width: none` within its container.

- [ ] **Step 3: Widen repeated content blocks without changing their topology**

  Set `.scope-list` and `.pricing-strip` to `max-width: none; width: 100%`, retaining their existing list/row structures. Keep the contact form’s readable narrow field width. Ensure the project strip remains full container width.

- [ ] **Step 4: Verify axis geometry**

  Use Playwright at 1440, 768, and 390 for `service.htm` and one detail page. Assert the left edge of marker, section heading, and container is equal within 1px; assert no overflow.

---

### Task 2: Implement Inspaire-Inspired Service Cards

**Files:**
- Modify: `skins/saparova/css/main.css:6480-6647` via late scoped overrides

**Interfaces:**
- Consumes: Existing three `.service-photo.service-photo--link` anchors and their existing descendants.
- Produces: Three portrait image-overlay cards with preserved content and navigation.

- [ ] **Step 1: Convert the card row into a stable desktop grid**

  Add a late override for `.service-wrapper .row`:

  ```css
  .service-wrapper .row {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 30px;
    margin: 0;
  }

  .service-wrapper .row > .service-photo {
    width: auto;
    min-width: 0;
    padding: 0;
  }
  ```

  Use a portrait `aspect-ratio` around `3 / 4` and `overflow: hidden` on `.service-photo`. At `max-width: 767px`, switch to `grid-template-columns: 1fr` and keep the portrait ratio.

- [ ] **Step 2: Build the image and overlay composition**

  Preserve image `src` and use:

  ```css
  .service-photo img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: none;
    transition: transform .55s cubic-bezier(.2,.65,.2,1);
  }

  .service-photo::before {
    content: "";
    position: absolute;
    inset: 0;
    z-index: 1;
    background: linear-gradient(180deg, transparent 35%, rgba(19,16,13,.82) 100%);
    transition: background .45s ease;
  }
  ```

  The lower `.service-desc` must be positioned at the bottom-left with readable padding, `align-items: flex-start`, and no centering transform. Preserve the existing title, price, and `Подробнее` span.

- [ ] **Step 3: Add the reference-style arrow affordance**

  Use the existing Font Awesome font already loaded by the pages for a circular arrow pseudo-element, avoiding new markup and preserving the card text:

  ```css
  .service-photo--link::after {
    content: "\\f061";
    position: absolute;
    top: 28px;
    right: 28px;
    z-index: 3;
    width: 58px;
    height: 58px;
    display: grid;
    place-items: center;
    border-radius: 50%;
    background: rgba(151,163,157,.86);
    color: #fff;
    font-family: "FontAwesome";
    font-size: 18px;
    transform: rotate(-45deg);
  }
  ```

  Keep the arrow decorative; the anchor remains the accessible interaction target.

- [ ] **Step 4: Restyle the existing `Подробнее` control without changing its label**

  Remove the current gradient treatment through a late override. Use a compact dark translucent capsule with a light border, white text, and sufficient contrast. Keep `.service-btn span` visible and keyboard-independent because the parent anchor is the link.

---

### Task 3: Add Bounded Card Motion And Reduced-Motion State

**Files:**
- Modify: `skins/saparova/css/main.css` near the new card overrides

**Interfaces:**
- Consumes: Existing card hover/focus selectors and the image/overlay/arrow elements from Task 2.
- Produces: Consistent hover, focus-visible, and reduced-motion behavior.

- [ ] **Step 1: Add hover and focus motion**

  Apply the same state to pointer and keyboard interaction:

  ```css
  .service-photo--link:hover img,
  .service-photo--link:focus-visible img {
    transform: scale(1.04);
  }

  .service-photo--link:hover::before,
  .service-photo--link:focus-visible::before {
    background: linear-gradient(180deg, rgba(19,16,13,.04) 25%, rgba(19,16,13,.9) 100%);
  }

  .service-photo--link:hover .service-desc,
  .service-photo--link:focus-visible .service-desc {
    transform: translateY(-6px);
  }

  .service-photo--link:hover::after,
  .service-photo--link:focus-visible::after {
    background: #c99471;
    transform: rotate(-45deg) translate(2px, -2px);
  }
  ```

  Add a visible `outline`/`outline-offset` on `:focus-visible` without relying on the arrow alone.

- [ ] **Step 2: Add reduced-motion overrides**

  Under `@media (prefers-reduced-motion: reduce)`, disable image/desc/arrow transforms and set transitions to `none` while keeping hover/focus color and overlay states visible.

- [ ] **Step 3: Verify motion states in browser**

  Use Playwright to focus and hover a card at desktop and mobile-compatible viewports. Assert computed transform changes when motion is enabled, computed transition is `none` under reduced motion, and all title/price/`Подробнее` text remains visible.

---

### Task 4: Full Visual And Content Verification

**Files:**
- Test: ephemeral Node/Playwright script only; no production test file

**Interfaces:**
- Consumes: Four updated pages and unchanged content tuples.
- Produces: Evidence for width alignment, card presentation, motion, responsive layout, and detector cleanliness.

- [ ] **Step 1: Verify all content tuples**

  Assert the service index still has the exact three `[href, image src, title text, price text]` tuples and exactly three cards. Assert each detail page retains its existing counts for scope items, pricing rows, project items, and contact form.

- [ ] **Step 2: Verify three viewports**

  At 1440, 768, and 390, assert no overflow, wide section-axis alignment, desktop three-column cards, mobile one-column cards, portrait image ratio, visible titles/prices/`Подробнее`, and visible arrow affordances.

- [ ] **Step 3: Run final mechanical checks**

  ```bash
  node C:\Users\nizze\.claude\skills\impeccable\scripts\detect.mjs --json portfolio/service.htm portfolio/service/3d-visualization.html portfolio/service/author-supervision.html portfolio/service/interior-design.html
  git diff --check -- portfolio/service.htm portfolio/service/3d-visualization.html portfolio/service/author-supervision.html portfolio/service/interior-design.html skins/saparova/css/main.css
  ```

- [ ] **Step 4: Confirm protected files**

  Confirm `portfolio/about.htm`, JavaScript files, dependency manifests, service-card text, image paths, prices, links, and `Подробнее` labels were not changed.
