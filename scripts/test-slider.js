const { chromium } = require('playwright');

(async () => {
  const browser = await chromium.launch({ headless: true });
  const page = await browser.newPage({ viewport: { width: 1400, height: 900 } });

  // First, let's look at the reference site to understand the structure
  console.log('=== Checking reference site (salomatina.design) ===');
  try {
    await page.goto('https://salomatina.design', { waitUntil: 'networkidle', timeout: 30000 });
    await page.screenshot({ path: 'scripts/ref-home.png', fullPage: false });
    console.log('Reference home page captured');

    // Find the before/after section
    const juxtaposeElements = await page.$$('.juxtapose');
    console.log(`Found ${juxtaposeElements.length} juxtapose elements on reference`);

    if (juxtaposeElements.length > 0) {
      // Get the CSS of the first juxtapose element
      const styles = await page.evaluate(() => {
        const el = document.querySelector('.juxtapose');
        if (!el) return null;
        const computed = window.getComputedStyle(el);
        return {
          width: computed.width,
          height: computed.height,
          position: computed.position,
          overflow: computed.overflow
        };
      });
      console.log('Reference juxtapose styles:', styles);

      // Get the CSS of jx-image elements
      const imageStyles = await page.evaluate(() => {
        const el = document.querySelector('.jx-image');
        if (!el) return null;
        const computed = window.getComputedStyle(el);
        const img = el.querySelector('img');
        const imgComputed = img ? window.getComputedStyle(img) : null;
        return {
          container: {
            width: computed.width,
            height: computed.height,
            position: computed.position,
            overflow: computed.overflow
          },
          img: imgComputed ? {
            width: imgComputed.width,
            height: imgComputed.height,
            objectFit: imgComputed.objectFit,
            position: imgComputed.position
          } : null
        };
      });
      console.log('Reference jx-image styles:', imageStyles);
    }
  } catch (e) {
    console.log('Could not access reference site:', e.message);
  }

  // Now check our local site
  console.log('\n=== Checking local site ===');
  await page.goto('file:///c:/Users/nizze/Desktop/vitalina-design.ru/portfolio/privateinterior/private-house-krd.html', { waitUntil: 'domcontentloaded' });

  // Wait for images to load
  await page.waitForTimeout(2000);

  await page.screenshot({ path: 'scripts/local-before.png', fullPage: false });

  // Check if juxtapose loaded
  const hasJuxtapose = await page.evaluate(() => {
    return typeof window.juxtapose !== 'undefined' || document.querySelector('.juxtapose') !== null;
  });
  console.log('Juxtapose loaded:', hasJuxtapose);

  // Check what's visible
  const floorplanVisible = await page.evaluate(() => {
    const section = document.querySelector('.project-floorplan');
    if (!section) return { exists: false };
    const computed = window.getComputedStyle(section);
    const rect = section.getBoundingClientRect();
    return {
      exists: true,
      display: computed.display,
      visibility: computed.visibility,
      height: rect.height,
      width: rect.width,
      children: section.innerHTML.substring(0, 500)
    };
  });
  console.log('Floorplan section:', floorplanVisible);

  // Check the slider container
  const sliderInfo = await page.evaluate(() => {
    const slider = document.querySelector('.project-floorplan__slider');
    if (!slider) return { exists: false };
    const computed = window.getComputedStyle(slider);
    const rect = slider.getBoundingClientRect();
    return {
      exists: true,
      display: computed.display,
      height: rect.height,
      width: rect.width,
      maxHeight: computed.maxHeight,
      overflow: computed.overflow
    };
  });
  console.log('Slider container:', sliderInfo);

  // Check juxtapose element
  const juxtaposeInfo = await page.evaluate(() => {
    const el = document.querySelector('.juxtapose');
    if (!el) return { exists: false };
    const computed = window.getComputedStyle(el);
    const rect = el.getBoundingClientRect();
    return {
      exists: true,
      display: computed.display,
      height: rect.height,
      width: rect.width,
      position: computed.position
    };
  });
  console.log('Juxtapose element:', juxtaposeInfo);

  // Check jx-image elements
  const jxImageInfo = await page.evaluate(() => {
    const els = document.querySelectorAll('.jx-image');
    return Array.from(els).map((el, i) => {
      const computed = window.getComputedStyle(el);
      const rect = el.getBoundingClientRect();
      const img = el.querySelector('img');
      const imgRect = img ? img.getBoundingClientRect() : null;
      return {
        index: i,
        height: rect.height,
        width: rect.width,
        overflow: computed.overflow,
        imgHeight: imgRect ? imgRect.height : null,
        imgWidth: imgRect ? imgRect.width : null
      };
    });
  });
  console.log('Jx-image elements:', jxImageInfo);

  await browser.close();
})();
