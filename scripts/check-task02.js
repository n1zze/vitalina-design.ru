'use strict';

const fs = require('fs');
const path = require('path');
const root = path.resolve(__dirname, '..');
const failures = [];
const oldMax = 'https://max.ru/';
const newMax = 'https://max.ru/u/f9LHodD0cOKt9HbpvkjqjTuiGv6by4LKUyzeoQia5wfDrl-V-WDjzCZxOqs';
const services = [
  ['planning', 'portfolio/service/planirovochnoe-reshenie.html'],
  ['visualization', 'portfolio/service/3d-visualization.html'],
  ['consultation', 'portfolio/service/konsultaciya.html'],
  ['interior', 'portfolio/service/interior-design.html'],
  ['fitout', 'portfolio/service/komplektaciya.html'],
  ['supervision', 'portfolio/service/author-supervision.html'],
];

function read(file) { return fs.readFileSync(path.join(root, file), 'utf8'); }
function fail(message) { failures.push(message); }
function count(html, pattern) { return [...html.matchAll(pattern)].length; }

const catalog = read('portfolio/service.htm');
const cardImages = [...catalog.matchAll(/class=["'][^"']*service-photo[^"']*["'][^>]*>[\s\S]*?<img[^>]+src=["']([^"']+)/gi)].map(m => m[1]);
if (cardImages.length !== 6 || cardImages.some(src => !src.startsWith('assets/projects/'))) fail('catalog: все 6 карточек должны использовать рендеры assets/projects');

const expectedMenu = ['planirovochnoe-reshenie.html', '3d-visualization.html', 'konsultaciya.html', 'interior-design.html', 'komplektaciya.html', 'author-supervision.html'];
for (const [label, file] of services) {
  const html = read(file);
  const menuStart = html.indexOf('sub-menu');
  const menuEnd = html.indexOf('</ul>', menuStart);
  const menu = html.slice(menuStart, menuEnd);
  let cursor = -1;
  for (const href of expectedMenu) {
    const next = menu.indexOf(href);
    if (next <= cursor) fail(`${label}: неполное или неверно упорядоченное меню услуг`);
    cursor = next;
  }
  if (count(html, /class=["'][^"']*pricing-row(?:\s|["'])/gi) !== 1) fail(`${label}: должна быть ровно одна pricing-row`);
  if (!/class=["'][^"']*image-deliverables__row/.test(html)) fail(`${label}: состав услуги должен содержать блоки картинка+описание`);
  if (!/class=["'][^"']*image-deliverables__image/.test(html)) fail(`${label}: блоки состава должны содержать изображения`);
  if (!/data-service-form/.test(html) || !/name=["']name["']/.test(html) || !/name=["']phone["']/.test(html) || !/name=["']consent["']/.test(html)) fail(`${label}: отсутствует полная единая форма`);
}

const interior = read('portfolio/service/interior-design.html');
if (interior.includes('[ Состав проекта ]')) fail('interior: секция Состав проекта должна быть удалена');
if (!interior.includes('[ Рабочая документация ]')) fail('interior: секция Рабочая документация отсутствует');
for (const image of ['../img/survey-plan.jpg', '../img/planing-result.jpg', '../img/select-references.jpg']) {
  if (!interior.includes(image)) fail(`interior: отсутствует ${image}`);
}
for (const image of ['survey-plan.jpg', 'planing-result.jpg', 'dismantling-plan.jpg', 'installation-plan.jpg']) {
  if (!interior.includes(`data-slider-src="../img/${image}"`)) fail(`slider: отсутствует ${image}`);
}
if (!interior.includes('data-deliverables-slider')) fail('interior: отсутствует deliverables slider');

const about = read('portfolio/about.htm');
if (count(about, /data-prefix=["']>["']/g) < 2) fail('about: две метрики должны иметь prefix >');

function walk(dir) {
  return fs.readdirSync(dir, { withFileTypes: true }).flatMap(entry => {
    const full = path.join(dir, entry.name);
    return entry.isDirectory() ? walk(full) : [full];
  });
}
const scanFiles = [...walk(path.join(root, 'portfolio')), ...walk(path.join(root, 'cms', 'templates'))]
  .filter(file => /\.(?:html?|php)$/i.test(file));
let newMaxCount = 0;
for (const file of scanFiles) {
  const html = fs.readFileSync(file, 'utf8');
  if (html.includes(`href="${oldMax}"`) || html.includes(`href='${oldMax}'`)) fail(`${path.relative(root, file)}: найден старый MAX URL`);
  newMaxCount += html.split(newMax).length - 1;
}
if (!newMaxCount) fail('MAX: новый URL не найден');

if (failures.length) {
  console.error(`FAIL task02 (${failures.length})`);
  failures.forEach(item => console.error(`- ${item}`));
  process.exitCode = 1;
} else {
  console.log('PASS: требования task02 выполнены');
}
