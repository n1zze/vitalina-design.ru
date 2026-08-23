# Секция «Планировочное решение» (До / После) — План реализации

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Добавить на страницы проектов интерактивную секцию для сравнения планировок «До» и «После» с использованием слайдера Juxtapose.js

**Architecture:** Секция размещается между intro и gallery на каждой странице проекта. Используется библиотека Juxtapose.js (Knight Lab) для реализации слайдера. CSS стили добавляются в существующий файл `projects.css`.

**Tech Stack:** HTML, CSS, Juxtapose.js (CDN)

## Global Constraints

- Juxtapose.js загружается через CDN (knightlab.com)
- Стилизация наследует типографику сайта: Manrope для заголовков, Proxima для текста
- Максимальная ширина контейнера: 1400px (как у gallery)
- Адаптивность: поддержка touch-событий, уменьшение контроллера на мобильных

---

## Структура файлов

| Файл | Ответственность |
|------|-----------------|
| `portfolio/assets/projects.css` | Стили секции `.project-floorplan` |
| `portfolio/privateinterior/*.html` | HTML-разметка секции (9 файлов) |

---

### Task 1: Добавить CSS-файл Juxtapose.js в head страниц проектов

**Files:**
- Modify: `portfolio/privateinterior/*.html` (9 файлов)

**Interfaces:**
- Consumes: CDN URL для juxtapose.css
- Produces: CSS-файл подключен в `<head>` каждой страницы проекта

- [ ] **Step 1: Прочитать текущий HTML-шаблон проекта**

Откройте `portfolio/privateinterior/private-house-krd.html` и изучите структуру `<head>`.

- [ ] **Step 2: Добавить CSS-файл Juxtapose.js в head**

Найдите строку:
```html
<link href="../assets/projects.css" rel="stylesheet">
```

Добавьте после неё:
```html
<link rel="stylesheet" type="text/css" href="https://cdn.knightlab.com/libs/juxtapose/latest/css/juxtapose.css">
```

- [ ] **Step 3: Повторить для всех страниц проектов**

Повторите шаг 2 для всех файлов:
- `portfolio/privateinterior/zhk-vse-svoi.html`
- `portfolio/privateinterior/zhk-ekaterininskij-park.html`
- `portfolio/privateinterior/zhk-ekaterininskij-park-2.html`
- `portfolio/privateinterior/zhk-ekaterininskij-park-3.html`
- `portfolio/privateinterior/zhk-euro.html`
- `portfolio/privateinterior/zhk-moskva.html`
- `portfolio/privateinterior/zhk-nebo.html`
- `portfolio/privateinterior/zhk-tradicii.html`
- `portfolio/privateinterior/private-house-krd.html`

- [ ] **Step 4: Проверить подключение**

Откройте одну из страниц в браузере, проверьте в DevTools (Network → CSS), что `juxtapose.css` загружается.

- [ ] **Step 5: Commit**

```bash
git add portfolio/privateinterior/*.html
git commit -m "feat: add Juxtapose.js CSS to project pages"
```

---

### Task 2: Добавить JS-файл Juxtapose.js перед закрывающим body

**Files:**
- Modify: `portfolio/privateinterior/*.html` (9 файлов)

**Interfaces:**
- Consumes: CDN URL для juxtapose.min.js
- Produces: JS-файл подключен перед `</body>`

- [ ] **Step 1: Найти.location подключения JS**

В `private-house-krd.html` найдите строку:
```html
<script src="../../skins/saparova/js/apps.js?v=11"></script>
```

- [ ] **Step 2: Добавить JS-файл Juxtapose.js**

Добавьте **после** строки с apps.js:
```html
<script src="https://cdn.knightlab.com/libs/juxtapose/latest/js/juxtapose.min.js"></script>
```

- [ ] **Step 3: Повторить для всех страниц проектов**

Повторите шаг 2 для всех 9 файлов проектов.

- [ ] **Step 4: Проверить загрузку**

