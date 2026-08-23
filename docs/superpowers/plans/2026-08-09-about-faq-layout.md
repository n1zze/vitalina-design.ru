# About Page FAQ And Layout Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Update the About page copy and metrics, add an accessible reference-inspired FAQ, and keep the complete page aligned to its existing desktop/mobile layout system.

**Architecture:** Keep the FAQ as static semantic HTML inside the existing `CMS:about` content block. Use native `details/summary` for interaction and shared CSS for the existing Manrope, warm-neutral palette, divider rhythm, focus treatment, and responsive container.

**Tech Stack:** Static HTML, shared CSS, Font Awesome already used by the page, Playwright for layout smoke checks.

## Global Constraints

- Preserve the existing visual language: Manrope, warm off-white/white section alternation, muted beige rules, and terracotta accent.
- FAQ placement is after testimonials and before `CMS:about:end`.
- Desktop layout uses the existing `.container` width and a 12-column mental grid.
- Mobile layout collapses to one column.
- Use native `details`/`summary` elements; no JavaScript is required for FAQ interaction.
- Do not let the FAQ or hero introduce horizontal scrolling at 390px, 768px, or desktop widths.
- Keep heading order valid and preserve visible keyboard focus.

---

### Task 1: Update About Hero Content

**Files:**
- Modify: `portfolio/about.htm:133-149`

- [ ] **Step 1: Replace the lead copy and metrics**

Use:

```html
<p class="hero-content__bio">Виталина Ромашкевич — дизайнер интерьера, архитектор, который ведёт ваш проект.</p>
```

Keep the existing architectural approach paragraph. Set the two metrics to:

```html
15+  / лет опыта в проектировании интерьеров
120+ / реализованных проектов под ключ
```

Keep the existing single divider between the two metric items and preserve the CTA link to `contact.html`.

- [ ] **Step 2: Verify the hero content statically**

Run:

```powershell
rg -n "Виталина Ромашкевич|data-max=\"15\"|data-max=\"120\"|hero-stats__item" portfolio/about.htm
```

Expected: the new lead, both approved metrics, exactly two metric items, and one `.hero-stats__divider` are present.

---

### Task 2: Add Semantic FAQ Markup

**Files:**
- Modify: `portfolio/about.htm` immediately before `<!-- CMS:about:end -->`

- [ ] **Step 1: Add the FAQ section**

Add one section with `class="faq-section"` and `aria-labelledby="faq-title"`. Use one `h2` titled `Частые вопросы`, a short intro, and six native accordion items. The first item includes `open`.

Questions and answers:

```html
<details class="faq-item" open>
  <summary>Что входит в дизайн-проект?</summary>
  <div class="faq-item__answer"><p>Планировочное решение, концепция интерьера, 3D-визуализации, рабочие чертежи и спецификации материалов, мебели и света.</p></div>
</details>
<details class="faq-item">
  <summary>Сколько времени занимает работа над проектом?</summary>
  <div class="faq-item__answer"><p>Срок зависит от площади и состава проекта. После знакомства я обозначаю этапы, сроки и точки согласования до начала работы.</p></div>
</details>
<details class="faq-item">
  <summary>Сколько стоит дизайн-проект?</summary>
  <div class="faq-item__answer"><p>Стоимость зависит от площади и выбранного состава услуг. Обсудим задачу и подготовим расчёт после знакомства с объектом.</p></div>
</details>
<details class="faq-item">
  <summary>Работаете ли вы удалённо?</summary>
  <div class="faq-item__answer"><p>Да, часть этапов можно вести дистанционно: встречи, согласования и рабочие материалы доступны онлайн.</p></div>
</details>
<details class="faq-item">
  <summary>Можно ли заказать авторский надзор отдельно?</summary>
  <div class="faq-item__answer"><p>Да. Авторский надзор можно подключить отдельно для контроля реализации проекта, материалов и соответствия работ чертежам.</p></div>
</details>
<details class="faq-item">
  <summary>Что делать, если я не знаю, какой стиль интерьера выбрать?</summary>
  <div class="faq-item__answer"><p>Это нормально. На первой встрече разберём образ жизни, привычки и референсы, а затем сформируем направление, которое подходит именно вам.</p></div>
</details>
```

- [ ] **Step 2: Verify semantics**

Check that the page has six `details.faq-item`, one `open` item, one FAQ `h2`, and no FAQ script dependency.

---

### Task 3: Implement Responsive FAQ Layout And States

**Files:**
- Modify: `skins/saparova/css/main.css` near the existing About page sections after testimonials styles

- [ ] **Step 1: Add the desktop grid**

Use the existing `.container` as the alignment boundary. Set `.faq-section` to the same vertical padding rhythm as `.approach-section` and `.featured-section`. Use a grid with `minmax(0, 4fr) minmax(0, 8fr)` columns and a gap aligned with the existing 60–90px section spacing. Do not wrap questions in cards.

- [ ] **Step 2: Style the accordion rows**

Use 1px beige rules, a compact uppercase eyebrow only if the existing page rhythm needs it, a 20–24px question label, and a terracotta plus icon created with CSS. The plus rotates to an x-like state when `details[open]`. The answer has a readable max-width and calm line height.

- [ ] **Step 3: Add keyboard and mobile behavior**

Keep native summary interaction, remove the browser marker only when replaced with an equivalent visible CSS indicator, add `:focus-visible`, set minimum interactive row height to 44px, and collapse to one column at 767px. Ensure answer padding and icon alignment remain stable when content wraps.

- [ ] **Step 4: Verify CSS does not disturb existing sections**

Confirm selectors are scoped to `.faq-section`, `.faq-item`, and their children; do not change global `h2`, `details`, or `summary` rules.

---

### Task 4: Run UX/UI Layout Verification

**Files:**
- Test: `portfolio/about.htm` and `skins/saparova/css/main.css`

- [ ] **Step 1: Run Playwright checks at all required widths**

Verify at 1440px, 768px, and 390px:

- hero has exactly two metrics;
- FAQ has six `details` items and one open item;
- FAQ heading and accordion share the same container edges;
- the CTA remains visible in the hero flow;
- expanded answers do not overflow horizontally;
- `summary` elements have visible focus outlines.

- [ ] **Step 2: Exercise keyboard interaction**

Focus the first FAQ summary, press `Enter`, verify its `open` state changes, and confirm focus remains on the summary.

- [ ] **Step 3: Run final checks**

Run:

```powershell
git diff --check
```

Expected: no whitespace errors.
