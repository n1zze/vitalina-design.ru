# Interior Design Image-Led Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans (recommended). Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Make the design-project page image-led like the approved ALEART reference, with a full-bleed hero and six alternating image/text deliverables replacing the old three-row scope list.

**Architecture:** Extend only `portfolio/service/interior-design.html` and shared `main.css`. The current service page remains static and keeps its sibling navigation, pricing, projects, form, inline scripts, and factual content. The new deliverables section uses local `zhk-euro` images and anchor navigation with native links, no new JavaScript dependency.

**Tech Stack:** Static HTML, existing CSS, Manrope, existing `.reveal` observer, local JPG assets, Playwright 1.62.1, Impeccable detector.

## Global Constraints

- `portfolio/about.htm` remains unchanged and is the visual reference.
- Use only local project images from `portfolio/assets/projects/zhk-vse-svoi/` and `portfolio/assets/projects/zhk-euro/`.
- Preserve current service facts and functionality, including pricing rows, project links, form IDs, validation, success/error states, sibling navigation, and CTA destination.
- Replace the existing three scope rows with six image-led deliverables; preserve their factual content in the new descriptions.
- Do not change JavaScript files, dependencies, other service pages, or service index cards.
- Do not copy ALEART brand assets, exact copy, bonuses, or external images.
- No horizontal overflow at 390px, 768px, or desktop widths.
- `prefers-reduced-motion: reduce` disables transforms, delays, and transition durations.

---

### Task 1: Replace Text Scope With Reference-Style Image Section

**Files:**
- Modify: `portfolio/service/interior-design.html:93-230`

**Interfaces:**
- Consumes: Existing hero, sibling nav, current pricing/projects/form sections, and local `zhk-euro` assets.
- Produces: Full-bleed hero image and reference-style `Что входит` block with six local image/text rows.

- [ ] **Step 1: Convert the hero to full-bleed image composition**

  Keep the existing sibling nav, marker, title, subtitle, price meta, CTA text, and CTA href. Add or wrap the hero image using:

  ```html
  <div class="service-design-hero__image" aria-hidden="true">
    <img src="../assets/projects/zhk-euro/cover.jpg" alt="" decoding="async">
  </div>
  <div class="service-design-hero__overlay"></div>
  ```

  Place the existing text content in a `.service-design-hero__content` layer above the image. Do not replace the current service facts with ALEART copy.

- [ ] **Step 2: Replace only the old scope list with the new deliverables section**

  Keep the section ID `scope` and section marker, but replace the three `.scope-list__item` rows with:

  ```html
  <div class="image-deliverables" aria-labelledby="deliverables-title">
    <div class="image-deliverables__intro">
      <span class="service-page-marker">[ Что входит ]</span>
      <h2 class="service-wide-title" id="deliverables-title">Вы получите</h2>
    </div>
    <nav class="image-deliverables__nav" aria-label="Состав дизайн-проекта">
      <a href="#deliverable-measurements">Обмерные планы</a>
      <a href="#deliverable-layout">Планировочные решения</a>
      <a href="#deliverable-concept">Дизайн-концепт</a>
      <a href="#deliverable-drawings">Рабочие чертежи</a>
      <a href="#deliverable-3d">3D-визуализации</a>
      <a href="#deliverable-materials">Подбор материалов</a>
    </nav>
    <div class="image-deliverables__list">
      <!-- six rows, each with id, local image, heading, and existing-supported explanation -->
    </div>
  </div>
  ```

  Add exactly six semantic rows with IDs and local image paths:

  ```text
  #deliverable-measurements -> ../assets/projects/zhk-vse-svoi/1.jpg
  #deliverable-layout       -> ../assets/projects/zhk-euro/02.jpg
  #deliverable-concept      -> ../assets/projects/zhk-euro/03.jpg
  #deliverable-drawings     -> ../assets/projects/zhk-euro/04.jpg
  #deliverable-3d           -> ../assets/projects/zhk-euro/05.jpg
  #deliverable-materials    -> ../assets/projects/zhk-euro/06.jpg
  ```

  Each row contains an image with meaningful alt text, the deliverable heading, and a concise explanation derived from the old three scope rows and existing `about.htm` copy. Do not retain the old three-row list.

- [ ] **Step 3: Keep all later sections unchanged in meaning and order**

  Preserve the existing pricing section, three pricing rows, process section if present, projects section and three project links, contact form, IDs, inline validation, and footer. Only adjust wrappers/classes when necessary for the new section styling.

