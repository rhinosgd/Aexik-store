# APEXIK Store Repository

This private repository stores the reusable WordPress/WooCommerce build for APEXIK.

## What Is Included

- `application/` - the latest APEXIK storefront and custom operations admin source (Next/Vinext, D1/R2-ready).
- `wordpress/wp-content/themes/gridline-club/` - the custom APEXIK WooCommerce theme.
- `wordpress/wp-content/plugins/gridline-starter/` - starter content, products, admin hub, demo assets, and store setup helpers.
- `wordpress/wp-content/plugins/apexik-seo-geo/` - SEO/GEO content structure, landing pages, schema, and metadata tools.
- `wordpress/packages/` - installable ZIP packages for the theme and plugins.
- `wordpress/deploy/` - server-side reference config files.
- `wordpress/preview/` - static preview pages for visual reference.

## What Is Not Included

This repository must not contain production passwords, SSH private keys, Cloudflare tokens, SMTP passwords, database dumps, or `/root/apexik-credentials.txt`.

## Current Production

- Domain: `apexik.com`
- Platform: WordPress + WooCommerce
- Server: Hetzner CX23, Nuremberg
- Public website: https://apexik.com

## Quick Restore

1. Install a clean WordPress site with WooCommerce.
2. Upload and activate `wordpress/packages/apexik-theme.zip`.
3. Upload and activate `wordpress/packages/apexik-starter-plugin.zip`.
4. Upload and activate `wordpress/packages/apexik-seo-geo-plugin.zip`.
5. In WordPress admin, run the APEXIK setup/import screen.
6. Configure domain, SSL, payment, shipping, tax, email, analytics, and real product inventory on the target server.

More detail is in `docs/deployment-guide.md`.
