# Interior Design Hybrid Page Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans (recommended). Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Expand `portfolio/service/interior-design.html` into a complete editorial sales page inspired by ALEART's information architecture while preserving VITALINA DESIGN's visual system and current functionality.

**Architecture:** Extend the existing static HTML page with three bounded content sections (`Почему это работает`, expanded deliverables, and process) and a hero meta block, then style them through scoped additions in `main.css`. Existing scope, pricing, project, sibling-nav, form, anchors, and inline JS remain in place; no new dependency or server behavior is introduced.

**Tech Stack:** Static HTML, existing CSS, Manrope, Font Awesome already loaded, existing reveal observer, Playwright 1.62.1, Impeccable detector.

## Global Constraints

- `portfolio/about.htm` is the layout reference and remains unchanged.
- Preserve current service title, description, price data, CTA destination, sibling links, pricing rows, project links, form fields, IDs, inline scripts, reveal classes, and success/error behavior.
- New text must be derived from supported content already present in `about.htm` or the current service page; do not invent unsupported claims.
- Do not alter `portfolio/about.htm` or the internal cards on `portfolio/service.htm`.
- Do not add JavaScript dependencies or change form submission behavior in this visual/content expansion.
- Keep the three services independent; no sequence language connects this page to 3D visualization or author supervision as required steps.
- No horizontal overflow at 390px, 768px, or desktop widths.
- `prefers-reduced-motion: reduce` disables transforms and staggered motion.

---

### Task 1: Expand Interior Design Page Content Structure

**Files:**
- Modify: `portfolio/service/interior-design.html:93-230`

**Interfaces:**
- Consumes: Existing hero, scope, pricing, projects, form, sibling navigation, and reveal classes.
- Produces: New semantic sections with stable IDs and existing content journey preserved.

- [ ] **Step 1: Add hero meta without changing existing hero copy**

  Keep the sibling nav, existing marker, `h1`, subtitle, and CTA. Add a compact `.service-hero__meta` group after the subtitle and before the CTA containing only already-supported facts:

  ```html
  <div class="service-hero__meta reveal" aria-label="Параметры проекта">
    <span>от 3 000 ₽/м²</span>
    <span>срок — 60 дней</span>
  </div>
  ```

  Do not remove or alter the existing pricing rows later on the page.

- [ ] **Step 2: Add the benefits section after the hero**

  Insert a new section before the existing `scope-section`:

  ```html
  <section class="service-benefits-section" aria-labelledby="service-benefits-title">
    <div class="container">
      <span class="service-page-marker">[ Почему это работает ]</span>
      <h2 class="service-wide-title" id="service-benefits-title">Архитектурная точность в каждой детали</h2>
      <div class="service-benefits-list">
        <article class="service-benefit reveal"><span>01</span><div><h3>Геометрия пространства</h3><p>Планировка выстраивается под образ жизни и реальные сценарии пространства.</p></div></article>
        <article class="service-benefit reveal"><span>02</span><div><h3>Детали до сантиметра</h3><p>Чертежи, свет, розетки, материалы и стыки прорабатываются до начала ремонта.</p></div></article>
        <article class="service-benefit reveal"><span>03</span><div><h3>Живой диалог</h3><p>Решения рождаются из ваших привычек, пожеланий и образа жизни, а не из шаблонного стиля.</p></div></article>
        <article class="service-benefit reveal"><span>04</span><div><h3>Связь с реализацией</h3><p>Визуализации и документация рассчитаны на реальные условия строительства.</p></div></article>
      </div>
    </div>
  </section>
  ```

  Keep claims aligned with existing `about.htm` copy and avoid new guarantees beyond the current 60-day statement.

- [ ] **Step 3: Add expanded deliverables after the existing scope list**

  Keep the existing three `.scope-list__item` rows untouched. Add a `.deliverables-section` inside the same `scope-section` after `.scope-list` with six existing service outputs:

  ```html
  <div class="deliverables-section" aria-labelledby="deliverables-title">
    <span class="service-page-marker">[ Полный состав ]</span>
    <h3 class="deliverables-title" id="deliverables-title">Вы получите</h3>
    <div class="deliverables-grid">
      <div class="deliverable reveal"><span>Обмерные планы</span><p>Точная основа для планировки и рабочих решений.</p></div>
      <div class="deliverable reveal"><span>Планировочные решения</span><p>Варианты расстановки мебели и техники с согласованием выбранного решения.</p></div>
      <div class="deliverable reveal"><span>Дизайн-концепт</span><p>Коллажи, палитра, материалы, свет и характер каждой комнаты.</p></div>
      <div class="deliverable reveal"><span>Рабочие чертежи</span><p>Комплект документации для реальных строительных работ.</p></div>
      <div class="deliverable reveal"><span>3D-визуализации</span><p>Фотореалистичный образ будущего интерьера до начала ремонта.</p></div>
      <div class="deliverable reveal"><span>Подбор материалов</span><p>Материалы, мебель, свет и спецификации в единой системе проекта.</p></div>
    </div>
  </div>
  ```

