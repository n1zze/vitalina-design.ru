const { chromium } = require('playwright');

(async () => {
  const browser = await chromium.launch({ headless: true });
  const page = await browser.newPage({ viewport: { width: 1400, height: 900 } });

  console.log('=== Checking reference site slider ===');
  try {
    await page.goto('https://salomatina.design', { waitUntil: 'networkidle', timeout: 30000 });

    // Scroll down to find the before/after section
    for (let i = 0; i < 10; i++) {
      await page.evaluate(() => window.scrollBy(0, 500));
      await page.waitForTimeout(500);
    }

    // Take screenshot at current position
    await page.screenshot({ path: 'scripts/ref-slider-area.png', fullPage: false });

    // Look for the before/after component
    const beforeAfterInfo = await page.evaluate(() => {
      const el = document.querySelector('.t-beforeafter');
      if (!el) return null;
      const rect = el.getBoundingClientRect();
      const computed = window.getComputedStyle(el);
      return {
        exists: true,
        width: rect.width,
        height: rect.height,
        display: computed.display,
        position: computed.position,
        className: el.className
      };
    });
    console.log('Before/After component:', beforeAfterInfo);

    // Get the images in the before/after component
    const images = await page.evaluate(() => {
      const els = document.querySelectorAll('.t-beforeafter__image img');
      return Array.from(els).map((img, i) => ({
        index: i,
        src: img.src,
        width: img.width,
        height: img.height,
        naturalWidth: img.naturalWidth,
        naturalHeight: img.naturalHeight
      }));
    });
    console.log('Images:', images);

    // Get the slider handle position
    const handle = await page.evaluate(() => {
      const el = document.querySelector('.t-beforeafter__handle');
      if (!el) return null;
      const rect = el.getBoundingClientRect();
      return {
        x: rect.x,
        y: rect.y,
        width: rect.width,
        height: rect.height
      };
    });
    console.log('Handle:', handle);

    // Scroll to the before/after component
    if (beforeAfterInfo) {
      await page.evaluate(() => {
        document.querySelector('.t-beforeafter').scrollIntoView({ behavior: 'instant', block: 'center' });
      });
      await page.waitForTimeout(500);
      await page.screenshot({ path: 'scripts/ref-slider-centered.png', fullPage: false });
    }

  } catch (e) {
    console.log('Error accessing reference:', e.message);
  }

  await browser.close();
})();
