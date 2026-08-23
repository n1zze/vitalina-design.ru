# Service Consultation Form Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans (recommended). Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Restyle the contact sections on the three service detail pages into one ALEART-inspired inline consultation form with the exact heading `Оставьте заявку на бесплатную консультацию`.

**Architecture:** Add the same small editorial intro block before each existing form and use scoped CSS in `contact.css`/`main.css` for the two-column desktop panel and mobile stack. Keep all current form markup, IDs, fields, validation, inline scripts, and success/error states intact.

**Tech Stack:** Static HTML, existing `main.css`/`contact.css`, Manrope, existing vanilla JS, Playwright 1.62.1, Impeccable detector.

## Global Constraints

- Apply only to `portfolio/service/3d-visualization.html`, `portfolio/service/author-supervision.html`, and `portfolio/service/interior-design.html`.
- Do not change forms on `portfolio/about.htm` or `portfolio/contact.html`.
- Preserve current form IDs, field names/labels, phone mask, consent/privacy link, validation, success/error messages, action/method behavior, and inline scripts.
- Do not introduce a modal, new endpoint, new dependency, or new submission logic.
- Main heading must match exactly: `Оставьте заявку на бесплатную консультацию`.
- Keep current service identity contextual but do not imply a service sequence.
- No horizontal overflow at 1440px, 768px, or 390px.

---

### Task 1: Add Shared Consultation Form Intro Markup

**Files:**
- Modify: `portfolio/service/3d-visualization.html:204-230`
- Modify: `portfolio/service/author-supervision.html:204-230`
- Modify: `portfolio/service/interior-design.html:248-280`

**Interfaces:**
- Consumes: Existing `.contact-section`, `.section-title`, `.contact-form`, and current service title.
- Produces: Consistent `.service-consultation-layout` and exact consultation heading while preserving the existing form.

- [ ] **Step 1: Wrap each existing contact section content**

  Keep `id="contact-form"` on the section and add a `.service-consultation-layout` wrapper around the heading/form content. Do not move or rename the form.

- [ ] **Step 2: Replace only the section heading text**

  Use this exact intro structure before the existing form:

  ```html
  <div class="service-consultation-intro">
    <span class="service-page-marker reveal">[ Личная встреча ]</span>
    <h2 class="service-consultation-title reveal">Оставьте заявку на бесплатную консультацию</h2>
    <p class="service-consultation-copy reveal">Обсудим вашу задачу, состав работ и подходящий формат проекта.</p>
  </div>
  ```

  Remove the old visible section title only; keep all form content and behavior unchanged.

- [ ] **Step 3: Add contextual service label**

  Add a small non-interactive label inside the intro using the current page identity:

  ```html
  <span class="service-consultation-service">Дизайн-проект</span>
  ```

  Use the matching service name on the other two pages. Do not add sequential numbers or imply a required service order.

- [ ] **Step 4: Verify form contract before styling**

  Run Playwright across all three pages and assert one `#contactForm`, the existing field IDs, one consent checkbox, existing error elements, one success element, and the exact new heading once per page.

---

### Task 2: Style ALEART-Inspired Inline Form Panel

**Files:**
- Modify: `skins/saparova/css/contact.css` after existing service/detail contact rules

**Interfaces:**
- Consumes: `.service-consultation-layout`, `.service-consultation-intro`, existing `.contact-form--single`.
- Produces: Warm two-column desktop form section and one-column mobile form section.

- [ ] **Step 1: Create desktop two-column composition**

  Use a grid aligned to the existing page container:

  ```css
  .service-consultation-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(420px, .8fr);
    gap: 72px;
    align-items: center;
  }
  ```

  The intro remains open on the warm background; the form becomes a white panel with a 1px beige border, restrained 3px radius, and no heavy shadow.

- [ ] **Step 2: Style the exact heading and copy**

  Use a light Manrope heading with a wide readable measure, contextual service label in terracotta/muted uppercase, and supporting copy in `#6b6560`. Keep heading within 2-3 lines on desktop and natural wrapping on mobile.

- [ ] **Step 3: Style the existing form panel**

  Keep current field markup and selectors. Apply form panel padding, warm input surfaces, beige borders, terracotta focus border, visible error state, current success state, and shared Primary submit button. Do not hide or restructure fields.

- [ ] **Step 4: Add responsive stack**

  At `max-width: 900px`, switch to one column, intro first and form panel second. At 390px maintain 44px+ input/button targets, readable copy, and no horizontal overflow.

---

### Task 3: Add Motion And Accessibility States

**Files:**
- Modify: `skins/saparova/css/contact.css`

**Interfaces:**
- Consumes: Existing `.reveal` observer and form focus/error/success classes.
- Produces: Restrained intro/panel reveal and complete interactive states.

- [ ] **Step 1: Add bounded reveal**

  Use existing `.reveal` for intro and form panel with no new JS. Avoid delaying form availability; the panel must be usable after it enters the viewport.

- [ ] **Step 2: Preserve focus/error/success visibility**

  Confirm labels, inputs, errors, checkbox, submit, and success message remain visible and readable. Focus must use terracotta border/outline; errors must remain distinct from neutral text.

- [ ] **Step 3: Add reduced-motion override**

  Under `@media (prefers-reduced-motion: reduce)`, remove new transforms/delays while keeping all content and state colors.

---

### Task 4: Verify Three Forms And Protected Pages

**Files:**
- Test: ephemeral Node/Playwright script; no production test file

**Interfaces:**
- Consumes: Three updated service detail pages and untouched `about`/`contact` pages.
- Produces: Evidence for exact copy, form contract, responsive composition, and regression safety.

- [ ] **Step 1: Verify exact headings and form contracts**

  Assert each service page has exactly one heading with `Оставьте заявку на бесплатную консультацию`, one form with its original ID, original field IDs, consent/privacy link, error elements, success element, and inline script.

- [ ] **Step 2: Verify responsive layout**

  At 1440px, 768px, and 390px assert desktop two-column / mobile one-column layout, no overflow, readable heading, visible CTA, and preserved service content above the form.

- [ ] **Step 3: Verify interaction states**

  Focus representative fields/button, trigger browser-visible required validation without submitting externally, and assert focus/error styles remain visible. Verify reduced-motion removes transitions.

- [ ] **Step 4: Run final mechanical checks**

  ```bash
  node C:\Users\nizze\.claude\skills\impeccable\scripts\detect.mjs --json portfolio/service/3d-visualization.html portfolio/service/author-supervision.html portfolio/service/interior-design.html
  git diff --check -- skins/saparova/css/contact.css skins/saparova/css/main.css portfolio/service/3d-visualization.html portfolio/service/author-supervision.html portfolio/service/interior-design.html
  ```

  Confirm `about.htm`, `contact.html`, JS files, dependency manifests, form action/method, and navigation destinations were not changed.
