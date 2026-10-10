INSERT OR IGNORE INTO categories (id, slug, name, sort_order, is_published) VALUES
('cat-desk-mats', 'desk-mats', 'Desk Mats', 1, 1),
('cat-t-shirts', 't-shirts', 'Clothing', 2, 1),
('cat-stickers', 'stickers', 'Stickers', 3, 1),
('cat-keychains', 'keychains', 'Key Chains', 4, 1),
('cat-curated-finds', 'curated-finds', 'Curated Finds', 5, 1);

INSERT OR IGNORE INTO products (id, category_id, slug, title, description, story, price, status) VALUES
('prod-undercut', 'cat-desk-mats', 'the-undercut-window-desk-mat', 'The Undercut Window Desk Mat', 'A race-day story engineered into a desk object.', 'A safety-car restart turned a measured gap into one of the most debated final laps in modern racing.', 39.99, 'published'),
('prod-rain-line', 'cat-t-shirts', 'rain-line-last-corner-tee', 'Rain Line / Last Corner Tee', 'A championship swing printed as a timing split.', 'A championship seemed settled until the last corner, the rain, and a position that changed hands twice.', 39.99, 'published'),
('prod-safety-car', 'cat-stickers', 'wet-race-safety-car-sticker-set', 'Wet Race / Safety Car Sticker Set', 'Five die-cut pieces for the rain radar, pit lane and notebook.', 'A race that kept changing its mind: rain radar, pit-lane arrows and a safety-car lamp become five die-cut pieces.', 39.99, 'published'),
('prod-radio-wave', 'cat-keychains', 'radio-wave-steering-key-chain', 'Radio Wave Steering Key Chain', 'A team-radio pressure moment translated into a small metal object.', 'When a radio message became the story, the object became a waveform.', 39.99, 'published'),
('prod-rain-check', 'cat-curated-finds', 'rain-check-pit-wall-mug', 'Rain Check Pit Wall Mug', 'A weather gamble for the late-night replay.', 'A soaked street circuit turns the pit wall into a weather station.', 39.99, 'published');

INSERT OR IGNORE INTO variants (id, product_id, style, size) VALUES
('var-undercut-signal', 'prod-undercut', 'Signal red', '900 × 400 mm'),
('var-rain-line-cream', 'prod-rain-line', 'Warm cream', 'M'),
('var-safety-radar', 'prod-safety-car', 'Rain radar', '5-piece set'),
('var-radio-steel', 'prod-radio-wave', 'Steel', '38 mm'),
('var-rain-radar', 'prod-rain-check', 'Radar red', '350 ml');

INSERT OR IGNORE INTO skus (id, variant_id, code) VALUES
('sku-undercut-signal', 'var-undercut-signal', 'APX-DM-UNDERCUT-RED-900'),
('sku-rain-line-cream', 'var-rain-line-cream', 'APX-TEE-RAINLINE-CREAM-M'),
('sku-safety-radar', 'var-safety-radar', 'APX-STICKER-WETRACE-SET'),
('sku-radio-steel', 'var-radio-steel', 'APX-KEY-RADIO-STEEL-38'),
('sku-rain-radar', 'var-rain-radar', 'APX-MUG-RAINCHECK-RED-350');

INSERT OR IGNORE INTO inventory (id, sku_id, available, reserved, reorder_point) VALUES
('inv-undercut', 'sku-undercut-signal', 25, 0, 5),
('inv-rain-line', 'sku-rain-line-cream', 25, 0, 5),
('inv-safety', 'sku-safety-radar', 25, 0, 5),
('inv-radio', 'sku-radio-steel', 25, 0, 5),
('inv-rain', 'sku-rain-radar', 25, 0, 5);

INSERT OR IGNORE INTO product_images (id, product_id, kind, source_type, src, alt, width, height, sort_order) VALUES
('img-undercut-main', 'prod-undercut', 'product', 'local', '/products/undercut-desk-mat.png', 'The Undercut Window Desk Mat', 1600, 1600, 1),
('img-undercut-alt', 'prod-undercut', 'product', 'local', '/products/reference/desk-mat.png', 'Desk mat artwork detail', 1600, 1600, 2),
('img-rain-line-main', 'prod-rain-line', 'product', 'local', '/products/rain-line-tee.png', 'Rain Line Last Corner Tee', 1600, 1600, 1),
('img-rain-line-alt', 'prod-rain-line', 'product', 'local', '/products/reference/tshirt.png', 'T-shirt detail', 1600, 1600, 2),
('img-safety-main', 'prod-safety-car', 'product', 'local', '/products/safety-car-stickers.png', 'Wet Race Safety Car Sticker Set', 1600, 1600, 1),
('img-safety-alt', 'prod-safety-car', 'product', 'local', '/products/reference/stickers.png', 'Sticker set detail', 1600, 1600, 2),
('img-radio-main', 'prod-radio-wave', 'product', 'local', '/products/radio-wave-keychain.png', 'Radio Wave Steering Key Chain', 1600, 1600, 1),
('img-radio-alt', 'prod-radio-wave', 'product', 'local', '/products/reference/keychain.png', 'Key chain detail', 1600, 1600, 2),
('img-rain-main', 'prod-rain-check', 'product', 'local', '/products/rain-check-mug.png', 'Rain Check Pit Wall Mug', 1600, 1600, 1),
('img-rain-alt', 'prod-rain-check', 'product', 'local', '/products/reference/mug.png', 'Mug detail', 1600, 1600, 2);
