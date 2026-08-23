# Service Consultation Form Design

## Goal

Restyle the contact forms on the three service detail pages in an ALEART-inspired inline conversion section with the exact primary heading:

`Оставьте заявку на бесплатную консультацию`

## Scope

Apply only to:

- `portfolio/service/3d-visualization.html`;
- `portfolio/service/author-supervision.html`;
- `portfolio/service/interior-design.html`.

Do not change the forms on `portfolio/about.htm` or `portfolio/contact.html`.

## Composition

Each service detail page keeps its current contact section ID and form behavior, but presents the section as:

- marker `[ Личная встреча ]`;
- exact heading `Оставьте заявку на бесплатную консультацию`;
- short supporting copy about discussing the task, service scope, and suitable format;
- two-column desktop layout;
- one-column mobile layout.

Desktop:

- left column: editorial copy and current service context;
- right column: white form panel;
- warm `#f8f5f0` section background;
- thin beige border, restrained radius, no heavy shadow;
- Primary terracotta submit button.

Mobile:

- heading and copy first;
- form panel below;
- fields remain full width and touch-friendly;
- no horizontal overflow.

## Form Contract

Preserve exactly:

- current form IDs;
- current field names and labels `Ваше имя` and `Телефон`;
- phone mask;
- consent checkbox and privacy link;
- validation behavior;
- success and error messages;
- existing action/method behavior and inline scripts.

Do not introduce a modal, new endpoint, new dependency, or new submission logic.

## Visual States

- inputs use warm paper surfaces and beige borders;
- focus border uses terracotta;
- validation error retains visible field/error text;
- success state remains clearly distinct;
- submit uses the shared Primary button role;
- `:focus-visible` remains visible;
- `prefers-reduced-motion` removes transforms/transitions without hiding state changes.

## Content Constraints

- The main heading must match exactly: `Оставьте заявку на бесплатную консультацию`.
- Supporting copy must remain concise and use existing service language; do not copy ALEART text or claims.
- Current service identity may be shown as a small contextual label, but no mandatory service sequence is implied.

## Verification

- Verify all three target pages have the exact heading once.
- Verify form IDs, fields, consent, privacy links, validation hooks, success elements, and inline scripts remain present.
- Inspect at 1440px, 768px, and 390px.
- Verify two-column desktop and one-column mobile layout, no overflow, readable text, and visible focus/error/success states.
- Run Impeccable detector on the three target HTML files and targeted `git diff --check`.
