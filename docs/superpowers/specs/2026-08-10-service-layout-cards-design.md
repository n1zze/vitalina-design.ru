# Service Layout And Cards Design

## Scope

Improve the visual composition of the service index and the three service detail pages:

- `portfolio/service.htm`;
- `portfolio/service/3d-visualization.html`;
- `portfolio/service/author-supervision.html`;
- `portfolio/service/interior-design.html`.

`portfolio/about.htm` is the layout reference and remains unchanged.

## Width And Section Rhythm

The service pages must use the same broad composition as `about.htm` rather than the current artificial `760px` axis.

- Markers, page headings, section headings, and sibling service navigation align to the full shared `.container` axis.
- At 1440px, the content follows the existing wide container behavior from `main.css`, not a centered 760px column.
- Supporting paragraphs may remain constrained to approximately 620px for readable line length, but their left edge follows the section axis.
- Scope list, pricing rows, project strip, and contact form retain their information architecture while using deliberate wide section composition.
- Mobile keeps the existing page padding, one-column order, and no horizontal overflow.

## Service Cards

The three existing service cards keep their current content and destinations:

- image source and alt text;
- service title;
- current price;
- current link;
- visible `Подробнее` label.

Only presentation changes. The card composition follows the supplied Inspaire reference:

- equal portrait cards in a three-column desktop grid;
- image fills the card with `object-fit: cover`;
- bottom dark overlay is a directional gradient, not uniform `brightness(.4)`;
- title and price sit in a readable lower content zone in white;
- `Подробнее` remains a visible compact control in the lower zone;
- a muted circular arrow control sits in the top-right corner;
- card corners stay restrained and architectural, without heavy shadows or decorative gradients.

On mobile, cards become one column with a stable portrait ratio and no horizontal overflow. Card markup must remain semantically link-based and keyboard accessible.

## Card Motion

Motion is purposeful and bounded:

- image scales from `1` to approximately `1.04` on hover/focus;
- overlay deepens slightly to protect text contrast;
- lower content shifts upward by a small amount and remains readable;
- arrow circle changes background/accent and rotates or translates subtly;
- transitions use one consistent ease and duration around 400-600ms;
- `:focus-visible` receives the same visual affordance as hover without requiring a pointer;
- `@media (prefers-reduced-motion: reduce)` disables transforms and uses instant state changes.

No GSAP dependency is introduced for this bounded interaction. Existing page reveal behavior remains unchanged.

## Detail Page Composition

The three detail pages retain their independent-service sibling navigation and current content journey:

`hero -> scope -> pricing -> projects -> contact form`.

No service is presented as a mandatory stage in another service. Existing titles, descriptions, prices, project links, forms, IDs, and scripts remain unchanged.

## Constraints

- Do not modify `portfolio/about.htm`.
- Do not change service-card content, image paths, names, prices, links, or the `Подробнее` text.
- Do not add dependencies or alter JavaScript behavior.
- Do not introduce sequential labels such as `01 -> 02 -> 03` for the three independent services.
- Preserve accessible link semantics, focus states, and touch targets.

## Verification

- Inspect all four pages at 1440px, 768px, and 390px.
- Verify section headings and markers share the broad content axis used by `about.htm`.
- Verify three card content tuples remain unchanged.
- Verify card image crop, overlay contrast, title/price/control visibility, hover, focus, and reduced-motion states.
- Verify no horizontal overflow and one-column mobile cards.
- Run the Impeccable detector on the four target HTML files and `git diff --check` on implementation files.
