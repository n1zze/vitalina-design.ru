# About Hero Two-Column Metrics Design

## Goal

Refine the `portfolio/about.htm` hero to match the approved reference composition: a two-column author block followed by a full-width horizontal metrics row.

## Hero Composition

- Keep the existing warm VITALINA DESIGN visual world and `.container` rhythm.
- Remove the CTA from the hero completely.
- Desktop upper block uses two columns:
  - left: existing author portrait;
  - right: author name, bio, professional context, and existing editorial eyebrow.
- The metrics row sits below both columns and spans the full container width.
- Mobile order is portrait -> author copy -> metrics row.

## Metrics

Use exactly four metrics:

1. `15+` — `лет опыта`;
2. `120+` — `реализованных проектов`;
3. `31` — `помещение`;
4. `3300 м²` — `площади`.

Metrics use equal columns, thin beige separators, terracotta values, and muted labels. The row is a supporting proof layer and must not compete with the author name.

## Rhythm And States

- Hero height and vertical padding must remain compatible with the following proof/approach sections.
- Desktop keeps the photo and copy visually balanced at one height.
- Mobile collapses the metrics to a two-column grid if four columns are not readable.
- Preserve the current portrait, author name, bio, stats animation hooks, and section order except for removing the hero CTA and moving/replacing the stats structure.
- Keep no horizontal overflow at 1440px, 768px, or 390px.
- Existing reveal motion remains; reduced-motion removes transforms and delays.

## Constraints

- Do not invent or alter the four metric values or labels.
- Do not add a hero button or replace it with another CTA.
- Do not change the about proof section, other pages, JavaScript, or dependencies.
- Keep Manrope, warm palette, thin rules, and editorial typography.

## Verification

- Verify hero contains no CTA link/button.
- Verify exactly four metrics with exact values and labels.
- Verify two-column desktop, stacked mobile layout, balanced alignment, and no overflow.
- Verify stats/reveal and reduced-motion states.
- Run Impeccable detector and targeted `git diff --check`.
