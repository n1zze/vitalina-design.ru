<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title><?= $title ?></title>
    <meta name="description" content="<?= $description ?>">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="shortcut icon" href="../img/icons/favicon.ico">
    <link rel="canonical" href="<?= $canonical ?>">
    <link href="../skins/saparova/css/main.css?v=14" rel="stylesheet">
    <link href="../skins/saparova/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/projects.css" rel="stylesheet">
</head>
<body id="home">
<header id="header">
    <div class="container container-header container-25">
        <div class="header-container">
            <div class="logo-col"><a href="/" class="fn-load"><img class="logo" src="../skins/saparova/img/vi-black.svg" alt="VITALINA DESIGN"></a></div>
            <div class="menu-col"><nav id="navigation"><ul id="menu">
                <li class="menu-item"><a href="about.htm">Об авторе</a></li>
                <li class="menu-item"><a href="index.htm">Проекты</a></li>
                <li class="menu-item"><a href="service.htm">Услуги</a></li>
                <li class="menu-item"><a href="contact.html">Контакты</a></li>
            </ul></nav></div>
            <div class="social-col"><ul class="socialmedia">
                <li class="socialmedia-li"><a title="MAX" href="https://max.ru/" class="socialmedia-a"><img src="/skins/saparova/img/max-black.svg" alt="MAX" width="22" height="22"></a></li>
                <li class="socialmedia-li"><a title="WhatsApp" href="https://wa.me/79033475152?text=Добрый%20день!" class="socialmedia-a"><i class="fa fa-whatsapp" aria-hidden="true"></i></a></li>
                <li class="socialmedia-li"><a title="Telegram" href="https://t.me/rvvitalina" class="socialmedia-a"><i class="fa fa-telegram" aria-hidden="true"></i></a></li>
            </ul></div>
            <div class="trigger-col"><a href="#0" class="nav-trigger"><span></span></a></div>
        </div>
    </div>
</header>
<main class="projects-page">
    <section class="projects-page__intro">
        <div class="projects-page__eyebrow">Портфолио · Краснодар</div>
        <h1 class="projects-page__title">Проекты</h1>
        <p class="projects-page__description">Проекты жилых пространств VITALINA DESIGN.</p>
    </section>
    <section class="projects-catalog"><div class="projects-catalog__grid">
<?= $cards ?>
    </div></section>
</main>
<footer id="footer"><div class="newfooter"><div class="copy">2025 &copy; vitalina-design.ru</div></div></footer>
<script src="../skins/saparova/js/apps.js?v=11"></script>
</body>
</html>
