const { chromium } = require('playwright');

(async () => {
  const browser = await chromium.launch({ headless: true });
  const page = await browser.newPage({ viewport: { width: 1400, height: 900 } });

  console.log('=== Checking reference site ===');
  try {
    await page.goto('https://salomatina.design', { waitUntil: 'networkidle', timeout: 30000 });

    // Scroll down to find the before/after section
    await page.evaluate(() => window.scrollTo(0, 2000));
    await page.waitForTimeout(2000);

    // Take screenshot
    await page.screenshot({ path: 'scripts/ref-scroll.png', fullPage: false });

    // Look for juxtapose elements
    const juxtaposeCount = await page.evaluate(() => {
      return document.querySelectorAll('.juxtapose').length;
    });
    console.log('Juxtapose elements found:', juxtaposeCount);

    // Look for any slider or comparison elements
    const sliderInfo = await page.evaluate(() => {
      const elements = document.querySelectorAll('[class*="slider"], [class*="compare"], [class*="before"], [class*="after"]');
      return Array.from(elements).map(el => ({
        tag: el.tagName,
        className: el.className,
        id: el.id
      }));
    });
    console.log('Slider elements:', sliderInfo);

    // Take full page screenshot
    await page.screenshot({ path: 'scripts/ref-full.png', fullPage: true });
    console.log('Full page screenshot saved');

  } catch (e) {
    console.log('Error accessing reference:', e.message);
  }

  await browser.close();
})();
