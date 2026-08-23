# Task02 Service Unification Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Унифицировать каталог и шесть страниц услуг по визуальному образцу дизайн-проекта, исправить связанные элементы about/contact и обновить MAX URL.

**Architecture:** Статические HTML-страницы сохраняются, а единая геометрия обеспечивается общими классами в `main.css`. Каждая страница получает одинаковые структурные компоненты, но сохраняет собственный контент, цену, тему формы и фоновый рендер. Автослайдер реализуется небольшим доступным vanilla-JS модулем в отдельном файле.

**Tech Stack:** HTML5, CSS3, vanilla JavaScript, Node.js, Playwright, Impeccable.

## Global Constraints

- Использовать Impeccable как обязательный UI/UX workflow.
- Не добавлять внешние зависимости.
- Не менять тексты и цены, кроме требуемой унификации названий и MAX URL.
- Не выполнять общий редизайн сайта.
- Сохранять существующую тёплую редакционную визуальную систему, шрифт Manrope и текущую цветовую палитру.
- Не менять CMS-маркеры на страницах.

---

### Task 1: Проверка требований task02

**Files:**
- Create: `scripts/check-task02.js`
- Read: `task02.md`

**Interfaces:**
- Consumes: каталог, шесть страниц услуг, about, contact, CMS-шаблоны.
- Produces: CLI с exit code `0` только при выполнении проверяемых требований task02.

- [ ] Написать проверки порядка и render-фонов карточек, шести пунктов меню, одной pricing-row на страницу, полной формы, обязательных изображений и четырёх слайдов, метрик `>`, старого/нового MAX URL.
- [ ] Запустить `node scripts/check-task02.js` и подтвердить ожидаемый FAIL.
- [ ] Проверить синтаксис: `node --check scripts/check-task02.js`.

### Task 2: Каталог, меню и MAX URL

**Files:**
- Modify: `portfolio/service.htm`
- Modify: актуальные `portfolio/**/*.{html,htm}` с меню услуг или MAX
- Modify: `cms/templates/catalog.php`
- Modify: `cms/templates/project.php`
- Modify: `skins/saparova/css/main.css`

**Interfaces:**
- Produces: одинаковые render-led карточки, полный порядок меню, единый MAX URL.

- [ ] Заменить шесть изображений карточек рендерами из `portfolio/assets/projects/`.
- [ ] Зафиксировать одинаковые зоны заголовка, цены и CTA через grid/flex в общем CSS.
- [ ] Обновить меню услуг на актуальных страницах и CMS-шаблонах до шести пунктов в порядке каталога.
- [ ] Заменить точный старый MAX URL в актуальных HTML/HTM и CMS-шаблонах.
- [ ] Запустить промежуточную проверку task02.

### Task 3: Единые hero, состав и стоимость услуг

**Files:**
- Modify: `portfolio/service/interior-design.html`
- Modify: `portfolio/service/3d-visualization.html`
- Modify: `portfolio/service/author-supervision.html`
- Modify: `portfolio/service/komplektaciya.html`
- Modify: `portfolio/service/planirovochnoe-reshenie.html`
- Modify: `portfolio/service/konsultaciya.html`
- Modify: `skins/saparova/css/main.css`

**Interfaces:**
- Consumes: `image-deliverables`, hero и pricing-компоненты дизайн-проекта.
- Produces: единая геометрия hero, image-led состав и ровно одна цена на страницу.

- [ ] Добавить общий модификатор страницы услуги и одинаковую структуру hero на шесть страниц.
- [ ] Перевести основные секции состава пяти остальных услуг на `image-deliverables` с локальными рендерами.
- [ ] Удалить дополнительные pricing-row; сохранить одну точную цену из спецификации.
- [ ] Добавить CSS для одинаковой высоты hero, положения CTA, адаптивной image-led композиции.
- [ ] Запустить task01 и task02 проверки.

### Task 4: Изображения и автослайдер дизайн-проекта

**Files:**
- Modify: `portfolio/service/interior-design.html`
- Create: `skins/saparova/js/service-pages.js`
- Modify: `skins/saparova/css/main.css`

**Interfaces:**
- Consumes: `[data-deliverables-slider]` с дочерними изображениями.
- Produces: fade-слайдер с интервалом 4000 мс и reduced-motion guard.

- [ ] Назначить три обязательных изображения.
- [ ] Создать контейнер рабочих чертежей с четырьмя изображениями и корректными alt.
- [ ] Реализовать инициализацию всех `[data-deliverables-slider]`, смену `.is-active` каждые 4000 мс и отказ от таймера при reduced motion.
- [ ] Подключить скрипт к странице дизайн-проекта и добавить CSS без layout shift.
- [ ] Проверить task02 и браузерный сценарий смены активного слайда.

### Task 5: Единые контактные формы услуг

**Files:**
- Modify: шесть `portfolio/service/*.html`
- Modify: `skins/saparova/css/main.css`
- Modify: `skins/saparova/js/service-pages.js`

**Interfaces:**
- Consumes: единый `.contact-form-inline[data-service-form]`.
- Produces: имя, телефон, consent, submit, success и одинаковая отправка на каждой странице.

- [ ] Привести шесть секций формы к одной разметке с уникальными id, `_subject` и разными render-фонами.
- [ ] Перенести общую маску, валидацию, honeypot и fetch-submit в `service-pages.js`.
- [ ] Удалить конфликтующие inline-копии обработчиков на страницах услуг.
- [ ] Проверить уникальность id, наличие labels и task02.

### Task 6: About и contact refinements

**Files:**
- Modify: `portfolio/about.htm`
- Modify: `portfolio/contact.html`
- Modify: `skins/saparova/css/main.css`
- Modify: `skins/saparova/css/contact.css`

**Interfaces:**
- Produces: две метрики с `>`, выровненные этапы и разнесённое согласие формы.

- [ ] Добавить `data-prefix=">"` двум метрикам и поддержать prefix в существующем счётчике либо включить знак в доступную разметку.
- [ ] Согласовать высоту заголовков этапов на desktop и сбросить её на mobile при одноколоночной структуре.
- [ ] Добавить явный gap/margins для consent и submit внутри `contact-form-grid__body` без уменьшения touch target.
- [ ] Запустить task02.

### Task 7: Ограниченный визуальный QA и завершение

**Files:**
- Verify: все изменённые UI-файлы.

**Interfaces:**
- Produces: проверенный task02 без необъяснённых detector findings.

- [ ] Загрузить Impeccable `reference/craft-floor.md` перед UI-редактированием и применить обязательный quality floor.
- [ ] Запустить `node scripts/check-task01.js`, `node scripts/check-task02.js` и `node --check skins/saparova/js/service-pages.js`.
- [ ] Запустить desktop/mobile Playwright-проход каталога, дизайн-проекта, about и contact; проверить overflow, JS errors и screenshots.
- [ ] Исправить найденные дефекты одним пакетом и выполнить не более одного подтверждающего прохода.
- [ ] Запустить `node C:\Users\nizze\.claude\skills\impeccable\scripts\detect.mjs --json` для изменённых UI-файлов и объяснить либо исправить findings.
- [ ] Проверить `git diff --check`, создать feature-коммит и отправить результат в GitHub после подтверждённой верификации.
