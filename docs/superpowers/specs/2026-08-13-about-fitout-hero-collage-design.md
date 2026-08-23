# About Fit-Out Hero And Service Design

## Direction

Extend the approved hybrid full-width visual system with a dark image-led About hero, repair missing project hero assets, and add a new "Комплектация" service without breaking the existing service detail rhythm.

## About Hero

- Replace the current light contained About hero treatment with a full-width warm editorial section inspired by `soloveva-designer.ru`, while preserving the site's light theme.
- Use the existing warm `#f8f5f0` surface as the hero background, dark ink typography, muted beige rules, and terracotta as the only accent.
- Use the existing local portrait assets:
  - `portfolio/hero/designer-photo.png` as the primary tall portrait;
  - `portfolio/hero/hero-photo.png` as the secondary collage portrait.
- Use a left editorial text column with:
  - a restrained marker;
  - a large Manrope heading;
  - the existing factual lead and bio copy;
  - a text CTA with a moving arrow, not a filled button.
- Use a right image collage with one dominant portrait and one smaller offset portrait, keeping the source portraits in natural color.
- Add two editorial information blocks below the main copy:
  - education: use the confirmed education fact supplied for VITALINA DESIGN;
  - projects: use the existing factual value `120+` / `Реализованных проектов`.
- Education block copy:
  - `Астраханский государственный институт`;
  - `Архитектура и строительство`.
- Do not add a graduation year because it was not provided.
- Animate the hero as one authored sequence:
  - text column reveals with a short opacity/translate transition;
  - dominant portrait appears first, secondary portrait follows with a restrained delay;
  - education and project blocks reveal after the main copy;
  - all transforms and delays collapse under `prefers-reduced-motion: reduce`.
- Keep the collage full-width within the hero, preserve aspect ratios, and avoid fake image placeholders.
- Mobile stacks text and portraits into one column with no horizontal overflow.
- Preserve existing navigation, CTA destination, reveal behavior, and reduced-motion support.

## Project Hero Fallbacks

- Change the missing hero asset for `portfolio/privateinterior/zhk-ekaterininskij-park.html` to its existing `cover.jpg`.
- Change the missing hero asset for `portfolio/privateinterior/zhk-euro.html` to its existing `cover.jpg`.
- Keep the existing dark overlay, title, metadata, and full-width project hero behavior.
- Do not change project gallery sources or metadata.

## Render Comparison

- Keep `.render-vs-real__slider.reveal.visible` constrained to `max-width: 1400px` and centered.
- Preserve full available width on mobile.
- Keep the existing Juxtapose interaction and source images unchanged.

## Featured Projects Link

- Restyle the link inside `.featured-header` as an editorial text link inspired by the reference interaction.
- Remove filled button/card treatment.
- Keep the current `index.htm` destination.
- Add a separate arrow element that moves right/up on hover and keyboard focus.
- Use the existing Font Awesome icon family or a CSS-safe existing icon, sized approximately `18px`.

## New Service: Комплектация

- Add a fourth card to `portfolio/service.htm` with the title `КОМПЛЕКТАЦИЯ`.
- Use `portfolio/assets/projects/zhk-euro/cover.jpg` as the card/visual asset.
- Link the card to `portfolio/service/komplektaciya.html`.
- Keep the existing card image, overlay, typography, spacing, hover, and focus system.
- Use the same arrow treatment as the other service cards, with an `18px` icon and transform-only hover motion.

## Комплектация Detail Page

- Create `portfolio/service/komplektaciya.html` using the established service detail structure from `3d-visualization.html`, `author-supervision.html`, and `interior-design.html`.
- Hero heading: `Комплектация интерьера`.
- Explain selection, procurement, supplier coordination, logistics, delivery, and acceptance.
- Include a `[ Что входит ]` section with concise service items.
- Include a `[ Как проходит работа ]` section with four stages.
- Include a project strip using existing project assets.
- Include the existing consultation/contact form pattern with preserved validation and privacy link.
- Do not invent a price; use a contact CTA such as `Обсудить комплектацию`.
- Preserve the service-page typography, spacing, marker, header, footer, and responsive tokens.

## Constraints

- No new dependencies.
- Use only existing local image assets.
- Preserve existing routes, form endpoints, form IDs/names, phone mask, and validation.
- No horizontal overflow at `1440px`, `991px`, `767px`, or `390px`.
- Honor reduced motion.

## Verification

- Both named project heroes load an existing `cover.jpg` source.
- About hero renders both collage portraits at desktop and a stacked mobile composition.
- Render slider width is at most `1400px` desktop.
- Featured projects link remains functional and arrow moves on hover/focus.
- Service catalog contains four cards and the new route resolves.
- Комплектация detail page uses the same header, footer, markers, section rhythm, forms, and mobile behavior as other services.
- Run Playwright checks, `git diff --check`, and Impeccable detector on all changed UI files.
