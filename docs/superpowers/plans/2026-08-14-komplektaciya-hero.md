# Komplektaciya Hero Image Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add hero background image to the Komplektaciya service page to match other service pages.

**Architecture:** Replace the current hero section HTML with the unified `service-design-hero` pattern used by Interior Design, 3D Visualization, and Author Supervision pages. No CSS changes needed.

**Tech Stack:** HTML, existing CSS classes

## Global Constraints

- File to modify: `portfolio/service/komplektaciya.html`
- Image to use: `../assets/projects/zhk-nebo/cover.jpg`
- CSS classes: `service-design-hero`, `service-design-hero__image`, `service-design-hero__overlay`, `service-design-hero__content`
- No new CSS required — all styles exist in `skins/saparova/css/main.css`

---

### Task 1: Replace hero section HTML

**Files:**
- Modify: `portfolio/service/komplektaciya.html:42-49`

**Interfaces:**
- Consumes: existing CSS classes from `main.css`
- Produces: updated hero section matching other service pages

- [ ] **Step 1: Read current hero section**

Read lines 42-49 of `portfolio/service/komplektaciya.html` to confirm current structure:

```html
<section class="service-hero service-hero--fitout">
  <div class="container service-hero__inner">
    <span class="service-page-marker reveal">[ Комплектация ]</span>
    <h1 class="service-hero__title reveal">Комплектация интерьера</h1>
    <p class="service-hero__subtitle reveal">Подбираю материалы, мебель, свет и декор, организую закупки и контролирую поставки до полной готовности интерьера.</p>
    <a href="../contact.html" class="service-hero__cta reveal">Обсудить комплектацию</a>
  </div>
</section>
```

- [ ] **Step 2: Replace with unified hero structure**

Replace lines 42-49 with:

```html
<section class="hero-section service-hero service-design-hero">
  <div class="service-design-hero__image reveal" aria-hidden="true">
    <img src="../assets/projects/zhk-nebo/cover.jpg" alt="" decoding="async">
  </div>
  <div class="service-design-hero__overlay" aria-hidden="true"></div>
  <div class="container service-design-hero__content">
    <span class="service-page-marker reveal">[ Комплектация ]</span>
    <h1 class="service-hero__title reveal">Комплектация интерьера</h1>
    <p class="service-hero__subtitle reveal">Подбираю материалы, мебель, свет и декор, организую закупки и контролирую поставки до полной готовности интерьера.</p>
    <div class="service-hero__meta reveal" aria-label="Параметры услуги">
      <span>подбор и закупки</span>
      <span>контроль поставок</span>
    </div>
    <a href="../contact.html" class="service-hero__cta reveal">Обсудить комплектацию</a>
  </div>
</section>
```

- [ ] **Step 3: Verify changes**

Open `portfolio/service/komplektaciya.html` in browser and verify:
1. Hero section displays background image with dark overlay
2. Text is readable over the image
3. Meta block shows "подбор и закупки" and "контроль поставок"
4. CTA button "Обсудить комплектацию" is visible
5. Compare with `interior-design.html` for visual consistency

- [ ] **Step 4: Test responsive behavior**

Test on mobile viewport (375px width):
1. Hero image scales properly
2. Text remains readable
3. Layout matches other service pages on mobile

- [ ] **Step 5: Commit changes**

```bash
git add portfolio/service/komplektaciya.html
git commit -m "feat: add hero image to kompletaciya service page"
```
