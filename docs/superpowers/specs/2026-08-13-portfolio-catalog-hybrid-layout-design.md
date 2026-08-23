# Portfolio Catalog Hybrid Layout Design

## Direction

Bring the portfolio catalog closer to the approved Aleart-inspired hybrid full-width system without using masonry. The catalog remains a grid-led editorial surface, while project detail pages use full-bleed imagery for hero and comparison sections.

## Shared Hybrid System

- Full-bleed surfaces: project detail hero, project gallery imagery, floorplan comparison, contact split image/form.
- Wide editorial container: `max-width: 1400px` for project grids, metadata, and section headings.
- Reading container: `max-width: 620-720px` for long descriptions, FAQ, process copy, and supporting text.
- Shared header: Manrope, identical logo scale, nav font sizing, line height, and gaps across about, service, catalog, and project detail.
- Preserve current warm neutral palette, terracotta accent, thin rules, and restrained motion.

## Portfolio Catalog

- Add a catalog-specific modifier to `portfolio/index.htm`:

```html
<main class="projects-page projects-catalog-page">
```

- Keep project detail pages on their own detail-specific styling path.
- Remove the project-detail background image/overlay/min-height treatment from the catalog intro.
- Catalog intro is a compact left-aligned editorial block with a small marker, title, and short description.
- Replace the current equal three-column catalog with an editorial CSS Grid, not CSS columns/masonry:
  - first project is a featured two-column card;
  - remaining projects occupy a balanced two/three-column rhythm;
  - card image ratios may vary by composition but each item remains a predictable grid cell;
  - no empty cells are inserted.
- Keep project title, metadata, links, focus state, image alt text, and hover scale behavior.
- Mobile collapses to one column with explicit spacing and no horizontal overflow.

## Project Detail Floorplan

- Keep project detail hero and gallery as full-width image-led surfaces.
- Change `.project-floorplan` from a centered narrow block to a full-width section.
- Align marker, title, and supporting text to the shared `1400px` content edge.
- Align heading text left rather than centered.
- Let `.project-floorplan__slider` use the full available viewport width.
- Keep the existing comparison library and before/after images unchanged.
- Mobile uses a full-width comparison with small safe side padding and preserves touch interaction.

## Constraints

- Do not use masonry layout.
- Do not change project data, image sources, routes, or gallery behavior.
- Do not add dependencies.
- Preserve responsive behavior at `1440px`, `991px`, `767px`, and `390px`.
- Preserve keyboard focus, hover feedback, reduced-motion behavior, and accessible labels.

## Verification

- Catalog intro has no background image or dark overlay.
- Catalog uses CSS Grid and contains no CSS `columns` rule.
- Catalog header computed typography matches about/service header.
- Project detail floorplan is full-width, left-aligned, and no longer centered as a narrow card.
- Project detail hero remains image-backed and unchanged in content.
- No horizontal overflow at all target viewports.
- Run `git diff --check` and the Impeccable detector on changed UI files.
