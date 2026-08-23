const fs = require('fs');
const path = require('path');

const projects = {
  'zhk-ekaterininskij-park': { title: 'ЖК Екатерининский парк', area: '62 м²', status: 'Дизайн-проект', images: range(2, 30).concat(range(40, 44)), raw: true },
  'zhk-ekaterininskij-park-2': { title: 'ЖК Екатерининский парк', area: '40 м²', status: 'Дизайн-проект + авторское сопровождение', images: range(1, 23) },
  'zhk-ekaterininskij-park-3': { title: 'ЖК Екатерининский парк', area: '60 м²', status: 'Дизайн-проект + авторское сопровождение', images: [1, 2, 4, 5, 6, 7, 8, 9, 10, 11, 12] },
  'zhk-euro': { title: 'ЖК Европейский', area: '150 м²', status: 'Дизайн-проект + авторское сопровождение', images: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 20, 21, '22-22', 22, 23, 24, 25, 26, 27] },
  'zhk-moskva': { title: 'ЖК Москва', area: '15 м²', status: '3D-визуализация', images: range(1, 9) },
  'zhk-nebo': { title: 'ЖК Небо', area: '75 м²', status: 'Дизайн-проект + авторское сопровождение', images: range(1, 15) },
  'zhk-tradicii': { title: 'ЖК Традиции', area: '65 м²', status: 'Дизайн-проект', images: [1, 2, 3, 4, 6, 7, 8, 10, 11, 12, 13, 14, 15, 16, 17] },
  'private-house-krd': { title: 'Частный дом', area: '120 м²', status: '3D-визуализация', images: range(1, 22) }
};

function range(start, end) {
  return Array.from({ length: end - start + 1 }, (_, index) => start + index);
}

function filename(image, raw) {
  if (typeof image === 'string') return `${image}.jpg`;
  return `${raw ? image : String(image).padStart(2, '0')}.jpg`;
}

function renderGallery(slug, project) {
  return project.images.map((image, index) => {
    const file = filename(image, project.raw);
    const path = `../assets/projects/${slug}/${file}`;
    const loading = index === 0 ? 'fetchpriority="high"' : 'loading="lazy"';
    return `    <a class="project-gallery__item fn-gallery" href="${path}" data-fancybox="${slug}"><img src="${path}" alt="${project.title}, интерьер" ${loading} decoding="async"></a>`;
  }).join('\n');
}

function renderPage(slug, project) {
  const description = `${project.title}, ${project.area}, Краснодар. ${project.status} от VITALINA DESIGN.`;
  return `<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>${project.title} - VITALINA DESIGN</title>
  <meta name="description" content="${description}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="../../img/icons/favicon.ico">
  <link rel="apple-touch-icon" sizes="180x180" href="../../img/icons/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="../../img/icons/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="../../img/icons/favicon-16x16.png">
  <link rel="manifest" href="../../img/icons/site.webmanifest">
  <link rel="canonical" href="https://vitalina-design.ru/portfolio/privateinterior/${slug}.html">
  <meta property="og:site_name" content="VITALINA DESIGN">
  <meta property="og:title" content="${project.title} - VITALINA DESIGN">
  <meta property="og:description" content="${description}">
  <meta property="og:url" content="https://vitalina-design.ru/portfolio/privateinterior/${slug}.html">
  <meta property="og:type" content="article">
  <meta property="og:locale" content="ru_RU">
  <meta property="og:image" content="https://vitalina-design.ru/portfolio/assets/projects/${slug}/cover.jpg">
  <meta name="twitter:card" content="summary_large_image">
  <link href="../../skins/saparova/css/main.css?v=14" rel="stylesheet">
  <link href="../../skins/saparova/css/font-awesome.min.css" rel="stylesheet">
  <link href="../assets/projects.css" rel="stylesheet">
</head>
<body id="home">
<header id="header"><div class="container container-header container-25"><div class="header-container">
  <div class="logo-col"><a href="/" class="fn-load"><img class="logo" src="../../skins/saparova/img/vi-black.svg" alt="VITALINA DESIGN"></a></div>
  <div class="menu-col"><nav id="navigation"><ul id="menu"><li class="menu-item"><a href="../about.htm">Об авторе</a></li><li class="menu-item"><a href="../index.htm">Проекты</a></li><li class="menu-item"><a href="../service.htm">Услуги</a></li><li class="menu-item"><a href="../contact.html">Контакты</a></li></ul></nav></div>
  <div class="social-col"><ul class="socialmedia"><li class="socialmedia-li"><a title="MAX" href="https://max.ru/" class="socialmedia-a"><img src="/skins/saparova/img/max-black.svg" alt="MAX" width="22" height="22"></a></li><li class="socialmedia-li"><a title="WhatsApp" href="https://wa.me/79033475152?text=Добрый%20день!" class="socialmedia-a"><i class="fa fa-whatsapp" aria-hidden="true"></i></a></li><li class="socialmedia-li"><a title="Telegram" href="https://t.me/rvvitalina" class="socialmedia-a"><i class="fa fa-telegram" aria-hidden="true"></i></a></li></ul></div>
  <div class="trigger-col"><a href="#0" class="nav-trigger"><span></span></a></div>
</div></div></header>
<main class="projects-page">
  <section class="projects-page__intro"><div class="projects-page__eyebrow">Частный интерьер · Краснодар</div><h1 class="projects-page__title">${project.title}</h1><div class="project-meta"><span>${project.area}</span><span>${project.status}</span><span>Краснодар</span></div></section>
  <section class="project-gallery" aria-label="Галерея проекта ${project.title}">
${renderGallery(slug, project)}
  </section>
  <a class="project-back" href="../index.htm">← Все проекты</a>
</main>
<footer id="footer"><div class="newfooter"><div class="copy">2025 &copy; vitalina-design.ru</div><div class="dev-link"><span>Разработал:<a href="https://a-romashkov.ru/" target="_blank" rel="noopener noreferrer">a-romashkov.ru</a></span></div></div></footer>
<script src="../../skins/saparova/js/apps.js?v=11"></script>
</body>
</html>
`;
}

const outputDir = path.resolve(__dirname, '../portfolio/privateinterior');
Object.entries(projects).forEach(([slug, project]) => {
  project.images.forEach((image) => {
    const asset = path.resolve(__dirname, `../portfolio/assets/projects/${slug}`, filename(image, project.raw));
    if (!fs.existsSync(asset)) throw new Error(`Missing project asset: ${asset}`);
  });
  fs.writeFileSync(path.join(outputDir, `${slug}.html`), renderPage(slug, project), 'utf8');
});
