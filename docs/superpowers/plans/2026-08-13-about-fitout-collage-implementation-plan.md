# About Fit-Out Collage Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build the approved warm full-width About collage hero, repair two project hero assets, refine About interactions, and add a matching Комплектация service page and card.

**Architecture:** Keep the existing static HTML and vanilla JS architecture. Extend existing about/service/project selectors in `main.css` and `contact.css`, add the new service page by copying the established service detail structure, and use only existing local images. No form endpoints, field IDs, validation, gallery routes, or third-party dependencies change.

**Tech Stack:** Static HTML, CSS, vanilla JavaScript, existing Manrope/Font Awesome, Playwright, Impeccable detector.

## Global Constraints

- Use the existing local portrait assets `portfolio/hero/designer-photo.png` and `portfolio/hero/hero-photo.png`.
- Use `portfolio/assets/projects/zhk-euro/cover.jpg` for the Комплектация card/visual.
- Use existing project `cover.jpg` files as hero fallbacks for the two missing project heroes.
- Preserve existing navigation, form endpoints, form IDs/names, phone mask, validation, galleries, and routes.
- No new dependencies.
- No horizontal overflow at `1440px`, `991px`, `767px`, or `390px`.
- Honor `prefers-reduced-motion`.
- Preserve the warm-neutral palette, Manrope hierarchy, terracotta accent, and section rhythm.

---

### Task 1: Repair Project Hero Image Sources

**Files:**
- Modify: `portfolio/privateinterior/zhk-ekaterininskij-park.html:35`
- Modify: `portfolio/privateinterior/zhk-euro.html:35`
- Test: Playwright image loading in Task 6

**Interfaces:**
- Consumes: existing `--project-intro-image` inline custom property and project cover assets.
- Produces: loaded hero backgrounds without changing title, metadata, or gallery data.

- [ ] **Step 1: Replace missing image variables**

Set the project intro custom properties to existing files:

```html
style="--project-intro-image: url('../assets/projects/zhk-ekaterininskij-park/cover.jpg');"
```

and:

```html
style="--project-intro-image: url('../assets/projects/zhk-euro/cover.jpg');"
```

- [ ] **Step 2: Verify both backgrounds resolve**

Open both pages with Playwright and assert the computed `background-image` contains the expected `cover.jpg` URL and the page has no horizontal overflow.

### Task 2: Build The Warm Full-Width About Hero

**Files:**
- Modify: `portfolio/about.htm:115-131`
- Modify: `skins/saparova/css/main.css:6722-6960` and about-specific overrides
- Test: about desktop/mobile screenshots and DOM assertions in Task 6

**Interfaces:**
- Consumes: existing About copy, two local portrait assets, `#about-metrics` anchor, and current reveal classes.
- Produces: full-width warm editorial hero with education/project info blocks and animated collage.

- [ ] **Step 1: Add the approved information blocks and second portrait**

Keep the existing factual copy and CTA destination. Add this content to the hero markup:

```html
<div class="hero-collage" aria-label="Портреты Виталины Ромашкевич">
  <img class="hero-collage__primary" src="../portfolio/hero/designer-photo.png" alt="">
  <img class="hero-collage__secondary" src="../portfolio/hero/hero-photo.png" alt="">
</div>

<div class="hero-info-grid">
  <div class="hero-info-block">
    <span class="hero-info-block__label">Образование</span>
    <span class="hero-info-block__value">Астраханский государственный институт</span>
    <span class="hero-info-block__detail">Архитектура и строительство</span>
  </div>
  <div class="hero-info-block">
    <span class="hero-info-block__label">Проекты</span>
    <span class="hero-info-block__value">120+</span>
    <span class="hero-info-block__detail">Реализованных проектов</span>
  </div>
</div>
```

Do not add an education year because none was supplied.

- [ ] **Step 2: Set the full-width warm hero surface**

Use `#f8f5f0` rather than a dark theme. The hero should fill the viewport width, with the text column left and collage right:

```css
.hero-section {
  position: relative;
  overflow: hidden;
  background: #f8f5f0;
  padding: 96px 0 80px;
}

.hero-wrapper {
  display: grid;
  grid-template-columns: minmax(280px, .85fr) minmax(420px, 1.15fr);
  gap: clamp(48px, 8vw, 128px);
  align-items: stretch;
}

.hero-collage {
  position: relative;
  min-height: 620px;
}

.hero-collage__primary {
  position: absolute;
  inset: 0 0 0 18%;
  width: 82%;
  height: 100%;
  object-fit: cover;
}

.hero-collage__secondary {
  position: absolute;
  right: 52%;
  bottom: 0;
  width: 30%;
  height: 42%;
  object-fit: cover;
  border: 8px solid #f8f5f0;
}
```

Use dark ink text, muted beige rules, and terracotta only for the CTA/selected accents.

- [ ] **Step 3: Add the authored reveal sequence**

Animate only opacity and transform: text first, primary portrait second, secondary portrait third, info blocks last. Use CSS classes already used by the page and guard the animation with `@media (prefers-reduced-motion: no-preference)`. Under reduced motion, all content is visible immediately with no delay.

- [ ] **Step 4: Define the mobile collage**

At `max-width: 767px`, stack text and collage, set collage height to `460px`, keep the secondary portrait as a small overlap, and preserve the CTA/info blocks without overflow.

### Task 3: Constrain Render Slider And Refine Featured Link

**Files:**
- Modify: `portfolio/about.htm:250-280,526` if link/arrow markup requires it
- Modify: `skins/saparova/css/contact.css` for render slider
- Modify: `skins/saparova/css/main.css` for featured link interaction
- Test: About computed width and hover/focus transform in Task 6

