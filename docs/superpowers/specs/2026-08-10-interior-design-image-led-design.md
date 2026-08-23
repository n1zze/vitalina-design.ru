# Interior Design Image-Led Design

## Goal

Enhance `portfolio/service/interior-design.html` with a cinematic image-led presentation based on the approved Full-bleed direction and the visual/content structure observed on ALEART's design-project page.

## Scope

Primary target:

- `portfolio/service/interior-design.html`.

Shared visual reference:

- `portfolio/about.htm` for typography, palette, spacing, and editorial rhythm;
- `portfolio/assets/projects/zhk-euro/cover.jpg` for the hero image;
- `portfolio/assets/projects/zhk-vse-svoi/1.jpg` for the measurement-plan visual and `portfolio/assets/projects/zhk-euro/02.jpg` through `06.jpg` for the remaining deliverables.

Do not modify `about.htm`, the other service detail pages, service index cards, JavaScript behavior, or dependencies.

## Hero

Replace the current text-only hero presentation with a full-bleed cinematic hero while preserving the current title, description, CTA destination, price facts, 60-day scope fact, and sibling service navigation.

- Use `zhk-euro/cover.jpg` as the full-width visual surface.
- Add a controlled dark directional overlay so text remains readable without flattening the image.
- Place the wide title, description, price, 60-day fact, and CTA in a left-aligned content zone.
- Keep the `h1` wide enough for a maximum of 2-3 lines.
- Keep the sibling service navigation accessible and visually separated from the hero copy.
- Avoid copied ALEART text, bonuses, logo, or unrelated claims.

## Image-Led Deliverables

Replace the existing three scope rows with a visual `Что входит` presentation using six existing project images. Preserve the information from the three current rows inside the expanded descriptions, but do not keep the old list as a duplicate block:

- `zhk-vse-svoi/1.jpg` — Обмерные планы;
- `zhk-euro/02.jpg` — Планировочные решения;
- `zhk-euro/03.jpg` — Дизайн-концепт;
- `zhk-euro/04.jpg` — Рабочие чертежи;
- `zhk-euro/05.jpg` — 3D-визуализации;
- `zhk-euro/06.jpg` — Подбор материалов.

The section follows the reference composition as closely as the existing site allows:

- marker `[ Что входит ]`;
- heading `Вы получите`;
- horizontal six-item anchor navigation above the content;
- six large image/text blocks below it;
- alternating image/text sides on desktop;
- image first, then title and explanation on mobile.

Each item contains:

- one image with meaningful alt text;
- the deliverable name;
- a concise supported explanation;
- a visible focus state if the item is interactive; otherwise it remains a semantic article.

Desktop uses six alternating image/text rows with a consistent editorial measure, matching the reference's long-form presentation rather than a repeated card grid. Mobile becomes a vertical sequence of image/text blocks with no horizontal overflow.

## Remaining Sections

Retain the approved hybrid sequence after the image-led intro:

1. Hero;
2. Benefits / «Почему это работает»;
3. Image-led «Что входит» replacing the previous three-row scope list;
4. Pricing;
5. Process;
6. Projects;
7. Personal meeting and existing form.

Preserve all existing pricing rows, project links, form IDs, field validation, success/error states, and independent sibling-service navigation.

## Motion

- Hero image enters with a restrained scale/opacity transition.
- Deliverable images reveal sequentially using the existing `.reveal` observer.
- Deliverable images scale to `1.03` on hover/focus where the item is interactive.
- Project images retain their existing bounded hover/focus scale.
- `prefers-reduced-motion: reduce` disables transforms, delays, and transition durations.
- No new JS animation dependency is introduced.

## Responsive Rules

- Desktop: full-bleed hero and wide image-led deliverables composition.
- Tablet: image rail reflows without clipping; text remains readable.
- Mobile: hero content stacks over the image with a safe overlay; deliverables become one column.
- No horizontal overflow at 390px, 768px, or desktop widths.
- Focus states remain visible and touch targets remain usable.

## Constraints

- Preserve the current service facts and functionality.
- Use only existing local project images.
- Do not copy ALEART brand assets, exact copy, bonuses, or external images.
- Replace the existing three scope rows with the six image-led deliverables; preserve their factual content in the new descriptions.
- Do not change JavaScript files, dependencies, or other service pages.

## Verification

- Inspect at 1440px, 768px, and 390px.
- Verify hero image, overlay contrast, title wrapping, CTA visibility, and sibling navigation.
- Verify six deliverable images load with correct paths and meaningful alt text.
- Verify the six deliverable images, anchor navigation, pricing/projects/form counts, and existing links remain intact.
- Verify image hover/focus and reduced-motion states.
- Run the Impeccable detector on the target HTML/CSS and `git diff --check`.
