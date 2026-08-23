# About Contact Form Editorial Design

## Direction

Implement the approved warm editorial treatment for the contact form on `portfolio/about.htm`, using the supplied reference as a compositional guide while preserving the existing VITALINA DESIGN visual rhythm.

## Scope

- Preserve the existing FormSubmit endpoint, hidden fields, honeypot, phone mask, consent checkbox, validation, and success state.
- Preserve the current form fields and their accessible IDs/names.
- Modify the contact form hero presentation and responsive behavior.
- Small copy refinements are allowed, but the form must retain the same purpose and action.
- Do not change other pages or unrelated about-page sections.

## Composition

- Keep the full-bleed photographic background and overlay treatment.
- Align the inner content to the existing page container rhythm (`24-25px` side padding and the current max width).
- Use a two-part heading area on desktop:
  - left: `[ Личная встреча ]` marker and a two-line project discussion heading;
  - right: a short explanatory sentence.
- Place the form below the heading area as one desktop row with three columns:
  - wider name field;
  - phone field with the existing Russian flag affordance;
  - primary CTA.
- Keep the privacy consent directly below the row and allow it to span the form width.
- Keep success feedback in the same content region without causing horizontal overflow.

## Visual System

- Continue the page's warm editorial palette rather than introducing a new color family.
- Use off-white form controls against the darkened photo, with a terracotta CTA as the single strong accent.
- Keep the title in the existing Manrope family and preserve the page's restrained weight and tracking.
- Use modest corner radii consistent with the existing portfolio controls.
- Make the CTA label short enough to remain on one line: `Обсудить проект` is the preferred wording.
- Keep placeholder text readable against the field background; use visible focus rings with the existing accent color.
- Keep consent text at a readable size and maintain a distinct, underlined privacy-policy link.

## Responsive Behavior

- Desktop: heading split and three-column form row.
- Tablet: heading stacks and form fields become a single column when the three-column row no longer has comfortable widths.
- Mobile: reduce wrapper padding and title scale only as needed; preserve the order marker, title, explanation, name, phone, CTA, consent.
- No horizontal scrolling at 1440px, 991px, 767px, or 390px.

## Motion and Accessibility

- Preserve the existing reveal behavior elsewhere on the page.
- Use only restrained transitions for field focus and CTA hover/active states.
- Honor `prefers-reduced-motion: reduce` by removing transitions and transforms.
- Keep semantic form labels/accessible names intact. If visual labels remain placeholder-based, add non-visible labels rather than relying on placeholder text alone.
- Ensure CTA, controls, placeholder, consent, and focus states meet WCAG AA contrast requirements.
- Keep keyboard focus visible and do not remove native checkbox interaction.

## Verification

- Inspect the rendered form at desktop, tablet, and mobile widths.
- Verify the heading and form remain within the existing page container rhythm.
- Verify the CTA remains a single line on desktop.
- Verify the phone mask, consent validation, honeypot, FormSubmit request, and success state are unchanged.
- Run the Impeccable detector against the changed UI files.
- Check the final diff for changes limited to the approved form scope.
