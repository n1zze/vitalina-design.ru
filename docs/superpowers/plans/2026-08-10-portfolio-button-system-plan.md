# Portfolio Button System Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans (recommended). Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Unify public portfolio CTA styling into Primary, Inverse, and Text/Ghost roles without changing labels, links, form behavior, or icon controls.

**Architecture:** Keep existing HTML classes and map them contextually through late scoped CSS rules in `main.css` and `contact.css`. Consolidate geometry, typography, focus, hover, and reduced-motion behavior without introducing a new dependency or changing JavaScript.

**Tech Stack:** Static HTML, existing CSS, Manrope, existing Font Awesome/icon controls, Playwright 1.62.1, Impeccable detector.

## Global Constraints

- Preserve all labels, hrefs, form IDs, submit behavior, and navigation destinations.
- Do not change content or interaction logic to implement the visual system.
- Keep the existing service card composition and image assets.
- Keep the current warm VITALINA DESIGN palette and Manrope typography.
- Do not change `portfolio/about.htm` content or unrelated CMS/admin controls.
- Floating phone/Telegram/MAX controls remain icon controls and are not converted into text CTAs.
- Reduced-motion removes transform transitions but preserves color/state feedback.

---

### Task 1: Define Shared CTA Tokens And Geometry

**Files:**
- Modify: `skins/saparova/css/main.css` after existing public portfolio button overrides
- Modify: `skins/saparova/css/contact.css` only for contact-form submit mapping if needed

**Interfaces:**
- Consumes: Existing `.hero-cta`, `.service-hero__cta`, `.contact-form__submit`, `.service-btn`, `.pricing-row__cta`, and `.featured-header__link` selectors.
- Produces: Shared public button geometry and semantic role selectors without HTML changes.

- [ ] **Step 1: Add public CTA tokens**

  Define a scoped public token block using the existing palette:

  ```css
  :root {
    --public-cta-primary: #c99471;
    --public-cta-primary-hover: #b5835f;
    --public-cta-ink: #1a1714;
    --public-cta-rule: #d4c5b5;
  }
  ```

  Keep these tokens limited to public portfolio controls; do not alter CMS/admin `.btn` behavior.

- [ ] **Step 2: Normalize filled control geometry**

  Apply to `.hero-cta` and `.contact-form__submit`:

  ```css
  min-height: 48px;
  padding: 14px 22px;
  border-radius: 3px;
  font-family: "Manrope", sans-serif;
  font-size: 14px;
  font-weight: 600;
  line-height: 1.2;
  transition: background-color .25s ease, color .25s ease,
              border-color .25s ease, transform .25s ease;
  ```

  Do not use gradients or heavy shadows.

- [ ] **Step 3: Normalize focus and reduced-motion states**

  Add one shared `:focus-visible` outline with `outline-offset: 3px`; use `translateY(-2px)` only on hover/focus where the control has room. Under reduced motion set transforms to none and transitions to color/background only.

---

### Task 2: Map Primary And Inverse Roles

**Files:**
- Modify: `skins/saparova/css/main.css`
- Modify: `skins/saparova/css/contact.css`

**Interfaces:**
- Consumes: Shared geometry from Task 1 and existing page contexts.
- Produces: Consistent filled CTA colors by surface context.

- [ ] **Step 1: Map Primary controls on light surfaces**

  Use terracotta background `#c99471` with white text for:

  - `.hero-cta` on `about.htm`;
  - `.contact-form__submit` on `contact.html` and service detail forms;
  - any public consultation/project submit control.

  Hover uses `#b5835f`, while active state moves only 1px if the existing interaction expects it.

- [ ] **Step 2: Map Inverse hero controls**

  Keep the full-bleed service hero CTA white with ink text and scoped hover terracotta/white:

  ```css
  .service-design-hero .service-hero__cta,
  .service-hero--image .service-hero__cta {
    background: #fff;
    color: #1a1714 !important;
  }
  ```

  Explicitly set `align-self: flex-start`, `width: auto`, and `transition-delay: 0s` so reveal sequencing cannot make the hover state jump or stretch.

- [ ] **Step 3: Remove black/white/beige ambiguity from form submits**

  Override the current black `.contact-form__submit` surface in `contact.css` with the Primary role. Preserve all form selectors, validation, disabled behavior, and success/error states.

---

### Task 3: Map Text And Ghost Roles

**Files:**
- Modify: `skins/saparova/css/main.css`

**Interfaces:**
- Consumes: Existing pricing links, featured project link, and service-card `Подробнее` controls.
- Produces: Secondary links that no longer compete with primary CTAs.

- [ ] **Step 1: Normalize pricing and editorial text links**

  Keep `.pricing-row__cta` and `.featured-header__link` as text actions: terracotta text, no fill, underline/border transition, visible focus outline, and no translate motion that changes row height.

- [ ] **Step 2: Normalize service-card Ghost controls**

  Keep `.service-btn`/`Подробнее` as an image-context ghost pill: transparent/dark translucent background, thin white border, white text, compact 44px-compatible hit area. Remove the legacy gradient/pseudo-fill behavior through late overrides; preserve the parent anchor and label.

- [ ] **Step 3: Keep floating controls separate**

  Do not map `.mfb-component__button--main` or child icon controls to text CTA styles. Verify their circular icon geometry remains unchanged.

---

### Task 4: Verify Button Inventory And Visual States

**Files:**
- Test: ephemeral Node/Playwright script only; do not add production tests

**Interfaces:**
- Consumes: All mapped public pages and controls.
- Produces: Evidence for semantic color mapping, dimensions, focus, hover, responsive targets, and preserved content.

- [ ] **Step 1: Inventory mapped controls**

  Extract each public control’s class, text, href/type, computed background, color, width, height, and transition. Assert labels/hrefs and form IDs are unchanged.

- [ ] **Step 2: Verify contexts at three viewports**

  At 1440px, 768px, and 390px assert:

  - Primary controls use terracotta/white;
  - inverse hero CTA uses white/ink;
  - pricing and project links have no filled background;
  - service-card `Подробнее` remains a ghost control;
  - no public CTA is below 44px height on mobile;
  - no horizontal overflow.

- [ ] **Step 3: Verify interaction states**

  Focus and hover representative primary, inverse, text, ghost, and form controls. Assert visible focus outline, no layout width jump, no reveal delay on hero CTA, and reduced-motion transform reset.

- [ ] **Step 4: Run final mechanical checks**

  ```bash
  node C:\Users\nizze\.claude\skills\impeccable\scripts\detect.mjs --json portfolio/about.htm portfolio/contact.html portfolio/service.htm portfolio/service/3d-visualization.html portfolio/service/author-supervision.html portfolio/service/interior-design.html
  git diff --check -- skins/saparova/css/main.css skins/saparova/css/contact.css
  ```

  Confirm no JavaScript, dependency manifests, floating icon controls, or unrelated CMS/admin controls changed.