Откройте страницу в браузере, проверьте в DevTools (Network → JS), что `juxtapose.min.js` загружается без ошибок.

- [ ] **Step 5: Commit**

```bash
git add portfolio/privateinterior/*.html
git commit -m "feat: add Juxtapose.js script to project pages"
```

---

### Task 3: Добавить стили .project-floorplan в projects.css

**Files:**
- Modify: `portfolio/assets/projects.css`

**Interfaces:**
- Consumes: Типографика сайта (Manrope, Proxima), цвета (#111, #777, #fff)
- Produces: CSS-классы `.project-floorplan`, `.project-floorplan__title`, `.project-floorplan__subtitle`, `.project-floorplan__slider`

- [ ] **Step 1: Открыть projects.css**

Откройте `portfolio/assets/projects.css`.

- [ ] **Step 2: Добавить стили секции**

Добавьте в конец файла (перед медиа-запросами):

```css
/* ── Планировочное решение (До / После) ────────────────────── */

.project-floorplan {
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 25px 60px;
  text-align: center;
}

.project-floorplan__title {
  font-family: "Manrope", sans-serif;
  font-size: clamp(24px, 3vw, 40px);
  font-weight: 500;
  line-height: 1.2;
  margin-bottom: 12px;
}

.project-floorplan__subtitle {
  color: #777;
  font-size: 14px;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  margin-bottom: 32px;
}

.project-floorplan__slider {
  max-width: 900px;
  margin: 0 auto;
}

/* Кастомизация Juxtapose.js под дизайн сайта */
.project-floorplan .jx-controller {
  width: 60px !important;
  height: 60px !important;
  background-color: #fff !important;
  border: 2px solid #111 !important;
}

.project-floorplan .jx-arrow {
  color: #111 !important;
}

@media (max-width: 767px) {
  .project-floorplan {
    padding: 0 18px 40px;
  }

  .project-floorplan__subtitle {
    font-size: 12px;
  }

  .project-floorplan .jx-controller {
    width: 40px !important;
    height: 40px !important;
  }
}

@media (max-width: 480px) {
  .project-floorplan__subtitle {
    display: none;
  }
}
```

- [ ] **Step 3: Проверить отображение**

Откройте страницу проекта в браузере, проверьте в DevTools (Elements → Computed), что стили применяются.

- [ ] **Step 4: Commit**

```bash
git add portfolio/assets/projects.css
git commit -m "feat: add CSS styles for floorplan before/after section"
```

---

### Task 4: Добавить HTML-секцию в страницу проекта (private-house-krd)

**Files:**
- Modify: `portfolio/privateinterior/private-house-krd.html`

**Interfaces:**
- Consumes: CSS-классы из Task 3, JS из Task 2
- Produces: HTML-секция `.project-floorplan` с изображениями

- [ ] **Step 1: Найти location для вставки**

В `private-house-krd.html` найдите закрывающий тег `</section>` секции intro:
```html
</section>
  <section class="project-gallery" ...
```

- [ ] **Step 2: Вставить HTML-секцию**

Вставьте **между** intro и gallery:

```html
  <section class="project-floorplan">
    <h2 class="project-floorplan__title">Планировочное решение</h2>
    <div class="project-floorplan__subtitle">Потяните за ползунок, чтобы сравнить</div>
    <div class="project-floorplan__slider">
      <div class="juxtapose"
           data-startingposition="50%"
           data-showlabels="true"
           data-showcredits="false"
           data-animate="true">
        <img src="../assets/projects/private-house-krd/before.jpg" data-label="До" />
        <img src="../assets/projects/private-house-krd/after.jpg" data-label="После" />
      </div>
    </div>
  </section>
```

- [ ] **Step 3: Добавить placeholder-изображения**

Создайте временные изображения `before.jpg` и `after.jpg` в `portfolio/assets/projects/private-house-krd/` (можно скопировать любые существующие изображения проекта для теста).

- [ ] **Step 4: Проверить отображение**

Откройте `private-house-krd.html` в браузере. Проверьте:
- Секция видна между intro и gallery
- Слайдер работает (можно двигать ползунок)
- Подписи «До» и «После» отображаются
- На мобильном размере контроллер уменьшается

- [ ] **Step 5: Commit**

```bash
git add portfolio/privateinterior/private-house-krd.html
git commit -m "feat: add floorplan before/after section to private-house-krd"
```

---

### Task 5: Добавить HTML-секцию в остальные страницы проектов

**Files:**
- Modify: `portfolio/privateinterior/zhk-vse-svoi.html`
- Modify: `portfolio/privateinterior/zhk-ekaterininskij-park.html`
- Modify: `portfolio/privateinterior/zhk-ekaterininskij-park-2.html`
- Modify: `portfolio/privateinterior/zhk-ekaterininskij-park-3.html`
- Modify: `portfolio/privateinterior/zhk-euro.html`
- Modify: `portfolio/privateinterior/zhk-moskva.html`
- Modify: `portfolio/privateinterior/zhk-nebo.html`
- Modify: `portfolio/privateinterior/zhk-tradicii.html`

**Interfaces:**
- Consumes: HTML-шаблон из Task 4
- Produces: Секция `.project-floorplan` на всех страницах проектов

- [ ] **Step 1: Подготовить шаблон**

Используйте HTML-шаблон из Task 4, заменив только имя проекта в путях к изображениям:
```html
<img src="../assets/projects/{PROJECT-NAME}/before.jpg" data-label="До" />
<img src="../assets/projects/{PROJECT-NAME}/after.jpg" data-label="После" />
```

- [ ] **Step 2: Добавить секцию в каждый файл**

Повторите для каждого файла, вставляя секцию между intro и gallery.

**Файлы и имена проектов:**
- `zhk-vse-svoi.html` → `zhk-vse-svoi`
- `zhk-ekaterininskij-park.html` → `zhk-ekaterininskij-park`
- `zhk-ekaterininskij-park-2.html` → `zhk-ekaterininskij-park-2`
- `zhk-ekaterininskij-park-3.html` → `zhk-ekaterininskij-park-3`
- `zhk-euro.html` → `zhk-euro`
- `zhk-moskva.html` → `zhk-moskva`
- `zhk-nebo.html` → `zhk-nebo`
- `zhk-tradicii.html` → `zhk-tradicii`

- [ ] **Step 3: Добавить placeholder-изображения**

Для каждого проекта создайте `before.jpg` и `after.jpg` в соответствующей папке `portfolio/assets/projects/{PROJECT-NAME}/`.

- [ ] **Step 4: Проверить все страницы**

Откройте каждую страницу проекта и проверьте, что секция отображается корректно.

- [ ] **Step 5: Commit**

```bash
git add portfolio/privateinterior/*.html
git commit -m "feat: add floorplan before/after section to all project pages"
```

---

### Task 6: Финальная проверка и документация

**Files:**
- Read: Все изменённые файлы

**Interfaces:**
- Consumes: Все предыдущие задачи
- Produces: Рабочая функциональность на всех страницах

- [ ] **Step 1: Проверить адаптивность**

Проверьте отображение на разных размерах экрана:
- Десктоп (> 1024px)
- Планшет (768px - 1024px)
- Мобильные (< 768px)
- Очень маленькие (< 480px)

- [ ] **Step 2: Проверить touch-события**

На эмуляторе мобильных устройств в DevTools проверьте, что слайдер работает при касании.

- [ ] **Step 3: Проверить производительность**

Убедитесь, что изображения оптимизированы и загружаются быстро.

- [ ] **Step 4: Commit (если есть правки)**

```bash
git add -A
git commit -m "fix: final adjustments to floorplan before/after section"
```

---

## Готово!

После выполнения всех задач:
1. Каждая страница проекта будет иметь секцию «Планировочное решение»
2. Слайдер будет работать на десктопе и мобильных устройствах
3. Стилизация будет соответствовать дизайну сайта

**Следующий шаг:** Заменить placeholder-изображения на реальные планировки проектов.
