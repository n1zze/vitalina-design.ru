# About Hero Warm Editorial Design

## Direction

Implement the approved **Warm Editorial** direction for `portfolio/about.htm`, using the supplied reference's composition without copying its dark visual identity.

## Composition

- Keep the warm `#f8f5f0` VITALINA DESIGN background and existing `.container` rhythm.
- Upper hero block uses two columns:
  - left: existing author portrait;
  - right: author name, bio, and professional context.
- Remove the CTA from the hero.
- Place a separate full-width section directly after the hero with heading `Обо мне в цифрах`.
- The metrics section contains exactly four equal items:
  - `15+` / `Лет опыта`;
  - `120+` / `Реализованных проектов`;
  - `31` / `Помещение`;
  - `3300 м²` / `Площади`.

## Visual System

- Photo remains natural color and uses the existing image asset.
- Author name is the dominant typographic element.
- Bio and professional context use muted ink hierarchy.
- Metrics use terracotta values, muted labels, and thin beige separators.
- Desktop metrics use four columns; mobile uses a two-by-two grid.
- No dark full-bleed treatment, no new hero button, and no generic meta-labels.

## Motion

- Preserve existing hero reveal behavior.
- Metrics can reveal as a group or in a restrained sequence.
- Reduced-motion removes transforms and delays.

## Constraints

- Preserve the current portrait, author name, bio, proof section, approach section, page CTA outside hero, links, and JavaScript behavior.
- Do not modify other pages or service-card content.
- No new dependencies.
- No horizontal overflow at 1440px, 768px, or 390px.

## Verification

- Hero has exactly two visual columns on desktop and stacked order on mobile.
- Hero contains no CTA.
- Metrics section has the exact heading and four values/labels.
- Proof section remains after the metrics section and before `Подход`.
- Existing CTA outside hero remains present.
- Detector and targeted diff checks pass.
