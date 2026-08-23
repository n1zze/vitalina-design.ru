# About Contact Form Editorial Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Refine the contact form on `portfolio/about.htm` into the approved warm editorial hero composition while preserving its existing submission and validation behavior.

**Architecture:** Keep the existing `.contact-form-hero` JavaScript contract, but place the form as a full-width sibling below the two-column heading block so the composition matches the approved reference. Consolidate the visual change in the existing contact-form section at the end of `skins/saparova/css/main.css`, using the current Manrope, terracotta accent, container width, and breakpoint conventions.

**Tech Stack:** Static HTML, CSS, existing vanilla JavaScript, Playwright for browser inspection, Impeccable detector.

## Global Constraints

- Preserve the existing FormSubmit endpoint, hidden fields, honeypot, phone mask, consent checkbox, validation, and success state.
- Preserve the current form fields and their accessible IDs/names.
- Do not change other pages or unrelated about-page sections.
- Keep the full-bleed photographic background and overlay treatment.
- Desktop uses a split heading and a three-column form row; tablet/mobile use a single-column stack.
- Use `Обсудить проект` as the preferred CTA wording.
- No horizontal scrolling at 1440px, 991px, 767px, or 390px.
- Honor `prefers-reduced-motion: reduce`.
- Do not add dependencies.

---

### Task 1: Update Form Copy and Accessible Names

**Files:**
- Modify: `portfolio/about.htm:449-473`
- Test: browser DOM assertions in the verification command from Task 3

**Interfaces:**
- Consumes: existing `.contact-form-hero__header`, `.contact-form-hero__body`, and `#aboutForm` structure.
- Produces: the same form IDs, names, hidden fields, checkbox, and submit event target with copy suitable for the new composition.

- [ ] **Step 1: Add explicit accessible labels without changing field identifiers**

Keep `id="hero-name"`, `id="hero-phone"`, `name="name"`, `name="phone"`, and `data-phone-input`. Add visually-hidden labels immediately before the corresponding inputs:

```html
<label class="visually-hidden" for="hero-name">Ваше имя</label>
<input type="text" name="name" id="hero-name" placeholder="Ваше имя" required class="contact-form-inline__input">
```

Use the equivalent `for="hero-phone"` label for the telephone field. Do not add visible labels that would change the reference composition.

Move the existing `<form id="aboutForm">` out of `.contact-form-hero__body` and place it immediately after `.contact-form-hero__content`, still inside `.contact-form-hero__wrapper`. Keep every hidden input, field, checkbox, success element, ID, name, and event-related attribute unchanged.

- [ ] **Step 2: Apply the approved short CTA and concise hint copy**

Change only the visible strings to:

```html
<h2 class="contact-form-hero__title">Давайте обсудим ваш проект</h2>
<p class="contact-form-hero__hint">Заполните форму, и я свяжусь с вами, чтобы обсудить задачу.</p>
<button type="submit" class="contact-form-inline__submit">Обсудить проект</button>
```

Keep the marker, consent copy, privacy link, success message, form action, and hidden metadata unchanged.

- [ ] **Step 3: Verify the HTML-only change is behavior-preserving**

Run:

```powershell
git diff -- portfolio/about.htm
```

Expected: only the explicit labels and the three approved visible copy strings differ; no form field name, ID, action, hidden input, checkbox, or success element is removed.

### Task 2: Refine the Contact Hero CSS

**Files:**
- Modify: `skins/saparova/css/main.css:11747-11996`
- Test: rendered screenshots at desktop/tablet/mobile widths in Task 3

**Interfaces:**
- Consumes: the unchanged `.contact-form-hero` DOM and existing page tokens (`Manrope`, `#c99471`, `.container` rhythm).
- Produces: the approved split heading, weighted three-column row, accessible focus states, and explicit responsive stack.

- [ ] **Step 1: Update the desktop hero geometry**

Keep the photographic layers and z-index order. Change the wrapper and content rules so the block has stable editorial spacing and the heading can split cleanly:

```css
.contact-form-hero {
  min-height: 0;
}

.contact-form-hero__wrapper {
  max-width: 1200px;
  padding: 72px 24px 64px;
}

.contact-form-hero__content {
  grid-template-columns: minmax(0, 1.1fr) minmax(280px, .9fr);
  gap: 72px;
  align-items: end;
}
```

The content must remain inside the existing max width and must not use viewport-relative widths that can overflow.

- [ ] **Step 2: Tune title and hint hierarchy**

Use a restrained title scale that resolves to two lines on desktop and remains legible on mobile:

```css
.contact-form-hero__title {
  max-width: 540px;
  font-size: clamp(2.25rem, 4.2vw, 3.6rem);
  line-height: 1.02;
  letter-spacing: -0.04em;
}

.contact-form-hero__hint {
  max-width: 340px;
  margin: 0 0 24px auto;
  font-size: 15px;
  line-height: 1.5;
}
```

Keep the marker treatment and the existing dark overlay, but ensure the title and hint remain readable over the image.

- [ ] **Step 3: Build the weighted desktop form row**

