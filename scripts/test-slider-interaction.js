const { chromium } = require('playwright');

(async () => {
  const browser = await chromium.launch({ headless: true });
  const page = await browser.newPage({ viewport: { width: 1400, height: 900 } });

  // Test local site
  console.log('=== Testing local slider ===');
  await page.goto('file:///c:/Users/nizze/Desktop/vitalina-design.ru/portfolio/privateinterior/private-house-krd.html', { waitUntil: 'domcontentloaded' });
  await page.waitForTimeout(2000);

  // Take screenshot of initial state
  await page.screenshot({ path: 'scripts/local-slider-initial.png', fullPage: false });

  // Scroll to the floorplan section
  await page.evaluate(() => {
    document.querySelector('.project-floorplan').scrollIntoView({ behavior: 'instant', block: 'center' });
  });
  await page.waitForTimeout(500);
  await page.screenshot({ path: 'scripts/local-slider-view.png', fullPage: false });

  // Find the slider handle and drag it
  const handle = await page.$('.jx-handle');
  if (handle) {
    const box = await handle.boundingBox();
    console.log('Handle position:', box);

    // Drag from center to left
    await page.mouse.move(box.x + box.width / 2, box.y + box.height / 2);
    await page.mouse.down();
    await page.mouse.move(box.x - 100, box.y + box.height / 2, { steps: 10 });
    await page.waitForTimeout(300);
    await page.screenshot({ path: 'scripts/local-slider-dragged-left.png', fullPage: false });

    // Drag to right
    await page.mouse.move(box.x + 200, box.y + box.height / 2, { steps: 10 });
    await page.waitForTimeout(300);
    await page.screenshot({ path: 'scripts/local-slider-dragged-right.png', fullPage: false });

    await page.mouse.up();
    console.log('Slider interaction tested successfully');
  } else {
    console.log('ERROR: Could not find slider handle');
  }

  // Check if images are properly sized
  const imageCheck = await page.evaluate(() => {
    const imgs = document.querySelectorAll('.jx-image img');
    return Array.from(imgs).map((img, i) => {
      const rect = img.getBoundingClientRect();
      const computed = window.getComputedStyle(img);
      return {
        index: i,
        naturalWidth: img.naturalWidth,
        naturalHeight: img.naturalHeight,
        displayWidth: rect.width,
        displayHeight: rect.height,
        objectFit: computed.objectFit,
        src: img.src.split('/').pop()
      };
    });
  });
  console.log('Image details:', imageCheck);

  await browser.close();
  console.log('\nDone! Check scripts/ folder for screenshots.');
})();
