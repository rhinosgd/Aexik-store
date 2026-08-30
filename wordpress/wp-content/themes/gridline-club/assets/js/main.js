(() => {
  const toggle = document.querySelector('[data-menu-toggle]');
  const menu = document.querySelector('[data-menu]');
  const closeMenu = () => {
    if (!toggle || !menu) return;
    toggle.setAttribute('aria-expanded', 'false');
    menu.classList.remove('is-open');
    document.body.classList.remove('menu-open');
  };

  if (toggle && menu) {
    toggle.addEventListener('click', () => {
      const open = toggle.getAttribute('aria-expanded') === 'true';
      toggle.setAttribute('aria-expanded', String(!open));
      menu.classList.toggle('is-open', !open);
      document.body.classList.toggle('menu-open', !open);
    });
    menu.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeMenu));
  }

  const searchTrigger = document.querySelector('[data-search-open]');
  const searchDrawer = document.querySelector('[data-search-drawer]');
  const searchInput = searchDrawer?.querySelector('input[type="search"]');
  let returnFocus = null;

  const closeSearch = () => {
    if (!searchDrawer || searchDrawer.getAttribute('aria-hidden') === 'true') return;
    searchDrawer.setAttribute('aria-hidden', 'true');
    searchTrigger?.setAttribute('aria-expanded', 'false');
    document.body.classList.remove('search-open');
    returnFocus?.focus();
  };

  const openSearch = () => {
    if (!searchDrawer) return;
    closeMenu();
    returnFocus = document.activeElement;
    searchDrawer.setAttribute('aria-hidden', 'false');
    searchTrigger?.setAttribute('aria-expanded', 'true');
    document.body.classList.add('search-open');
    window.setTimeout(() => searchInput?.focus(), 40);
  };

  searchTrigger?.addEventListener('click', openSearch);
  searchDrawer?.querySelectorAll('[data-search-close]').forEach((button) => button.addEventListener('click', closeSearch));
  searchDrawer?.addEventListener('keydown', (event) => {
    if (event.key !== 'Tab') return;
    const controls = [...searchDrawer.querySelectorAll('button, a, input')].filter((item) => !item.disabled && item.offsetParent !== null);
    if (!controls.length) return;
    const first = controls[0];
    const last = controls[controls.length - 1];
    if (event.shiftKey && document.activeElement === first) {
      event.preventDefault();
      last.focus();
    } else if (!event.shiftKey && document.activeElement === last) {
      event.preventDefault();
      first.focus();
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') return;
    closeSearch();
    closeMenu();
  });

  document.querySelectorAll('[data-accordion] button').forEach((button, index) => {
    const panel = button.nextElementSibling;
    if (!panel) return;
    const panelId = panel.id || `gridline-accordion-${index + 1}`;
    panel.id = panelId;
    button.setAttribute('aria-controls', panelId);
    panel.hidden = button.getAttribute('aria-expanded') !== 'true';
    button.addEventListener('click', () => {
      const expanded = button.getAttribute('aria-expanded') === 'true';
      button.setAttribute('aria-expanded', String(!expanded));
      panel.hidden = expanded;
    });
  });

  document.querySelectorAll('[data-product-gallery]').forEach((gallery) => {
    const mainImage = gallery.querySelector('[data-gallery-main]');
    const thumbnails = [...gallery.querySelectorAll('[data-gallery-src]')];
    if (!mainImage || !thumbnails.length) return;
    thumbnails.forEach((thumbnail) => {
      thumbnail.addEventListener('click', () => {
        const nextSource = thumbnail.getAttribute('data-gallery-src');
        if (!nextSource) return;
        mainImage.setAttribute('src', nextSource);
        thumbnails.forEach((item) => item.classList.toggle('is-active', item === thumbnail));
      });
    });
  });

  const staticRadioArchives = {
    'Must Be the Water Radio Polo': {
      channel: 'AUS 25 / RADIO ARCHIVE', highlight: 'Must be the water.',
      lines: [['CAR', 'Is there a leakage?'], ['PIT WALL', 'A leakage where?'], ['CAR', 'I have the seat full of water!'], ['PIT WALL', 'Must be the water.'], ['CAR', "Let's add that to the words of wisdom."]]
    },
    'Must Be the Water Radio Mug': {
      channel: 'AUS 25 / RADIO ARCHIVE', highlight: 'Must be the water.',
      lines: [['CAR', 'I have the seat full of water!'], ['PIT WALL', 'Must be the water.'], ['CAR', "Let's add that to the words of wisdom."]]
    },
    'GP2 Engine Pit Wall Hoodie': {
      channel: 'JPN 15 / RADIO ARCHIVE', highlight: 'GP2 engine, GP2.',
      lines: [['CAR', 'They pass me on the straights like in GP2.'], ['CAR', 'It is embarrassing, very embarrassing.'], ['CAR', 'GP2 engine, GP2. Aaargh!']]
    },
    'Simply Lovely Insulated Tumbler': {
      channel: 'NED 24 / CHEQUERED FLAG', highlight: 'Simply lovely',
      lines: [['CAR', 'Uh huh. Simply lovely, huh? Well done.']]
    },
    'Change Your FXXking Car Desk Mat': {
      channel: 'PIT WALL / UNFILTERED', highlight: 'change your f***ing car',
      lines: [['MEETING', 'I have a printout here.'], ['MEETING', "If you've got a problem, change your f***ing car."]]
    }
  };

  const addHighlight = (node, message, highlight) => {
    const position = message.toLowerCase().indexOf(highlight.toLowerCase());
    if (position < 0) {
      node.append(document.createTextNode(message));
      return;
    }
    node.append(document.createTextNode(message.slice(0, position)));
    const strong = document.createElement('strong');
    strong.textContent = message.slice(position, position + highlight.length);
    node.append(strong, document.createTextNode(message.slice(position + highlight.length)));
    node.classList.add('is-highlight');
  };

  document.querySelectorAll('.woocommerce ul.products li.product').forEach((card) => {
    if (document.querySelector('.gl-rs-product')) return;
    if (card.querySelector('.gl-radio-card-media')) return;
    const title = card.querySelector('.woocommerce-loop-product__title')?.textContent.trim();
    const archive = staticRadioArchives[title];
    const image = card.querySelector('.woocommerce-loop-product__link > img, a > img');
    if (!archive || !image) return;
    const media = document.createElement('div');
    media.className = 'gl-radio-card-media';
    image.before(media);
    media.append(image);

    const transcript = document.createElement('div');
    transcript.className = 'gl-radio-card-transcript';
    const head = document.createElement('div');
    head.className = 'gl-radio-card-head';
    head.innerHTML = `<span class="gl-radio-live">LIVE</span><span>${archive.channel}</span>`;
    const lines = document.createElement('div');
    lines.className = 'gl-radio-card-lines';
    archive.lines.forEach(([speaker, message]) => {
      const row = document.createElement('p');
      const label = document.createElement('span');
      label.textContent = speaker;
      row.append(label);
      addHighlight(row, message, archive.highlight);
      lines.append(row);
    });
    const foot = document.createElement('small');
    foot.textContent = 'FULL CONTEXT / HOVER ARCHIVE';
    const wave = document.createElement('div');
    wave.className = 'gl-radio-wave';
    wave.setAttribute('aria-hidden', 'true');
    const waveImage = document.createElement('img');
    waveImage.src = '../wp-content/themes/gridline-club/assets/images/ui/apexik-radio-wave.png';
    waveImage.alt = '';
    wave.append(waveImage);
    transcript.append(head, wave, lines, foot);
    media.append(transcript);
  });

})();
