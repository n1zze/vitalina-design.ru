# About Page FAQ And Layout Design

## Scope

Update `portfolio/about.htm` and its shared styles to:

- clarify the hero copy;
- replace the current three hero metrics with the approved two metrics;
- add a reference-inspired FAQ section;
- correct the page layout rhythm so the new section belongs to the existing visual system on desktop and mobile.

## Content

Hero copy:

- Main identity: `Виталина Ромашкевич`.
- Lead: `Виталина Ромашкевич — дизайнер интерьера, архитектор, который ведёт ваш проект.`
- Supporting paragraph keeps the existing architectural approach and client-lifestyle message.

Hero metrics:

- `15+` / `лет опыта в проектировании интерьеров`.
- `120+` / `реализованных проектов под ключ`.

FAQ questions:

1. `Что входит в дизайн-проект?`
2. `Сколько времени занимает работа над проектом?`
3. `Сколько стоит дизайн-проект?`
4. `Работаете ли вы удалённо?`
5. `Можно ли заказать авторский надзор отдельно?`
6. `Что делать, если я не знаю, какой стиль интерьера выбрать?`

Answers use existing site facts where available and avoid new unsupported promises. Text follows the current concise, calm Russian copy style.

## Layout

The page keeps its current visual language: Manrope, warm off-white/white section alternation, muted beige rules, and terracotta accent.

FAQ placement is after testimonials and before `CMS:about:end`, so the page journey is: proof through reviews, practical objections through FAQ, then contact/footer actions.

Desktop layout uses the existing `.container` width and a 12-column mental grid:

- section title/intro occupies the first 4 columns;
- accordion occupies the remaining 8 columns;
- no card wrapper or nested panel is introduced;
- each question is a full-width row separated by 1px rules;
- question text, plus icon, answer, and focus state align to the same content edges.

Mobile layout collapses to one column:

- section heading comes first;
- accordion follows at full container width;
- question rows have at least 44px interactive height;
- long answers wrap naturally without horizontal overflow;
- the plus icon remains optically aligned to the question baseline.

Use native `details`/`summary` elements. The first question is open by default. No JavaScript is required for FAQ interaction.

## Accessibility And UX

- Keep heading order valid: FAQ uses one `h2`; each question is a `summary` rather than a heading control.
- Preserve visible keyboard focus for summaries.
- Hide decorative divider semantics with CSS only; do not remove question text from the accessible name.
- Ensure the open state is communicated by the native `details` semantics and a rotated plus icon is supplementary only.
- Keep the CTA after the metrics visible and visually distinct.
- Do not let the FAQ or hero introduce horizontal scrolling at 390px, 768px, or desktop widths.

## Verification

- Inspect the page at 1440px, 768px, and 390px.
- Verify hero has exactly two metrics and one divider.
- Verify FAQ has six accessible `details` controls and one open item.
- Verify the accordion rows align to the container and do not create nested card clutter.
- Verify CTA, focus states, and expanded answers are usable by keyboard.
- Run `git diff --check` and a Playwright DOM/layout smoke check.