- [ ] **Step 4: Add image-section structure checks**

  Run Playwright and assert one hero image, six deliverable rows, six deliverable images, six anchor links, matching target IDs, three pricing rows, three project links, one `#contactForm`, and one sibling nav with one active current link.

---

### Task 2: Style Full-Bleed Hero And Deliverables

**Files:**
- Modify: `skins/saparova/css/main.css` after existing interior-detail overrides

**Interfaces:**
- Consumes: New hero and `.image-deliverables` structure from Task 1.
- Produces: Cinematic desktop hero, reference-like deliverable rows, wide section axis, and mobile composition.

- [ ] **Step 1: Style the hero image layer**

  Use a relative full-bleed section with a minimum desktop height around `clamp(560px, 72vh, 760px)`. Image covers the entire section. Overlay uses a directional gradient from a dark left content zone to a lighter image area. Text remains readable at desktop and mobile.

- [ ] **Step 2: Style the six-row deliverables section**

  Use a short intro and horizontal anchor nav above the rows. Each row is a two-column editorial split with image and text, alternating image side using `:nth-child(even)`. Use large image aspect ratios, thin rules, wide spacing, and no card shadows/radius.

- [ ] **Step 3: Add responsive layout**

  At tablet, reduce image/text columns while preserving readable copy. At mobile, collapse each row to image first, then heading and explanation; anchor nav becomes a vertical or horizontally scrollable native link group with no page overflow.

- [ ] **Step 4: Preserve existing visual system**

  Use the `about.htm` palette, Manrope, markers, wide `.container` axis, 80px desktop section cadence and 64px mobile cadence. Do not introduce ALEART's typography or branding.

---

### Task 3: Add Image Motion And Accessibility States

**Files:**
- Modify: `skins/saparova/css/main.css` scoped motion/reduced-motion rules

**Interfaces:**
- Consumes: Existing `.reveal` behavior and new hero/deliverable images.
- Produces: Bounded image motion, focus states, and reduced-motion compliance.

- [ ] **Step 1: Add hero image entrance**

  Use opacity and scale on `.service-design-hero__image img` with the existing reveal/visible state or an already-visible default plus transition. Do not add a new JS observer.

- [ ] **Step 2: Add deliverable reveal and hover/focus**

  Add staggered delays to `.image-deliverables__row:nth-child(n)` and scale each row image to `1.03` on hover/focus when the row is interactive. Ensure the anchor navigation has visible focus.

- [ ] **Step 3: Add reduced-motion overrides**

  Under `@media (prefers-reduced-motion: reduce)`, set all new image transitions and delays to `none/0s`, reset transforms, and keep text visible.

- [ ] **Step 4: Check keyboard and touch states**

  Verify anchor links, sibling navigation, CTA, pricing rows, project links, and form fields retain visible focus and usable touch areas at 390px.

---

### Task 4: Verify Image Paths, Existing Functionality, And Layout

**Files:**
- Test: ephemeral Node/Playwright script only; do not add production test files

**Interfaces:**
- Consumes: Updated interior-design page and shared CSS.
- Produces: Evidence for image rendering, content preservation, responsive safety, and detector output.

- [ ] **Step 1: Verify image and content inventory**

  Assert the hero image and six deliverable images have `naturalWidth > 0`, expected local src paths, meaningful alt text, six unique IDs, six matching nav hrefs, three pricing rows, three project links, one form, and preserved CTA href.

- [ ] **Step 2: Verify viewport composition**

  At 1440px, 768px, and 390px assert no horizontal overflow, hero text/CTA visible, deliverables ordered correctly, desktop alternating rows, mobile stacked rows, no clipped Russian text, and broad section alignment.

- [ ] **Step 3: Verify motion states**

  Assert hero/project/deliverable image transform changes when motion is enabled and transitions become `0s`/transforms `none` under reduced motion.

- [ ] **Step 4: Run mechanical checks**

  ```bash
  node C:\Users\nizze\.claude\skills\impeccable\scripts\detect.mjs --json portfolio/service/interior-design.html
  git diff --check -- portfolio/service/interior-design.html skins/saparova/css/main.css
  ```

  Confirm `portfolio/about.htm`, the other service pages, JS files, dependency manifests, pricing links, project links, and form IDs were not changed.
