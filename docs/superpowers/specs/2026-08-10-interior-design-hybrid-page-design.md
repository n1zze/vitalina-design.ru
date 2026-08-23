# Interior Design Hybrid Page Design

## Goal

Improve `portfolio/service/interior-design.html` using a hybrid of the current VITALINA DESIGN editorial system and the information architecture observed on `https://aleart-design.ru/design-project`.

The page should feel like a complete presentation of the design-project service, not a short price list. It must retain the current product facts, project links, form behavior, and independent-service navigation.

## Scope

Primary target:

- `portfolio/service/interior-design.html`.

Reference context:

- `portfolio/about.htm` for layout, typography, color, and authorial tone;
- ALEART design-project page for conversion sequence and content depth.

The three other service pages and `about.htm` are not redesigned in this task, although shared CSS additions must not regress them.

## Page Architecture

Keep the current service sibling navigation and add the following content sequence:

1. **Hero**
   - existing title and description;
   - existing CTA;
   - current design-project price and 60-day scope information;
   - an existing interior image used as visual context;
   - wide `.container` axis matching `about.htm`.

2. **Why This Works**
   - four editorial benefit rows with number, heading, and concise explanation;
   - content reuses supported ideas from `about.htm`: architectural approach, spatial geometry, centimeter-level detail, dialogue, and realizability;
   - no generic equal boxed cards.

3. **What Is Included**
   - six deliverables: measurement plans, planning solutions, design concept, working drawings, 3D visualizations, and material selection;
   - broad list/grid with thin rules and varied density;
   - existing three-item scope content remains present, while the new detail supports the complete service explanation without removing existing facts.

4. **Pricing**
   - preserve the existing three pricing rows, links, names, summaries, and prices;
   - give the full design-project row the strongest visual emphasis;
   - do not copy ALEART tariff names, claims, bonuses, or prices.

5. **Projects**
   - preserve the existing three project links, images, titles, and metadata;
   - compose one visual lead project with two supporting projects where the existing image assets allow it;
   - make this section the visual proof peak before the form.

6. **Process**
   - add a process presentation based on the existing verified process language in `about.htm`;
   - show the journey from first conversation and measurements through planning, concept, 3D, drawings, and handoff;
   - do not imply that the other two independent services are mandatory stages.

7. **Personal Meeting**
   - preserve the existing form, fields, IDs, validation, and success/error states;
   - add a short existing-tone explanation before the form describing the next conversation step.

## Visual Direction

- Use the wide `.container` composition from `about.htm`, not the old artificial 760px section axis.
- Keep Manrope, `#f8f5f0`, white surfaces, `#1a1714`, `#6b6560`, `#c99471`, and beige rules around `#d4c5b5`.
- Markers use the established bracket form, but only where they improve scanability.
- Hero uses a calm editorial split or wide visual block with readable text and no dense overlay copy.
- Benefits use numbered editorial rows rather than repeated card containers.
- Deliverables use a broad ruled grid/list; no generic icon-card bento is introduced.
- Pricing stays a readable list with one intentional featured row.
- Projects carry the strongest image scale and become the emotional peak.
- Do not copy ALEART branding, exact copy, logos, bonuses, icons, or images.

## Motion And Responsive Behavior

- Hero content uses a short staggered reveal using the existing reveal infrastructure.
- Benefit rows reveal sequentially on scroll.
- Project images use a restrained `scale(1.02-1.04)` hover/focus transition.
- Featured pricing uses color and border transitions only, without layout jumps.
- `prefers-reduced-motion: reduce` disables transforms and staggered motion.
- Desktop uses wide split/composite sections; tablet reflows splits; mobile becomes one linear reading path.
- Headings remain within 2-3 lines where practical; no horizontal overflow at 390px, 768px, or desktop widths.

## Content And Function Constraints

- Preserve current service title, description, price data, CTA destination, sibling links, pricing rows, project links, form fields, IDs, inline scripts, reveal classes, and success/error behavior.
- New text must be derived from supported content already present in `about.htm` or the current service page; do not invent unsupported claims.
- Do not alter `portfolio/about.htm` or the internal cards on `portfolio/service.htm`.
- Do not add JavaScript dependencies or change form submission behavior in this visual/content expansion.
- Keep the three services independent; no sequence language connects this page to 3D visualization or author supervision as required steps.

## Verification

- Inspect the page at 1440px, 768px, and 390px.
- Verify the section axis matches `about.htm` and no overflow occurs.
- Verify all existing pricing rows, project links, form IDs, scope items, and CTA destinations remain present.
- Verify new benefits, deliverables, and process sections are in the intended reading order.
- Verify hover/focus/reduced-motion states for project images and featured pricing.
- Run the Impeccable detector on the changed HTML and CSS targets.
- Run `git diff --check` on implementation files.
