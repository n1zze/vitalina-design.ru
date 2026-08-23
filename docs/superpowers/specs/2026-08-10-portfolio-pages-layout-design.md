# Portfolio Pages Layout Design

## Scope

Align the visual rhythm of the portfolio contact and service pages with the incumbent design language established by `portfolio/about.htm`.

In scope:

- `portfolio/contact.html`;
- `portfolio/service.htm` heading shell and surrounding spacing only;
- `portfolio/service/3d-visualization.html`;
- `portfolio/service/author-supervision.html`;
- `portfolio/service/interior-design.html`.

`portfolio/about.htm` remains the visual reference and is not changed.

The service cards in `portfolio/service.htm` are explicitly out of scope. Their HTML, images, prices, links, overlay presentation, hover behavior, and functionality must remain unchanged.

## Visual Direction

Preserve the existing warm editorial system from `about.htm`:

- Manrope for headings and interface text;
- warm off-white `#f8f5f0`, white surfaces, ink `#1a1714`, muted text `#6b6560`;
- terracotta accent `#c99471`;
- thin beige rules around `#d4c5b5`;
- light, spacious headings with deliberate contrast between compact groups and generous section spacing;
- uppercase, tracked section markers in the form `[ Раздел ]`.

No new visual world, content claims, imagery, or interaction model is introduced.

## Page Treatment

### Contact

Keep the current DOM order and all contact data, forms, map, anchors, links, validation, reveal states, and floating contact controls.

- Replace the legacy centered category-title treatment with an editorial page introduction aligned to the shared container.
- Use section markers for the page introduction and existing major contact groups.
- Normalize container width, section spacing, heading scale, line-height, dividers, form surfaces, and responsive collapse.
- Retain the current two contact columns, consultation form, map, project form, and footer order.

### Service Index

Keep the service card grid exactly as implemented. Change only the page title shell and the spacing immediately before and after the unchanged grid.

- Align the page heading to the same marker and light heading hierarchy as `about.htm`.
- Remove no service content and do not alter card-level selectors or states.
- Preserve the existing responsive card behavior.

### Service Details

Keep each detail page's content and journey unchanged:

`hero -> scope -> pricing -> projects -> contact form`.

- Standardize the hero eyebrow as a bracketed service marker while preserving the service number and label meaning.
- Align `h1` and section `h2` typography, max-widths, section padding, rules, and CTA states with `about.htm`.
- Preserve all scope list items, pricing rows, project images, links, form fields, validation, anchor IDs, and reveal behavior.
- Keep the current numbered divider-list and pricing-row structures; do not replace them with cards.

## Responsive Rules

- Desktop uses the existing shared container and generous 80-96px section cadence.
- Mobile uses the existing single-column order with 48-64px section cadence.
- No horizontal overflow at 390px, 768px, or wide desktop widths.
- Visual order must continue to match DOM and keyboard order.
- Interactive elements retain visible focus states and comfortable touch targets.
- Long Russian labels and descriptions wrap naturally without clipping.

## Implementation Boundary

Prefer scoped CSS additions or overrides in the existing stylesheet structure. Use minimal HTML edits only for section markers or page-shell hooks where CSS cannot target the intended role safely. Do not modify JavaScript behavior, form submission, navigation destinations, or factual copy.

## Verification

- Inspect all five target pages at 1440px, 768px, and 390px.
- Confirm `about.htm` remains unchanged.
- Confirm service cards in `service.htm` remain unchanged in content and behavior.
- Confirm all detail-page anchors, links, forms, reveal states, and floating controls remain functional.
- Run the Impeccable layout detector over all changed targets.
- Run `git diff --check` and a Playwright DOM/layout smoke check.
