# About, Contact, And Project Editorial Design

## Direction

Extend the existing VITALINA DESIGN warm editorial system across the about hero, the contact page form block, and project detail introductions. Use the supplied reference images as composition guides while preserving current routes, content meaning, form behavior, and project galleries.

## Shared Visual System

- Use the existing Manrope family for display and body hierarchy.
- Keep the warm neutral palette, terracotta accent, thin separators, and restrained corner radius already used by the portfolio.
- Use `80px` section rhythm on desktop and `64px` on mobile where the page's existing responsive rules establish those values.
- Use a maximum content width of `1400px` for broad editorial blocks.
- Keep mobile layouts single-column with no horizontal overflow.
- Avoid new dependencies and avoid introducing a second visual system.

## About Hero

- Preserve `portfolio/hero/designer-photo.png` as the existing about portrait.
- Compose desktop as image-left and text-right, following Image 1.
- Give the text column a thin top rule, author heading, short lead/bio copy, and a clear about CTA.
- Keep the existing page content and link destination unless the current copy is factually inconsistent.
- Mobile stacks the portrait above the text and keeps the CTA reachable without excessive whitespace.
- Preserve existing reveal behavior and reduced-motion support.

## Contact Split Block

- Keep `portfolio/contact.html` as an inline page block, not a modal.
- Use `portfolio/hero/hero-photo.png` as the left image panel.
- Keep the form on a white right panel with a large heading, supporting sentence, visible labels, two-column name/phone row, full-width secondary rows, CTA, consent, inline errors, and success state.
- Preserve the existing FormSubmit endpoint, hidden fields, honeypot, IDs, field names, phone mask, validation, and success behavior.
- At mobile widths, stack the image above the form and keep all controls full width.

## Project Detail Intro

- Apply the composition through `portfolio/assets/projects.css` so all project detail pages receive the same treatment.
- Left-align the project title and metadata rather than centering them.
- Use a quiet metadata grid for the existing project facts without inventing project numbers, years, statuses, or locations.
- Keep the gallery as the dominant visual element below the intro.
- Add generous whitespace between metadata and the first gallery image, following Image 3.
- Collapse metadata to one column on small screens.

## Page Improvements

- Correct the about portrait alt text from the stale surname to `Виталина Ромашкевич`.
- Improve unclear metrics labels without inventing facts: use `Помещений` and clarify the area label according to the existing number's meaning.
- Keep the metrics heading semantically present and align its typography with the page's Manrope system if made visible.
- Avoid an empty review modal image element before a real source is available; populate it only when opening the modal or use a non-image fallback element.
- Update the footer copyright year from the stale fixed value when appropriate.
- Scope legacy global form rules so they cannot override the new editorial inputs.

## Verification

- Render about, contact, one representative project detail, and a second project detail using desktop and mobile viewports.
- Verify image paths, headings, section spacing, and metadata alignment.
- Verify no horizontal overflow at `1440px`, `991px`, `767px`, and `390px`.
- Verify contact form submission contract and phone mask remain unchanged.
- Run `git diff --check` and the Impeccable detector on all changed UI files.
