# About Hero Warm Editorial Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans (recommended). Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Rebuild the `about.htm` hero as a warm editorial two-column author block followed by a separate four-metric section, with no CTA inside the hero.

**Architecture:** Keep the existing hero portrait/content markup and stats animation hooks, move the four metrics into a new semantic section after the hero, and apply scoped CSS in `main.css`. Existing proof section and all page CTAs outside the hero remain unchanged.

**Tech Stack:** Static HTML, existing CSS, Manrope, existing reveal/stats JavaScript, Playwright 1.62.1, Impeccable detector.

## Global Constraints

- Keep the warm `#f8f5f0` VITALINA DESIGN visual world and existing `.container` rhythm.
- Remove the CTA from the hero completely.
- Use exactly four metrics: `15+` / `Лет опыта`, `120+` / `Реализованных проектов`, `31` / `Помещение`, `3300 м²` / `Площади`.
- Preserve the current portrait, author name, bio, stats animation hooks, proof section, approach section, page CTA outside hero, links, and JavaScript behavior.
- Do not change other pages or service-card content.
- Do not add dependencies.
- Keep no horizontal overflow at 1440px, 768px, or 390px.
- Reduced-motion removes transforms and delays.

---

### Task 1: Update About Hero Markup

**Files:**
- Modify: `portfolio/about.htm:115-177`

**Interfaces:**
- Consumes: Existing hero section, portrait, copy, stats values, proof section.
- Produces: CTA-free hero and separate `about-metrics-section`.

- [ ] **Step 1: Remove the hero CTA only**

  Remove the `.hero-cta` anchor inside `.hero-content`. Preserve the existing `contact.html` CTA later in the page and all other links.

- [ ] **Step 2: Replace stats markup with four metric items**

  Keep `.hero-stats`, `role="list"`, and `aria-label`. Use four `.hero-stats__item` entries with exact values/labels:

  ```html
  <div class="hero-stats__item" role="listitem"><span class="hero-stats__number" data-max="15" data-suffix="+">15+</span><span class="hero-stats__label">Лет опыта</span></div>
  <div class="hero-stats__item" role="listitem"><span class="hero-stats__number" data-max="120" data-suffix="+">120+</span><span class="hero-stats__label">Реализованных проектов</span></div>
  <div class="hero-stats__item" role="listitem"><span class="hero-stats__number" data-max="31">31</span><span class="hero-stats__label">Помещение</span></div>
  <div class="hero-stats__item" role="listitem"><span class="hero-stats__number" data-max="3300">3300 м²</span><span class="hero-stats__label">Площади</span></div>
  ```

  Remove `.hero-stats__divider`; the new section CSS will define separators.

- [ ] **Step 3: Move metrics into a separate section**

  Place the stats block after the closing hero section and before the existing `about-proof-section`:

  ```html
  <section class="about-metrics-section" aria-labelledby="about-metrics-title">
    <div class="container">
      <h2 class="about-metrics-title" id="about-metrics-title">Обо мне в цифрах</h2>
      <div class="about-metrics-grid">
        <!-- existing four .hero-stats__item elements -->
      </div>
    </div>
  </section>
  ```

  Preserve stats data attributes so existing counting behavior continues to work.

---

### Task 2: Style Warm Editorial Hero And Metrics

**Files:**
- Modify: `skins/saparova/css/main.css` after existing about normalization rules

**Interfaces:**
- Consumes: `.about-metrics-section`, `.about-metrics-grid`, four `.hero-stats__item` elements.
- Produces: Reference-like two-column hero and independent metrics section.

- [ ] **Step 1: Tighten hero spacing**

  Reduce desktop hero padding/internal gaps by approximately 15-20% without hiding portrait, name, bio, or stats. Keep the hero split photo/content alignment and the author name as the strongest text element.

- [ ] **Step 2: Style the separate metrics section**

  Use a white or warm-paper section with heading `Обо мне в цифрах`, four equal desktop columns, terracotta values, muted labels, and thin beige separators. The metrics section must have its own vertical padding and be visually distinct from the hero.

- [ ] **Step 3: Add mobile two-by-two metrics**

  At `max-width: 767px`, use two columns, internal row/column rules, natural label wrapping, and preserve portrait -> copy -> metrics reading order.

- [ ] **Step 4: Preserve motion and accessibility**

  Keep existing stats counting/reveal hooks, ensure heading order is valid, and remove transforms/delays under reduced motion.

---

### Task 3: Verify Hero Rhythm And Regression Safety

**Files:**
- Test: ephemeral Node/Playwright script only; no production test file

**Interfaces:**
- Consumes: `about.htm`, proof section, approach section, and service index card tuples.
- Produces: Evidence for exact metrics, CTA removal, layout, and protected content.

- [ ] **Step 1: Verify hero and metrics markup**

  At 1440px, 768px, and 390px assert one hero, zero CTA anchors inside hero, one `about-metrics-section`, one exact metrics heading, four exact values/labels, and no overflow.

- [ ] **Step 2: Verify geometry**

  Assert desktop hero has visible photo/content columns and metrics grid has four columns; mobile hero stacks and metrics grid has two columns. Verify proof follows metrics and approach follows proof.

- [ ] **Step 3: Verify protected content**

  Assert the page CTA/form outside hero remains present and `service.htm` card tuples remain unchanged.

- [ ] **Step 4: Run mechanical checks**

  ```bash
  node C:\Users\nizze\.claude\skills\impeccable\scripts\detect.mjs --json portfolio/about.htm portfolio/service.htm
  git diff --check -- portfolio/about.htm portfolio/service.htm skins/saparova/css/main.css
  ```

  Confirm no JavaScript files, dependency manifests, service detail pages, or floating icon controls changed.
