# Секция «Планировочное решение» (До / После)

## Контекст

Страницы проектов (portfolio) на vitalina-design.ruCurrently consist of:
1. Header
2. Intro section (title, meta info)
3. Gallery (masonry grid of project images)
4. Footer

По аналогии с [salomatina.design](https://salomatina.design) (Tilda T410 block), необходимо добавить секцию для сравнения планировок «До» и «После».

## Требования

- **Расположение**: После intro, перед gallery
- **Содержание**: Пара изображений — исходная планировка (До) и новая планировка (После)
- **Взаимодействие**: Слайдер с ползунком (Juxtapose.js)
- **Количество**: Одна пара на страницу проекта

## Подход: Juxtapose.js

Используем библиотеку [Juxtapose.js](https://github.com/NUKnightLab/juxtapose) (Knight Lab) — ту же, что использует Tilda в блоке T410.

**Причины выбора:**
- Готовое решение с поддержкой touch-событий
- Адаптивный, работает на мобильных
- Минимальный размер (~15KB)
- Проверено Tilda

## Структура HTML

```html
<section class="project-floorplan">
  <h2 class="project-floorplan__title">Планировочное решение</h2>
  <div class="project-floorplan__subtitle">
    Потяните за ползунок, чтобы сравнить
  </div>
  <div class="project-floorplan__slider">
    <div class="juxtapose"
         data-startingposition="50%"
         data-showlabels="true"
         data-showcredits="false"
         data-animate="true">
      <img src="before.jpg" data-label="До" />
      <img src="after.jpg" data-label="После" />
    </div>
  </div>
</section>
```

**Ключевые элементы:**
- `.project-floorplan` — контейнер секции
- `.project-floorplan__title` — заголовок «Планировочное решение»
- `.project-floorplan__subtitle` — подсказка «Потяните за ползунок...»
- `.juxtapose` — контейнер слайдера (Juxtapose.js)

## Стилизация (CSS)

Секция наследует типографику сайта:
- **Manrope** для заголовков
- **Proxima** для текста

Слайдер занимает всю ширину контейнера (макс. 1400px), как и галерея.

```css
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

/* Кастомизация Juxtapose.js */
.jx-controller {
  width: 60px !important;
  height: 60px !important;
  background-color: #fff !important;
  border: 2px solid #111 !important;
}

.jx-arrow { color: #111 !important; }
```

## Изображения

Для каждого проекта нужно准备ить:
- `before.jpg` — исходная планировка (до ремонта/дизайна)
- `after.jpg` — новая планировка (после redesign)

**Размер:** Рекомендуется **1200×800px** (пропорции 3:2) для хорошего качества на десктопе и мобильных.

## Адаптивность

### Десктоп (> 767px)
- Слайдер: max-width 900px
- Контроллер ползунка: 60×60px

### Мобильные (< 767px)
- Слайдер: 100% ширины
- Контроллер ползунка: 40×40px
- Подсказка «Потяните за ползунок» скрывается на экранах < 480px

## Файлы для изменения

1. **`portfolio/assets/projects.css`** — добавить стили для `.project-floorplan`
2. **`portfolio/privateinterior/*.html`** (и другие проекты) — добавить секцию HTML
3. **Juxtapose.js** — добавить через CDN или локально

## CDN для Juxtapose.js

```html
<link rel="stylesheet" type="text/css" href="https://cdn.knightlab.com/libs/juxtapose/latest/css/juxtapose.css">
<script src="https://cdn.knightlab.com/libs/juxtapose/latest/js/juxtapose.min.js"></script>
```

## Порядок реализации

1. Добавить CSS-файл Juxtapose.js в `<head>` всех страниц проектов
2. Добавить JS-файл Juxtapose.js перед `</body>`
3. Добавить стили `.project-floorplan` в `projects.css`
4. Добавить HTML-секцию в каждый файл проекта (с placeholder-изображениями)
5. Проверить адаптивность
