(() => {
  const assetRoot = '../wordpress/wp-content/themes/apexik-automobilist/assets/images/';
  const products = [
    { title: 'Abu Dhabi 2021 — Lap 58', category: 'Desk Mat', slug: 'desk-mats', story: 'pit-wall', price: '€39', image: 'desk-mat-abu-dhabi-2021-artwork.png' },
    { title: 'Hold Position?', category: 'Clothing', slug: 't-shirts', story: 'team-radio', price: '€29', image: 'product-tshirt.png' },
    { title: 'Box This Lap', category: 'Sticker', slug: 'stickers', story: 'paddock-review', price: '€9', image: 'product-stickers.png' },
    { title: 'One Lap Too Late', category: 'Key Chain', slug: 'keychains', story: 'driver-talk', price: '€14', image: 'product-keychain.png' },
    { title: 'Paddock Review', category: 'Curated Find', slug: 'curated-finds', story: 'paddock-review', price: '€24', image: 'product-curated-mug.png' }
  ];
  const icon = (name) => {
    const paths = { menu: '<path d="M3 6h18M3 12h18M3 18h18"/>', close: '<path d="M5 5l14 14M19 5L5 19"/>', search: '<circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>', user: '<circle cx="12" cy="8" r="4"/><path d="M4 21c.7-4.2 3.4-6.3 8-6.3s7.3 2.1 8 6.3"/>', bag: '<path d="M5 8h14l-1 13H6L5 8Z"/><path d="M9 9V6a3 3 0 0 1 6 0v3"/>', chevron: '<path d="m8 10 4 4 4-4"/>', arrow: '<path d="M5 12h14M14 7l5 5-5 5"/>' };
    return `<svg class="apx-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="square">${paths[name]}</svg>`;
  };
  const header = `
    <a class="apx-skip-link" href="#main">Skip to content</a>
    <div class="apx-announcement" data-announcement><div class="apx-shell apx-announcement__inner"><p><strong>OPENING GRID</strong> Free tracked delivery over €75 · placeholder offer</p><div class="apx-announcement__meta"><span>Worldwide delivery</span><button type="button" data-announcement-close aria-label="Close announcement">${icon('close')}</button></div></div></div>
    <header class="apx-header" data-header><div class="apx-shell apx-header__inner">
      <button class="apx-header__menu" type="button" data-menu-open aria-controls="apx-mobile-menu" aria-expanded="false">${icon('menu')}<span>Menu</span></button>
      <a class="apx-logo" href="index.html" aria-label="APEXIK home"><img src="../wordpress/wp-content/themes/apexik-automobilist/assets/images/apexik-logo-header.png" alt="APEXIK"></a>
      <nav class="apx-nav" aria-label="Primary navigation"><div class="apx-nav__shop"><button type="button" data-mega-trigger aria-expanded="false">Shop ${icon('chevron')}</button><div class="apx-mega" data-mega-menu><div class="apx-shell apx-mega__grid"><div class="apx-mega__intro"><h2>Shop by product</h2><p>One design system, built for different objects.</p></div><ul><li><a href="shop.html?category=desk-mats"><span>01</span>Desk Mats</a></li><li><a href="shop.html?category=t-shirts"><span>02</span>Clothing</a></li><li><a href="shop.html?category=stickers"><span>03</span>Stickers</a></li><li><a href="shop.html?category=keychains"><span>04</span>Key Chains</a></li><li><a href="shop.html?category=curated-finds"><span>05</span>Curated Finds</a></li></ul><a class="apx-mega__feature apx-art apx-art--red" href="shop.html"><span>All products</span><strong>SHOP THE FULL GRID</strong><small>Open Shop All ${icon('arrow')}</small></a></div></div></div><a href="index.html#today-design" data-home-anchor="today-design">Today’s Design</a><a href="index.html#race-moment-news" data-home-anchor="race-moment-news">Race Moment</a><a href="index.html#about" data-home-anchor="about">About</a><a href="index.html#faq" data-home-anchor="faq">FAQ</a><a href="index.html#contact" data-home-anchor="contact">Contact</a></nav>
      <div class="apx-header__actions"><a href="shop.html" aria-label="Search">${icon('search')}</a><a href="admin.html" aria-label="Management portal">${icon('user')}</a><button type="button" data-cart-open aria-controls="apx-cart-drawer" aria-expanded="false" aria-label="Open cart">${icon('bag')}<span class="apx-cart-count">0</span></button></div>
    </div></header>
    <div class="apx-promo-ticker" aria-label="Current offers"><div class="apx-promo-ticker__track"><span>Free shipping above $99</span><span>50% off selected stories</span><span>Made to order</span><span aria-hidden="true">Free shipping above $99</span><span aria-hidden="true">50% off selected stories</span><span aria-hidden="true">Made to order</span></div></div>
    <div class="apx-mobile-menu" id="apx-mobile-menu" data-mobile-menu hidden><div class="apx-mobile-menu__head"><span class="apx-logo"><span>APE</span><b>X</b><i>IK</i></span><button type="button" data-menu-close aria-label="Close menu">${icon('close')}</button></div><nav><a href="index.html#shop-by-format">Shop <span>01</span></a><a href="index.html#today-design">Today’s Design <span>02</span></a><a href="index.html#race-moment-news">Race Moment <span>03</span></a><a href="index.html#about">About <span>04</span></a><a href="index.html#faq">FAQ <span>05</span></a><a href="index.html#contact">Contact <span>06</span></a></nav></div>
    <aside class="apx-cart-drawer" id="apx-cart-drawer" data-cart-drawer aria-hidden="true"><div class="apx-cart-drawer__head"><h2>Your grid</h2><button type="button" data-cart-close aria-label="Close cart">${icon('close')}</button></div><div class="apx-cart-drawer__body"><div class="apx-cart-empty" data-preview-cart><span>0</span><p>No stories on your grid yet.</p><a class="apx-button" href="shop.html">Explore the shop</a></div></div></aside><div class="apx-overlay" data-overlay hidden></div>`;
  const footer = `
    <section class="apx-newsletter"><div class="apx-shell apx-newsletter__inner"><h2>The next story drops after the chequered flag.</h2><form class="apx-newsletter__form" data-newsletter-form novalidate><label for="preview-email">Email address</label><div><input id="preview-email" type="email" placeholder="fan@example.com" required><button type="submit">Join the grid ${icon('arrow')}</button></div><p class="apx-form-status" data-form-status aria-live="polite"></p></form></div></section>
    <section class="apx-trust-strip" aria-label="Customer review service"><div class="apx-shell apx-trust-strip__inner"><strong>Trustpilot</strong><span>Review score connects when the verified profile goes live.</span></div></section>
    <footer class="apx-footer"><div class="apx-shell">
      <div class="apx-footer__top"><div><a class="apx-logo apx-logo--footer" href="index.html"><span>APE</span><b>X</b><i>IK</i></a><p>Paddock culture, engineered into objects for everyday life.</p></div><nav class="apx-footer__social" aria-label="Social channels"><a href="#" aria-label="Instagram">IG</a><a href="#" aria-label="TikTok">TT</a><a href="#" aria-label="YouTube">YT</a><a href="#" aria-label="Pinterest">PT</a></nav></div>
      <div class="apx-footer__grid"><div><h3>Shop</h3><a href="shop.html?category=desk-mats">Desk Mats</a><a href="shop.html?category=t-shirts">Clothing</a><a href="shop.html?category=stickers">Stickers</a><a href="shop.html?category=keychains">Key Chains</a><a href="shop.html?category=curated-finds">Curated Finds</a></div><div><h3>Company</h3><a href="index.html#about">About Us</a><a href="race-moments.html">Race Moments</a><a href="about.html">Rights & Sources</a><a href="index.html#contact">Contact</a></div><div><h3>Help</h3><a href="about.html">Shipping & Delivery</a><a href="about.html">Returns</a><a href="about.html">Size Guide</a><a href="index.html#faq">FAQ</a></div><div><h3>Customer Service</h3><a href="index.html#contact">Contact Us</a><a href="about.html">Privacy Policy</a><a href="about.html">Terms & Conditions</a><a href="about.html">Cookie Settings</a></div></div>
      <div class="apx-footer__bottom"><span>© 2026 APEXIK</span><span>Worldwide delivery · duties calculated at checkout</span><div class="apx-footer__payments" aria-label="Accepted payment methods"><span>VISA</span><span>MC</span><span>AMEX</span><span>PAYPAL</span><span>APPLE PAY</span></div></div>
    </div></footer>`;
  const card = (product, index, linkMode = 'format') => {
    const href = linkMode === 'product' ? 'product.html' : linkMode === 'story' ? `shop.html?story=${product.story}` : `shop.html?category=${product.slug}`;
    return `<article class="apx-product-card" data-category="${product.slug}" data-story="${product.story}"><a class="apx-product-card__visual apx-product-card__visual--photo" href="${href}" aria-label="Open ${product.title}"><img src="${assetRoot}${product.image}" alt="${product.title} ${product.category}"></a><div class="apx-product-card__body"><div><p>${product.category}</p><h3><a href="product.html">${product.title}</a></h3></div><strong>${product.price}</strong></div></article>`;
  };

  document.querySelector('[data-preview-header]')?.insertAdjacentHTML('beforeend', header);
  document.querySelector('[data-preview-footer]')?.insertAdjacentHTML('beforeend', footer);
  document.querySelectorAll('.apx-moment-row a[href="product.html"]').forEach((link) => { link.href = 'moment.html'; });
  document.querySelectorAll('[data-preview-products]').forEach((host) => {
    const limit = Number(host.dataset.previewProducts || products.length);
    const linkMode = host.dataset.linkMode || (host.closest('#paddock-fun') ? 'story' : 'format');
    host.innerHTML = products.slice(0, limit).map((product, index) => card(product, index, linkMode)).join('');
  });
  window.apxPreviewProducts = products;

  let cartCount = 0;
  document.addEventListener('click', (event) => {
    const remove = event.target.closest('[data-cart-remove]');
    if (remove) { event.preventDefault(); remove.closest('[data-cart-item]')?.remove(); document.dispatchEvent(new CustomEvent('apx:cart-change')); }
    const thumb = event.target.closest('[data-preview-thumb]');
    if (thumb) { thumb.parentElement.querySelectorAll('[data-preview-thumb]').forEach((button) => button.classList.toggle('is-active', button === thumb)); const main = document.querySelector('[data-preview-main-image]'); if (main && thumb.dataset.previewImage) { main.src = thumb.dataset.previewImage; main.alt = thumb.dataset.previewAlt || main.alt; } }
    if (event.target.closest('[data-preview-add]')) { cartCount += 1; document.querySelectorAll('.apx-cart-count').forEach((item) => { item.textContent = String(cartCount); }); const cart = document.querySelector('[data-preview-cart]'); if (cart) cart.innerHTML = `<span>${cartCount}</span><p>Abu Dhabi 2021 — Lap 58 Desk Mat<br>600 × 300 mm · Style A · ${cartCount} item</p><a class="apx-button" href="cart.html">View cart</a>`; document.querySelector('[data-cart-open]')?.click(); }
    if (event.target.closest('[data-test-order]')) { const form = document.querySelector('[data-preview-checkout]'); const status = document.querySelector('[data-order-status]'); if (form && !form.reportValidity()) return; if (status) status.textContent = 'Test order confirmed. No payment was charged and nothing was sent to Printful.'; }
  });
  const updatePreviewCart = () => {
    const items = [...document.querySelectorAll('[data-cart-item]')]; let subtotal = 0;
    items.forEach((item) => { const qty = Math.max(1, Number(item.querySelector('[data-cart-qty]')?.value || 1)); const price = Number(item.dataset.unitPrice || 0); subtotal += qty * price; const line = item.querySelector('[data-line-total]'); if (line) line.textContent = `€${(qty * price).toFixed(2)}`; });
    document.querySelectorAll('[data-cart-subtotal]').forEach((item) => { item.textContent = `€${subtotal.toFixed(2)}`; });
    const empty = document.querySelector('[data-cart-empty-state]'); if (empty) empty.hidden = items.length > 0;
  };
  document.addEventListener('input', (event) => { if (event.target.matches('[data-cart-qty]')) updatePreviewCart(); });
  document.addEventListener('apx:cart-change', updatePreviewCart);
  updatePreviewCart();

  // Product gallery: desktop hover detail and mobile touch-to-zoom, using the existing source image.
  document.querySelectorAll('.preview-product__main-image').forEach((frame) => {
    const image = frame.querySelector('img');
    if (!image) return;
    frame.addEventListener('pointermove', (event) => {
      if (event.pointerType === 'touch') return;
      const rect = frame.getBoundingClientRect();
      image.style.transformOrigin = `${((event.clientX - rect.left) / rect.width) * 100}% ${((event.clientY - rect.top) / rect.height) * 100}%`;
      frame.classList.add('is-zoomed');
    });
    frame.addEventListener('pointerleave', () => frame.classList.remove('is-zoomed'));
    frame.addEventListener('click', () => { if (window.matchMedia('(hover: none)').matches) frame.classList.toggle('is-zoomed'); });
  });

  document.querySelectorAll('.preview-cart-item__art').forEach((art) => {
    const item = art.closest('.preview-cart-item');
    const title = item?.querySelector('h2')?.textContent || '';
    const image = title.includes('Sticker') ? 'product-stickers.png' : 'desk-mat-abu-dhabi-2021-artwork.png';
    art.innerHTML = `<img src="${assetRoot}${image}" alt="${title.replace(/"/g, '&quot;')}">`;
  });

  const shopCategory = new URLSearchParams(window.location.search).get('category');
  const shopTitle = document.querySelector('.apx-shop-intro h1');
  if (shopTitle && shopCategory) shopTitle.textContent = 'All Category';
  const shopTabs = document.querySelector('.preview-shop-layout .apx-tabs');
  if (shopTabs && !shopTabs.querySelector('[data-product-tab="curated-finds"]')) shopTabs.insertAdjacentHTML('beforeend', '<button id="shop-tab-curated-finds" role="tab" aria-controls="shop-product-panel" tabindex="-1" aria-selected="false" data-product-tab="curated-finds">Curated Finds</button>');
})();
