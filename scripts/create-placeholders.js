const { chromium } = require('playwright');

(async () => {
  const browser = await chromium.launch({ headless: true });
  const page = await browser.newPage({ viewport: { width: 900, height: 600 } });

  // Create placeholder image "before"
  await page.setContent(`
    <html><body style="margin:0;background:#e8e8e8;display:flex;align-items:center;justify-content:center;width:900px;height:600px;">
    <div style="text-align:center;font-family:Arial,sans-serif;">
      <div style="font-size:48px;font-weight:bold;color:#333;">ДО</div>
      <div style="font-size:18px;color:#888;margin-top:10px;">Планировка до ремонта</div>
      <div style="width:400px;height:300px;background:#ddd;margin:20px auto;border:2px solid #bbb;"></div>
    </div>
    </body></html>
  `);
  await page.screenshot({ path: 'portfolio/assets/projects/private-house-krd/before.jpg', type: 'jpeg', quality: 80 });

  // Create placeholder image "after"
  await page.setContent(`
    <html><body style="margin:0;background:#f0f0f0;display:flex;align-items:center;justify-content:center;width:900px;height:600px;">
    <div style="text-align:center;font-family:Arial,sans-serif;">
      <div style="font-size:48px;font-weight:bold;color:#333;">ПОСЛЕ</div>
      <div style="font-size:18px;color:#888;margin-top:10px;">Планировка после дизайна</div>
      <div style="width:400px;height:300px;background:#cde;margin:20px auto;border:2px solid #9bc;"></div>
    </div>
    </body></html>
  `);
  await page.screenshot({ path: 'portfolio/assets/projects/private-house-krd/after.jpg', type: 'jpeg', quality: 80 });

  console.log('Placeholder images created');
  await browser.close();
})();
