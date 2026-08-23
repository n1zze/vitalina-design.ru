# About Hero And Service Card Button Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans (recommended). Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Fix service-card `Подробнее` alignment/radius and refine `about.htm` hero with tighter hierarchy plus a compact proof accent.

**Architecture:** Keep existing HTML and content wherever possible. Add one small proof section after the about hero, then use scoped CSS overrides in `main.css` for hero rhythm, stats weight, proof presentation, and card button geometry. No JavaScript or dependency changes are required.

**Tech Stack:** Static HTML, existing CSS, Manrope, existing reveal observer, local project images, Playwright 1.62.1, Impeccable detector.

## Global Constraints

- Preserve all existing text, links, form behavior, images, stats, CTA destination, and section order outside the new proof accent.
- Do not change service-card names, prices, image paths, or hrefs.
- Do not change service detail pages or shared behavior unrelated to these targets.
- Do not add dependencies or modify JavaScript logic.
- Keep no horizontal overflow at 1440px, 768px, or 390px.
- `prefers-reduced-motion: reduce` disables transforms and delays.

---

### Task 1: Fix Service Card `Подробнее`

**Files:**
- Modify: `skins/saparova/css/main.css` late service-card overrides

**Interfaces:**
- Consumes: Existing `.service-photo .service-btn` and `.service-btn span` markup.
- Produces: Centered, non-pill ghost control without changing card content or links.

- [ ] **Step 1: Normalize button geometry**

  Override the current card control with:

  ```css
  .service-photo .service-btn {
    min-height: 44px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 3px;
    text-align: center;
  }

  .service-photo .service-btn span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    text-align: center;
  }
  ```

  Keep the existing contextual dark/white ghost colors and hover behavior.

- [ ] **Step 2: Verify the root cause is fixed**

  At 1440px, 768px, and 390px assert button `borderRadius === "3px"`, `textAlign === "center"`, span text alignment is centered, height is at least 44px, and card href/title/price/image tuples are unchanged.

---

### Task 2: Refine About Hero Hierarchy

**Files:**
- Modify: `portfolio/about.htm` only to add the proof section hook if required
- Modify: `skins/saparova/css/main.css` about hero overrides

**Interfaces:**
- Consumes: Existing `.hero-section`, `.hero-wrapper`, `.hero-photo`, `.hero-content`, `.hero-stats`, `.hero-cta`.
- Produces: Tighter desktop/mobile hero hierarchy without replacing the split composition.

- [ ] **Step 1: Reduce hero vertical excess**

  Adjust only hero spacing so desktop height is approximately 15-20% lower while preserving photo aspect/crop and all content. Reduce section padding and internal gaps; do not hide bio, stats, CTA, or image.

- [ ] **Step 2: Make hierarchy explicit**

  Keep the author name dominant. Reduce stats number/label visual weight and preserve their values. Keep the CTA terracotta Primary and avoid inherited reveal delay on its hover state.

- [ ] **Step 3: Preserve mobile reading order**

  Keep `photo -> eyebrow -> name -> bio -> stats -> CTA`, reduce gaps, and assert no clipping or overflow at 390px.

---

### Task 3: Add Compact Proof Accent After About Hero

**Files:**
- Modify: `portfolio/about.htm` after the closing `hero-section` and before `approach-section`
- Modify: `skins/saparova/css/main.css` proof-section styles

**Interfaces:**
- Consumes: Existing render/realization image paths from the about page and existing projects link.
- Produces: A small proof block bridging author hero to approach.

- [ ] **Step 1: Add semantic proof markup**

  Insert a section with `aria-labelledby`, existing render/realization images, concise existing-tone text, and a `Посмотреть проекты` link to `index.htm`. Keep it visually quieter than the hero and do not duplicate the full existing comparison slider.

- [ ] **Step 2: Style desktop proof composition**

  Use a wide two-column composition: restrained image pair/visual comparison on one side, proof copy and projects link on the other. Use the current warm palette, thin rules, and 64-80px section rhythm.

- [ ] **Step 3: Style mobile proof composition**

  Stack images, copy, and link in DOM order; preserve readable image crop and no horizontal overflow. Add visible focus for the project link.

- [ ] **Step 4: Add bounded motion**

  Use existing `.reveal` for proof content and a restrained image scale/fade on hover/focus. Under reduced motion disable transforms and delays.

---

### Task 4: Verify Button, Hero, Proof, And Regression Safety

**Files:**
- Test: ephemeral Node/Playwright script only; do not add production test files

**Interfaces:**
- Consumes: `about.htm`, `service.htm`, shared CSS, and protected page invariants.
- Produces: Evidence for centered card controls, hero hierarchy, proof assets, responsive layout, and unchanged content.

- [ ] **Step 1: Verify service-card invariants**

  Assert exact card tuples `[href, image src, title, price, Подробнее]` remain unchanged and button geometry matches Task 1.

- [ ] **Step 2: Verify about hero/proof structure**

  Assert one hero, two stats, one CTA, one proof section, two proof images, one projects link, and expected DOM order.

- [ ] **Step 3: Verify viewports and motion**

  At 1440px, 768px, and 390px assert no overflow, mobile stacking, visible focus states, hero height reduction, proof image loading, and reduced-motion transform reset.

- [ ] **Step 4: Run mechanical checks**

  ```bash
  node C:\Users\nizze\.claude\skills\impeccable\scripts\detect.mjs --json portfolio/about.htm portfolio/service.htm
  git diff --check -- portfolio/about.htm portfolio/service.htm skins/saparova/css/main.css
  ```

  Confirm service detail HTML, JavaScript files, dependency manifests, service-card content, and floating icon controls were not changed.
