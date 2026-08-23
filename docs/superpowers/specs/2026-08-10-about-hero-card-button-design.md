# About Hero And Service Card Button Design

## Scope

Two focused visual improvements:

- fix the `Подробнее` control inside service cards on `portfolio/service.htm`;
- refine the hero of `portfolio/about.htm` and add a compact proof accent immediately after it.

Shared reference remains the current VITALINA DESIGN editorial system.

## Service Card Button Fix

Keep the existing service card content, image, href, title, price, and visible `Подробнее` label.

- use `border-radius: 3px`, not a pill radius;
- center the label horizontally and vertically with flex alignment;
- preserve the contextual ghost treatment over the image;
- keep minimum touch height `44px`;
- preserve hover/focus color change and visible focus outline;
- do not change the parent anchor or add JavaScript.

## About Hero Refinement

Keep the current split hero structure, portrait, name, bio, stats, and CTA, but refine hierarchy:

- reduce desktop hero height by approximately 15-20% through tighter vertical spacing;
- keep the portrait and content on a clear shared vertical axis;
- keep the author name as the dominant visual element;
- reduce the visual weight of `15+ / 120+` stats so they support rather than compete with the name;
- keep the CTA as the primary terracotta accent;
- reduce mobile gaps so the hero reaches the next section sooner without hiding content;
- preserve the current readable typography and warm palette.

## Proof Accent After Hero

Add a compact proof section directly after the hero and before `Подход`:

- use the existing render/realization imagery already used by `about.htm`;
- show a restrained image pair or visual comparison rather than a full duplicate slider;
- include a concise existing-tone statement that visualization is tied to a real interior result;
- include a text link `Посмотреть проекты` to the existing projects page;
- avoid new unsupported claims or repeated large statistics;
- keep the proof accent visually quieter than the hero but stronger than a simple text divider.

## Motion And Responsive

- service card `Подробнее` uses only bounded hover/focus transition;
- hero elements keep existing reveal behavior without inherited CTA delay;
- proof imagery may use a subtle scale/fade reveal;
- reduced-motion disables transforms and delays;
- desktop preserves split hero composition;
- mobile stacks photo, author content, stats, CTA, then proof accent without overflow.

## Constraints

- Preserve all existing text, links, form behavior, images, stats, CTA destination, and section order outside the new proof accent.
- Do not change service-card names, prices, image paths, or hrefs.
- Do not change service detail pages or shared behavior unrelated to these targets.
- Do not add dependencies or modify JavaScript logic.

## Verification

- Verify `Подробнее` text is centered and radius is `3px` at 1440/768/390.
- Verify about hero height, axes, stats, CTA, and mobile stacking.
- Verify proof accent images load and project link is correct.
- Verify no horizontal overflow, visible focus states, and reduced-motion behavior.
- Run Impeccable detector and targeted `git diff --check`.