**Interfaces:**
- Consumes: existing render Juxtapose and `.featured-header__link` destination.
- Produces: `1400px` render width and editorial text-link behavior.

- [ ] **Step 1: Set render slider width**

Use:

```css
.render-vs-real__slider {
  width: 100%;
  max-width: 1400px;
  margin: 0 auto;
}
```

At mobile, use `max-width: 100%` and preserve the existing touch/drag behavior.

- [ ] **Step 2: Convert featured project link to text link**

Keep the current `index.htm` href and use an explicit arrow element:

```html
<a href="index.htm" class="featured-header__link">
  Все проекты <span aria-hidden="true" class="featured-header__arrow">↗</span>
</a>
```

Style it without filled background or card treatment. Animate only the arrow transform on hover/focus, using approximately `18px` size and the existing page accent.

### Task 4: Add Комплектация Card

**Files:**
- Modify: `portfolio/service.htm:116-154`
- Modify: `skins/saparova/css/main.css:11569-11753` if card arrow styling needs a scoped update
- Test: card count, href, image source, and responsive grid in Task 6

**Interfaces:**
- Consumes: existing `.service-photo--link` card system and service wrapper.
- Produces: fourth service card linked to the new detail page.

- [ ] **Step 1: Add the card using an existing local visual**

Add a fourth card with this contract:

```html
<a href="service/komplektaciya.html" class="service-photo service-photo--link col-xs-12 col-sm-6 col-md-4 col-lg-4">
  <img class="mw-100" src="assets/projects/zhk-euro/cover.jpg" alt="Комплектация интерьера">
  <div class="service-desc">
    <div class="service-title">КОМПЛЕКТАЦИЯ</div>
    <div class="service-price">Мебель, материалы и свет</div>
    <div class="service-btn"><span>Подробнее <i class="fa fa-long-arrow-right" aria-hidden="true"></i></span></div>
  </div>
</a>
```

Preserve the existing card hover/focus behavior and do not create a new card component system.

- [ ] **Step 2: Normalize service arrow size**

Set the scoped arrow icon to `18px` with a transform-only hover state:

```css
.service-btn .fa-long-arrow-right {
  font-size: 18px;
  line-height: 1;
  transition: transform .25s cubic-bezier(.16, 1, .3, 1);
}

.service-photo--link:hover .service-btn .fa-long-arrow-right,
.service-photo--link:focus-visible .service-btn .fa-long-arrow-right {
  transform: translateX(4px);
}
```

### Task 5: Create Комплектация Detail Page

**Files:**
- Create: `portfolio/service/komplektaciya.html`
- Reuse styles: `skins/saparova/css/main.css`, `skins/saparova/css/contact.css`
- Test: route, sections, form contract, responsive layout in Task 6

**Interfaces:**
- Consumes: service detail structure and existing contact form behavior from `3d-visualization.html`.
- Produces: a complete service page at `portfolio/service/komplektaciya.html`.

- [ ] **Step 1: Create the shared service detail shell**

Use the same header, font imports, loading mask, social menu, footer, `apps.js`, and form scripts as the existing detail pages. Keep the service marker and title within the established service hero layout:

```html
<span class="service-page-marker reveal">[ Комплектация ]</span>
<h1 class="service-hero__title reveal">Комплектация интерьера</h1>
<p class="service-hero__subtitle reveal">Подбираю материалы, мебель, свет и декор, организую закупки и контролирую поставки до полной готовности интерьера.</p>
<a href="../contact.html" class="service-hero__cta reveal">Обсудить комплектацию</a>
```

- [ ] **Step 2: Add the content sections**

Add a `[ Что входит ]` scope section with concise items for material selection, furniture/light selection, supplier comparison, budget tracking, ordering, logistics, delivery, and acceptance. Add `[ Как проходит работа ]` with four stages: brief and budget, selection, approval and ordering, delivery and acceptance.

- [ ] **Step 3: Add existing portfolio strip and consultation form**

Reuse existing project assets (`zhk-euro`, `zhk-vse-svoi`, `private-house-krd`) and the same `contact-form contact-form--single` contract as other services. Keep the existing privacy link, required fields, phone mask, success state, and validation script. Do not add a fictional price.

- [ ] **Step 4: Verify route and page rhythm**

Assert the new page loads with status 200, contains the new title, has the same header/footer computed tokens as sibling service pages, and has no horizontal overflow at all target widths.

### Task 6: Full Verification

**Files:**
- Inspect all changed HTML/CSS files from Tasks 1-5.
- Run Impeccable detector on all changed UI files.

- [ ] **Step 1: Render all affected surfaces**

Render about, both repaired project pages, service catalog, all four sibling/detail service pages, and the new Комплектация page at `1440x900`, `991x900`, `767x900`, and `390x844`.

- [ ] **Step 2: Assert image, links, form and responsive contracts**

Verify both project hero backgrounds load, About has two collage images and two info blocks, render slider width is at most `1400px`, featured link keeps its route, service card count is `4`, new route resolves, and all forms preserve their existing fields and phone mask.

- [ ] **Step 3: Run final quality checks**

Run:

```powershell
node "C:\Users\nizze\.claude\skills\impeccable\scripts\detect.mjs" --json "portfolio/about.htm" "portfolio/contact.html" "portfolio/service.htm" "portfolio/service/komplektaciya.html" "portfolio/privateinterior/zhk-ekaterininskij-park.html" "portfolio/privateinterior/zhk-euro.html" "skins/saparova/css/main.css" "skins/saparova/css/contact.css"
git diff --check
```

Expected: no new detector findings in changed surfaces and no horizontal overflow at target widths.