- [ ] **Step 4: Add the process section before projects**

  Insert a `service-process-section` after the existing pricing section and before `portfolio-section`. Use six process labels already represented in `about.htm`: знакомство и анкета, замеры и техническое задание, планировочное решение, концепция и подбор материалов, 3D-визуализация, рабочие чертежи.

  ```html
  <section class="service-process-section" aria-labelledby="service-process-title">
    <div class="container">
      <span class="service-page-marker">[ Этапы работы ]</span>
      <h2 class="service-wide-title" id="service-process-title">Как строится процесс</h2>
      <div class="service-process-list">
        <article class="service-process-item reveal"><span>01</span><h3>Знакомство и анкета</h3><p>Пожелания, бюджет и сроки проекта.</p></article>
        <article class="service-process-item reveal"><span>02</span><h3>Замеры и техническое задание</h3><p>Обмеры, фотофиксация и исходные данные.</p></article>
        <article class="service-process-item reveal"><span>03</span><h3>Планировочное решение</h3><p>Варианты планировки с расстановкой мебели.</p></article>
        <article class="service-process-item reveal"><span>04</span><h3>Концепция и материалы</h3><p>Коллажи, мебель, свет и отделочные решения.</p></article>
        <article class="service-process-item reveal"><span>05</span><h3>3D-визуализация</h3><p>Фотореалистичное представление каждой комнаты.</p></article>
        <article class="service-process-item reveal"><span>06</span><h3>Рабочие чертежи</h3><p>Документация для передачи строителям.</p></article>
      </div>
    </div>
  </section>
  ```

- [ ] **Step 5: Add personal-meeting context without changing the form**

  Add a short `.contact-section__subtitle` or existing-compatible paragraph before the current form, using the current tone: `Оставьте контакты — обсудим задачу, состав работ и подходящий формат проекта.` Do not change form IDs, fields, validation, or inline script.

---

### Task 2: Style The Hybrid Editorial Sections

**Files:**
- Modify: `skins/saparova/css/main.css` after the existing service-page overrides

**Interfaces:**
- Consumes: New classes from Task 1 and existing `about.htm` tokens.
- Produces: Wide, editorial, responsive sections without generic boxed-card repetition.

- [ ] **Step 1: Style the hero meta and wide headings**

  Use the broad `.container` axis, a two-column meta row at desktop, and a stacked row on mobile. Keep the hero title within 2-3 lines and preserve the existing CTA contrast.

- [ ] **Step 2: Style benefits as editorial rows**

  Use a two-column grid of rows with top rules, terracotta numbers, a strong heading, and muted explanatory copy. Avoid border-radius cards and shadows. At mobile, stack rows with the same DOM order.

- [ ] **Step 3: Style deliverables as a ruled six-item grid**

  Use three columns at wide desktop, two at tablet, one at mobile. Give each item a top rule and enough vertical padding for a 44px minimum content region. No icon placeholders or decorative bento blocks.

- [ ] **Step 4: Style process as a wide six-step timeline/list**

  Use full container width, thin rules, numbers aligned to one rail, and a readable text measure. Do not imply that 3D visualization or author supervision are required services; these are only internal design-project steps.

- [ ] **Step 5: Preserve section contrast and about rhythm**

  Use 80px desktop and 64px mobile section padding, alternating white/off-white surfaces, and the existing marker/title spacing from `about.htm`. Keep pricing rows and project strip compatible with the expanded page.

---

### Task 3: Add Motion And Responsive Details

**Files:**
- Modify: `skins/saparova/css/main.css` scoped motion/reduced-motion rules

**Interfaces:**
- Consumes: Existing `.reveal` observer and new benefit/deliverable/process/project classes.
- Produces: Restrained motion consistent with the existing site.

- [ ] **Step 1: Assign reveal timing without changing JavaScript**

  Use CSS transition delays by item position for benefits, deliverables, and process rows. The existing observer remains responsible for adding `.visible`.

- [ ] **Step 2: Add project image hover/focus motion**

  Apply `transform: scale(1.03)` to existing `.portfolio-strip__item img` on hover/focus, with overflow preserved and no layout shift.

- [ ] **Step 3: Add reduced-motion overrides**

  Under `@media (prefers-reduced-motion: reduce)`, set transition durations to `0s`, remove transforms and delay, and keep all content visible when `.visible` is added.

- [ ] **Step 4: Verify keyboard and touch states**

  Confirm project links, pricing links, sibling nav, CTA, and form controls retain visible focus and usable touch dimensions at 390px.

---

### Task 4: Verify Content, Layout, And Regression Safety

**Files:**
- Test: ephemeral Node/Playwright script; do not add production test files

**Interfaces:**
- Consumes: Updated `interior-design.html`, shared CSS, and unchanged sibling pages.
- Produces: Evidence that the expanded page is complete and existing behavior is preserved.

- [ ] **Step 1: Verify page structure and existing invariants**

  Assert one hero `h1`, one sibling nav with 3 links and one active current link, 3 existing scope rows, 6 deliverables, 3 pricing rows, 6 process items, 3 project items, one `#contactForm`, and the existing CTA/project/form hrefs.

- [ ] **Step 2: Verify all viewport states**

  At 1440px, 768px, and 390px assert no horizontal overflow, broad heading/marker axis, correct section order, no clipped Russian copy, mobile single-column reflow, and visible focus states.

- [ ] **Step 3: Verify motion and reduced-motion**

  Assert project image transform changes on hover when motion is enabled and becomes `none`/`0s` under reduced motion. Verify new reveal sections do not remain hidden after `.visible` is added.

- [ ] **Step 4: Run final mechanical checks**

  ```bash
  node C:\Users\nizze\.claude\skills\impeccable\scripts\detect.mjs --json portfolio/service/interior-design.html
  git diff --check -- portfolio/service/interior-design.html skins/saparova/css/main.css
  ```

  Confirm `portfolio/about.htm`, the other two service detail pages, JavaScript files, dependency manifests, pricing links, project links, and form IDs were not changed.
