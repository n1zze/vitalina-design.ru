# Service Navigation Design

## Goal

Reframe the service area as one coherent service system rather than a disconnected set of templates. The approved direction is **«Карта услуг»**:

`Дизайн-проект · 3D-визуализация · Авторский надзор`

The design applies to:

- `portfolio/service.htm`;
- `portfolio/service/3d-visualization.html`;
- `portfolio/service/author-supervision.html`;
- `portfolio/service/interior-design.html`.

`portfolio/about.htm` remains the visual and tonal reference.

## Design Thesis

The primary visitor question is not only «what services exist?», but «какая самостоятельная услуга подходит моей задаче?». Every service page therefore exposes the same sibling navigation, highlights the current service, and then presents the existing content for that service. The navigation is not a process timeline and must not imply that one service is required before another.

The visual language stays warm, architectural, and editorial:

- Manrope and the existing warm palette;
- large calm headings with a maximum two-to-three-line measure;
- thin beige rules and terracotta service accents;
- varied density between route navigation, content lists, pricing, proof, and contact;
- one clear primary CTA per page;
- existing reveal motion remains the only authored motion unless a later implementation plan explicitly adds more.

## Service Index

Keep the three existing service cards unchanged: no changes to their HTML, images, prices, links, overlay, typography, hover state, or responsive card behavior.

Change only the surrounding page composition:

- introduce a short editorial introduction that frames the three independent directions using existing service names;
- place a three-option service navigation strip before the unchanged card grid;
- show the options as equal siblings: `Дизайн-проект`, `3D-визуализация`, `Авторский надзор`;
- do not make one service visually primary or imply a required order;
- retain the current card grid as the visual catalog and navigation source.

The service navigation is a navigation aid, not a replacement for the cards. It must remain usable on mobile as a vertical list of equal options with the same DOM order.

## Detail Pages

All three detail pages share one structural shell:

1. sibling service navigation with the current service highlighted;
2. hero containing the existing service title, description, CTA, and relevant visual context;
3. existing `Что входит` content presented as the service's deliverables;
4. existing pricing rows;
5. existing projects strip;
6. existing contact form.

The active navigation item differs per page:

- `interior-design.html`: active service `Дизайн-проект`;
- `3d-visualization.html`: active service `3D-визуализация`;
- `author-supervision.html`: active service `Авторский надзор`.

Use the existing service names, descriptions, scope items, prices, project labels, form fields, links, and IDs. Do not invent claims or replace factual copy.

The sibling navigation links to the three existing detail URLs and gives users a clear way to compare independent services. It must not use next/previous language, numbered progress states, or copy that suggests a required sequence. The current `hero -> scope -> pricing -> projects -> contact form` journey remains intact on each page.

## Layout Rules

Desktop:

- shared container aligned with `about.htm`;
- service navigation spans the content measure and uses three equal options;
- hero uses one consistent text axis and a wide enough title measure to avoid narrow wrapping;
- scope and pricing remain readable single-column content blocks;
- projects provide the main visual change of pace before the form.

Mobile:

- service navigation becomes a full-width vertical list of equal options;
- active service remains visually distinct without relying on hover;
- content stays in DOM order;
- no horizontal overflow at 390px or 768px;
- buttons and service links retain comfortable touch areas;
- long Russian headings and labels wrap naturally.

## Content And Function Constraints

- Preserve all existing service content and form behavior.
- Preserve all anchor IDs, navigation destinations, images, prices, project links, reveal classes, and inline scripts.
- Do not modify service-card internals on `service.htm`.
- Do not add a new dependency or replace the existing static-page architecture.
- Functional risks found during audit, including detail-form submission behavior and missing project targets, are tracked separately and must not be silently changed as part of this visual implementation.

## Verification

- Inspect all four target pages at 1440px, 768px, and 390px.
- Verify sibling service links and active states for all three independent services.
- Verify the three service cards' content and selectors are unchanged.
- Verify all existing forms, anchors, project links, and reveal states remain present.
- Verify no horizontal overflow and consistent left edge alignment for route, hero, headings, and section markers.
- Run the Impeccable detector on the four target HTML files.
- Run `git diff --check` on only the implementation files.
