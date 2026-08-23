# Design: Komplektaciya Hero Image

## Problem

The "Komplektaciya" (fitout) service page lacks a hero background image, unlike all other service pages:

| Service Page | Hero Image | CSS Class |
|--------------|------------|-----------|
| Interior Design | `zhk-euro/cover.jpg` | `service-design-hero` |
| 3D Visualization | `zhk-moskva/cover.jpg` | `service-design-hero` |
| Author Supervision | `private-house-krd/cover.jpg` | `service-design-hero` |
| **Komplektaciya** | **none** | `service-hero--fitout` |

This creates visual inconsistency across the services section.

## Solution

Replace the current hero section with the unified `service-design-hero` pattern used by other services.

### Image Selection

- **Project:** `zhk-nebo` (75 m², design-project + author supervision)
- **Image:** `../assets/projects/zhk-nebo/cover.jpg`

### HTML Changes

**File:** `portfolio/service/komplektaciya.html`

**Current hero (lines 42-49):**
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

**New hero:**
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

### Changes Summary

1. **Outer wrapper:** Add `hero-section` class, change `service-hero--fitout` to `service-design-hero`
2. **Image block:** Add `service-design-hero__image` with `zhk-nebo/cover.jpg`
3. **Overlay block:** Add `service-design-hero__overlay` for dark overlay
4. **Content wrapper:** Change `service-hero__inner` to `service-design-hero__content`
5. **Meta block:** Add `service-hero__meta` with two parameters

### CSS

No CSS changes needed — all styles for `service-design-hero` already exist in `skins/saparova/css/main.css`.

## Scope

- **Single file change:** `portfolio/service/komplektaciya.html`
- **Lines affected:** 42-49 (hero section)
- **Estimated effort:** 5 minutes

## Verification

1. Open `portfolio/service/komplektaciya.html` in browser
2. Confirm hero section displays background image with overlay
3. Confirm text is readable over the image
4. Compare with other service pages for visual consistency
5. Test responsive behavior on mobile viewport
