# APEXIK Deployment Guide

## Repository Purpose

This repository is the portable build package for the APEXIK WordPress store. It is designed so a new device, developer, or AI agent can recover the current theme/plugin source and installable ZIP files without touching server credentials.

## Folder Map

- `wordpress/packages/apexik-theme.zip` - upload through WordPress Admin > Appearance > Themes > Add New > Upload Theme.
- `wordpress/packages/apexik-starter-plugin.zip` - upload through WordPress Admin > Plugins > Add New > Upload Plugin.
- `wordpress/packages/apexik-seo-geo-plugin.zip` - upload through WordPress Admin > Plugins > Add New > Upload Plugin.
- `wordpress/wp-content/themes/gridline-club/` - editable source for the live theme.
- `wordpress/wp-content/plugins/gridline-starter/` - editable source for product/content setup.
- `wordpress/wp-content/plugins/apexik-seo-geo/` - editable source for SEO/GEO structure.
- `wordpress/deploy/nginx-apexik.conf` - Nginx reference config.
- `wordpress/deploy/apexik-php.ini` - PHP reference overrides.

## Clean Server Setup

1. Create a WordPress server with PHP 8.3+, MySQL 8 or MariaDB 10.11+, Nginx, and HTTPS.
2. Install WooCommerce.
3. Set WordPress permalinks to `Post name`.
4. Install the three ZIP packages from `wordpress/packages/`.
5. Activate the APEXIK theme and both plugins.
6. Run the starter setup/import screen in WordPress admin.
7. Verify the homepage, shop page, product pages, cart, checkout, account, contact page, and SEO landing pages.

## Production Settings Still Required

- Payment gateway credentials.
- Shipping zones and carrier rates.
- Tax/VAT policy.
- Real sender email or SMTP provider.
- Product stock, lead time, and supplier-confirmed specifications.
- Legal pages reviewed for the operating company.
- Analytics, Search Console, Merchant Center, and consent/cookie tooling.

## Security Rules

Never commit:

- `/root/apexik-credentials.txt`
- SSH private keys
- WordPress admin passwords
- Database passwords
- Cloudflare API tokens
- SMTP passwords
- Full database dumps
- `wp-config.php` containing real credentials

Use this repository for code and install packages only.
