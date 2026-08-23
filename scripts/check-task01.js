'use strict';

const fs = require('fs');
const path = require('path');

const root = path.resolve(__dirname, '..');
const failures = [];

const files = {
  catalog: 'portfolio/service.htm',
  planning: 'portfolio/service/planirovochnoe-reshenie.html',
  consultation: 'portfolio/service/konsultaciya.html',
  interiorDesign: 'portfolio/service/interior-design.html',
  visualization: 'portfolio/service/3d-visualization.html',
  supervision: 'portfolio/service/author-supervision.html',
  fitout: 'portfolio/service/komplektaciya.html',
  about: 'portfolio/about.htm',
};

function fail(message) {
  failures.push(message);
}

function readRequired(label, relativePath) {
  const absolutePath = path.join(root, relativePath);

  if (!fs.existsSync(absolutePath)) {
    fail(`${label}: отсутствует файл ${relativePath}`);
    return null;
  }

  return fs.readFileSync(absolutePath, 'utf8');
}

function visibleText(html) {
  return html
    .replace(/<script\b[^>]*>[\s\S]*?<\/script>/gi, ' ')
    .replace(/<style\b[^>]*>[\s\S]*?<\/style>/gi, ' ')
    .replace(/<[^>]+>/g, ' ')
    .replace(/&nbsp;|&#160;/gi, ' ')
    .replace(/&mdash;|&#8212;/gi, '—')
    .replace(/&ndash;|&#8211;/gi, '−')
    .replace(/&sup2;|&#178;/gi, '²')
    .replace(/&laquo;|&#171;/gi, '«')
    .replace(/&raquo;|&#187;/gi, '»')
    .replace(/&amp;/gi, '&')
    .replace(/\s+/g, ' ')
    .trim();
}

function comparable(value) {
  return value
    .toLocaleLowerCase('ru-RU')
    .replace(/[–—−]/g, '-')
    .replace(/ё/g, 'е')
    .replace(/\s+/g, ' ')
    .trim();
}

function requireText(label, text, expected) {
  if (!comparable(text).includes(comparable(expected))) {
    fail(`${label}: отсутствует обязательный текст «${expected}»`);
  }
}

function requirePattern(label, text, description, pattern) {
  if (!pattern.test(comparable(text))) {
    fail(`${label}: отсутствует ${description}`);
  }
}

function forbidText(label, text, forbidden) {
  if (comparable(text).includes(comparable(forbidden))) {
    fail(`${label}: найден запрещённый текст «${forbidden}»`);
  }
}

const contents = {};
for (const [label, relativePath] of Object.entries(files)) {
  const html = readRequired(label, relativePath);
  if (html !== null) {
    contents[label] = {
      html,
      text: visibleText(html),
    };
  }
}

if (contents.catalog) {
  const expectedLinks = [
    'service/planirovochnoe-reshenie.html',
    'service/3d-visualization.html',
    'service/konsultaciya.html',
    'service/interior-design.html',
    'service/komplektaciya.html',
    'service/author-supervision.html',
  ];
  const actualLinks = [...contents.catalog.html.matchAll(
    /<a\b(?=[^>]*\bclass=["'][^"']*\bservice-photo(?:--link)?\b[^"']*["'])[^>]*\bhref=["']([^"']+)["'][^>]*>/gi,
  )].map((match) => match[1]);

  if (JSON.stringify(actualLinks) !== JSON.stringify(expectedLinks)) {
    fail(
      `catalog: неверный порядок ссылок карточек\n` +
      `  ожидалось: ${expectedLinks.join(' -> ')}\n` +
      `  получено:  ${actualLinks.join(' -> ') || '(нет ссылок)'}`,
    );
  }

  for (const title of [
    'ПЛАНИРОВОЧНОЕ РЕШЕНИЕ',
    '3D ВИЗУАЛИЗАЦИЯ',
    'КОНСУЛЬТАЦИЯ',
    'ДИЗАЙН-ПРОЕКТ',
    'КОМПЛЕКТАЦИЯ',
    'АВТОРСКОЕ СОПРОВОЖДЕНИЕ',
  ]) {
    requireText('catalog', contents.catalog.text, title);
  }
}

if (contents.planning) {
  const text = contents.planning.text;
  for (const expected of [
    'Планировочное решение',
    'Консультация',
    'Обмерный план помещения',
    '2-3 варианта расстановки мебели',
    'Схема демонтажа перегородок',
    'Схема монтажа перегородок',
    'Финальный план с утверждённой расстановкой мебели и перегородками',
    'Комментарии и рекомендации по использованию пространства',
    'Готовый план квартиры',
    'Пример планировочного решения',
  ]) {
    requireText('planning', text, expected);
  }
  requirePattern('planning', text, 'срок 5–12 рабочих дней', /5\s*-\s*12 рабочих дней/);
  requirePattern('planning', text, 'стоимость от 10 000 рублей', /от 10\s*000 (?:руб(?:\.|лей)?|₽)/);
}

if (contents.consultation) {
  const text = contents.consultation.text;
  for (const expected of [
    'Консультация',
    'навести порядок в мыслях и проекте',
    'конкретным вопросом или общим запросом',
    'совместить эстетику и логику',
  ]) {
    requireText('consultation', text, expected);
  }
  requirePattern(
    'consultation',
    text,
    'стоимость от 5 000 рублей в час',
    /от 5\s*000 (?:руб(?:\.|лей)?|₽)(?:\s*\/\s*|\s+(?:за|в)\s*)час/,
  );
}

if (contents.interiorDesign) {
  const text = contents.interiorDesign.text;
  for (const expected of [
    'Планировочное решение',
    'Фотореалистичная 3D-визуализация всех помещений',
    'Полный комплект рабочей документации для строителей',
    'Спецификация отделочных материалов',
    'Подбор мебели, освещения, сантехники и декора',
    'Обмерный план',
    'Схема демонтажа конструкций',
    'Схема монтажа конструкций',
    'Схема перепланировки квартиры',
    'Схема расстановки мебели',
    'Схема привязки сантехнического оборудования',
    'Схема раскладки напольных покрытий',
    'Схема дверных проёмов',
    'Схема потолков',
    'Схема привязки осветительного оборудования',
    'Схема групп включения и привязки выключателей',
    'Схема привязки розеток и электровыводов',
    'Спецификация розеток и выключателей',
    'Схема отделки стен',
    'Развертки по стенам помещений',
    'Сводная ведомость объемов отделочных материалов',
    'Подбор референсов',
    'Пример проекта',
  ]) {
    requireText('interiorDesign', text, expected);
  }
  requirePattern('interiorDesign', text, 'срок от 30 рабочих дней', /от 30 рабочих дней/);
  requirePattern(
    'interiorDesign',
    text,
    'стоимость 3 000 рублей за квадратный метр',
    /3\s*000 (?:руб(?:\.|лей)?|₽)(?:\s*\/\s*(?:м2|м²)|\s+за квадратный метр)/,
  );
  forbidText('interiorDesign', text, 'Дизайн-концепт');
}

if (contents.visualization) {
  const text = contents.visualization.text;
  for (const expected of [
    'Обмерный план',
    'Планировочное решение',
    'Фотореалистичная 3D-визуализация всех помещений',
    'План демонтажа',
    'План монтажа',
    'показывает интерьер «как в жизни»',
    'Интерьер собирается в единую, гармоничную концепцию',
    'каким будет пространство на 100%',
  ]) {
    requireText('visualization', text, expected);
  }
  requirePattern('visualization', text, 'срок от 30 рабочих дней', /от 30 рабочих дней/);
  requirePattern(
    'visualization',
    text,
    'стоимость 1 000 рублей за квадратный метр',
    /1\s*000 (?:руб(?:\.|лей)?|₽)(?:\s*\/\s*(?:м2|м²)|\s+за квадратный метр)/,
  );
}

if (contents.supervision) {
  const text = contents.supervision.text;
  for (const expected of [
    'Авторское сопровождение',
    'Еженедельные выезды на объект',
    'Контроль соответствия дизайн-проекта и реализации',
    'Коммуникация со строителями и подрядчиками',
    'Отчет и фотофиксация',
  ]) {
    requireText('supervision', text, expected);
  }
  requirePattern(
    'supervision',
    text,
    'стоимость от 30 000 рублей в месяц',
    /от 30\s*000 (?:руб(?:\.|лей)?|₽)(?:\s*\/\s*мес|\s+(?:за|в)\s+месяц)/,
  );
}

if (contents.fitout) {
  const text = contents.fitout.text;
  for (const expected of [
    'Подбор отделочных материалов и элементов интерьера',
    'Составление графика закупок',
    'Подготовка технического задания для изготовления индивидуальной мебели',
    'Согласование с клиентом счетов на закупку и доставку чистовых материалов',
    'Приемка чистовых материалов и элементов интерьера',
  ]) {
    requireText('fitout', text, expected);
  }
  requirePattern(
    'fitout',
    text,
    'стоимость 2 000 рублей за квадратный метр',
    /2\s*000 (?:руб(?:\.|лей)?|₽)(?:\s*\/\s*(?:м2|м²)|\s+за квадратный метр)/,
  );
}

if (contents.about) {
  requireText('about', contents.about.text, 'Планировочное решение');
  requirePattern('about', contents.about.text, 'описание 2–3 вариантов планировки', /2\s*-\s*3 варианта планировки/);
}

if (failures.length > 0) {
  console.error(`FAIL: требования task01 не выполнены (${failures.length})`);
  for (const failure of failures) {
    console.error(`- ${failure}`);
  }
  process.exitCode = 1;
} else {
  console.log('PASS: требования task01 выполнены');
}
