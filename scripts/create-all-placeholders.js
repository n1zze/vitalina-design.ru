const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');

const projects = [
  'private-house-krd',
  'zhk-vse-svoi',
  'zhk-ekaterininskij-park',
  'zhk-ekaterininskij-park-2',
  'zhk-ekaterininskij-park-3',
  'zhk-euro',
  'zhk-moskva',
  'zhk-nebo',
  'zhk-tradicii'
];

(async () => {
  const browser = await chromium.launch({ headless: true });
  const page = await browser.newPage({ viewport: { width: 900, height: 600 } });

  for (const project of projects) {
    const dir = `portfolio/assets/projects/${project}`;
    if (!fs.existsSync(dir)) {
      fs.mkdirSync(dir, { recursive: true });
    }

    // Create "before" image
    await page.setContent(`
      <html><body style="margin:0;background:#e8e8e8;display:flex;align-items:center;justify-content:center;width:900px;height:600px;">
      <div style="text-align:center;font-family:Arial,sans-serif;">
        <div style="font-size:48px;font-weight:bold;color:#333;">ДО</div>
        <div style="font-size:18px;color:#888;margin-top:10px;">Планировка до ремонта</div>
        <div style="width:400px;height:300px;background:#ddd;margin:20px auto;border:2px solid #bbb;"></div>
      </div>
      </body></html>
    `);
    await page.screenshot({ path: `${dir}/before.jpg`, type: 'jpeg', quality: 80 });

    // Create "after" image
    await page.setContent(`
      <html><body style="margin:0;background:#f0f0f0;display:flex;align-items:center;justify-content:center;width:900px;height:600px;">
      <div style="text-align:center;font-family:Arial,sans-serif;">
        <div style="font-size:48px;font-weight:bold;color:#333;">ПОСЛЕ</div>
        <div style="font-size:18px;color:#888;margin-top:10px;">Планировка после дизайна</div>
        <div style="width:400px;height:300px;background:#cde;margin:20px auto;border:2px solid #9bc;"></div>
      </div>
      </body></html>
    `);
    await page.screenshot({ path: `${dir}/after.jpg`, type: 'jpeg', quality: 80 });

    console.log(`Created placeholders for ${project}`);
  }

  await browser.close();
  console.log('All placeholders created');
})();