Replace the equal three-column rule with a weighted grid, increase control height, and retain the existing phone wrapper/flag behavior:

```css
.contact-form-inline {
  margin-top: 34px;
}

.contact-form-inline__row {
  grid-template-columns: minmax(0, 1.25fr) minmax(0, 1.1fr) minmax(190px, .95fr);
  gap: 12px;
  margin-bottom: 16px;
}

.contact-form-inline__input,
.contact-form-inline__submit {
  min-height: 56px;
}

.contact-form-inline__submit {
  width: 100%;
  padding: 0 22px;
  background: #c99471;
  color: #fff;
}

.contact-form-inline__submit:hover,
.contact-form-inline__submit:focus-visible {
  background: #b5835f;
}
```

Use `:focus-visible` for the CTA and retain an obvious accent outline for text and telephone inputs. Do not use a white CTA with white text.

- [ ] **Step 4: Make the consent and success states fit the new rhythm**

Keep consent below the row, but raise readability and prevent long text from forcing a wide layout:

```css
.contact-form-inline__consent {
  max-width: 920px;
  margin-top: 14px;
  font-size: 12px;
  line-height: 1.5;
}

.contact-form-inline__success {
  max-width: 100%;
  overflow-wrap: anywhere;
}
```

Do not alter the success element's `id` or the inline `display:none` behavior used by the existing script.

- [ ] **Step 5: Define explicit tablet and mobile collapse rules**

At `max-width: 991px`, stack the heading and form fields. Because the form is a sibling below the heading grid, reset its desktop top margin at this breakpoint. At `max-width: 767px`, reduce only wrapper padding/title size and keep the content order intact:

```css
@media (max-width: 991px) {
  .contact-form-hero__wrapper { padding: 56px 20px 48px; }
  .contact-form-hero__content {
    grid-template-columns: 1fr;
    gap: 28px;
  }
  .contact-form-hero__hint {
    max-width: 420px;
    margin: 0;
  }
  .contact-form-inline__row {
    grid-template-columns: 1fr;
    margin-top: 24px;
  }
}

@media (max-width: 767px) {
  .contact-form-hero__wrapper { padding: 44px 18px 40px; }
  .contact-form-hero__title { font-size: clamp(2rem, 10vw, 2.75rem); }
  .contact-form-inline__input,
  .contact-form-inline__submit { min-height: 54px; }
}
```

Confirm there is no competing rule later in the stylesheet that restores the equal-column or 44px-height layout.

- [ ] **Step 6: Preserve reduced-motion behavior**

Extend the existing reduced-motion block only if needed so all new transitions are disabled:

```css
@media (prefers-reduced-motion: reduce) {
  .contact-form-inline__input,
  .contact-form-inline__submit {
    transition: none;
  }
}
```

Do not add continuous animation, parallax, or scroll listeners.

### Task 3: Verify Visual and Functional Boundaries

**Files:**
- Inspect: `portfolio/about.htm`
- Inspect: `skins/saparova/css/main.css`
- Run: `C:\Users\nizze\.claude\skills\impeccable\scripts\detect.mjs`

**Interfaces:**
- Consumes: completed HTML/CSS changes from Tasks 1-2.
- Produces: evidence that the approved form renders responsively and the existing form contract remains intact.

- [ ] **Step 1: Start a local static server**

Run from the repository root:

```powershell
npx --yes http-server . -p 4173
```

Expected: the site is available at `http://127.0.0.1:4173/portfolio/about.htm`.

- [ ] **Step 2: Capture desktop, tablet, and mobile screenshots with Playwright**

Use the installed Playwright package with viewport sizes `1440x900`, `991x900`, `767x900`, and `390x844`. Inspect `.contact-form-hero` and assert:

```js
const form = page.locator('#aboutForm');
await expect(form).toBeVisible();
await expect(page.locator('#hero-name')).toHaveAttribute('name', 'name');
await expect(page.locator('#hero-phone')).toHaveAttribute('name', 'phone');
await expect(page.locator('.contact-form-inline__submit')).toHaveText('Обсудить проект');
await expect(page.locator('#aboutFormSuccess')).toBeAttached();
```

Expected: no horizontal overflow (`document.documentElement.scrollWidth <= document.documentElement.clientWidth`) at every viewport; the desktop CTA remains one line; the mobile row is single-column.

- [ ] **Step 3: Run the Impeccable detector once against changed UI files**

Run:

```powershell
node "C:\Users\nizze\.claude\skills\impeccable\scripts\detect.mjs" --json "portfolio/about.htm" "skins/saparova/css/main.css"
```

Expected: review the JSON for contrast, placeholder-as-label, overflow, and CTA issues. Fix only findings within the approved contact-form scope, then rerun once if a fix was required.

- [ ] **Step 4: Review the final diff and behavior contract**

Run:

```powershell
git diff --check
git diff -- portfolio/about.htm skins/saparova/css/main.css
```

Expected: the diff contains only the approved form markup/copy and contact hero CSS; JavaScript behavior, form action, hidden fields, IDs, names, and unrelated sections are unchanged.
