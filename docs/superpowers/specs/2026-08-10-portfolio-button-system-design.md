# Portfolio Button System Design

## Goal

Unify CTA and button presentation across the public portfolio pages so black, white, beige, gradient, and text controls have clear semantic roles instead of appearing as unrelated styles.

## Scope

Public portfolio surfaces using the existing button/link classes, including:

- `portfolio/about.htm`;
- `portfolio/contact.html`;
- `portfolio/service.htm`;
- `portfolio/service/3d-visualization.html`;
- `portfolio/service/author-supervision.html`;
- `portfolio/service/interior-design.html`;
- their shared public CSS in `skins/saparova/css/main.css` and `skins/saparova/css/contact.css`.

CMS/admin-only controls and floating icon contact controls remain separate systems.

## Semantic Roles

### Primary

The main conversion action on a light surface:

- background `#c99471`;
- white text;
- hover background `#b5835f`;
- examples: `Обсудить проект`, `Отправить заявку`, `Получить консультацию`.

### Inverse

The main conversion action over a dark or image surface:

- white background;
- ink text `#1a1714`;
- hover uses a restrained terracotta surface and white text;
- example: CTA inside the full-bleed service hero.

### Text / Ghost

Secondary actions that should not compete with the primary CTA:

- transparent background;
- thin contextual border or underline;
- terracotta or ink text on light surfaces;
- white border/text over images;
- examples: `Заказать`, `Все проекты`, and card-level `Подробнее`.

Floating contact controls remain icon controls and are not converted into text CTAs.

## Shared Geometry And States

- minimum height `48px` for primary/inverse controls;
- minimum touch target `44px` on mobile;
- horizontal padding `20-24px`;
- border-radius `3px` for filled controls;
- Manrope, `14px`, weight `600` for primary/inverse controls;
- one consistent `:focus-visible` outline with sufficient contrast;
- one consistent hover motion, limited to `translateY(-2px)` and color transition;
- no heavy shadow, layout shift, gradient fill, or unexplained beige button state;
- reduced-motion removes transform transitions but preserves color/state feedback.

## Mapping

- `about.htm` hero CTA: `Primary`;
- `about.htm` and contact forms: `Primary`;
- `contact.html` consultation/project submit buttons: `Primary`;
- service detail hero CTA over image: `Inverse`;
- service detail form submit buttons: `Primary`;
- pricing row `Заказать`: `Text`;
- featured project `Все проекты`: `Text`;
- service card `Подробнее`: `Ghost`, contextual to the image overlay;
- floating phone/Telegram/MAX controls: unchanged icon-control system.

## Compatibility Constraints

- Preserve all labels, hrefs, form IDs, submit behavior, and navigation destinations.
- Do not change content or interaction logic to implement the visual system.
- Keep the existing service card composition and image assets.
- Keep the current warm VITALINA DESIGN palette and Manrope typography.
- Do not change `portfolio/about.htm` content or unrelated CMS/admin controls.

## Verification

- Inventory all mapped public CTA/button classes after implementation.
- Verify each role has correct surface contrast, label visibility, focus state, and mobile target size.
- Verify hero inverse CTA does not stretch or inherit reveal delay into hover.
- Verify primary form controls, text pricing links, and service card ghost controls remain distinguishable.
- Inspect at 1440px, 768px, and 390px.
- Run the Impeccable detector on changed public HTML targets and `git diff --check`.
