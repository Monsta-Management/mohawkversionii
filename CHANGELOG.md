# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).
  
---

## [2.4.0] - 2026-06-12
### Improved
- Updated product variation image selection `product_image_vartiant()` to prioritise Gold medal variants on archive and shop pages.
- Aligned variation image ordering with existing medal colour sorting logic for consistent swatch and image display.
- Removed dependency on image filename patterns for colour detection.
- Preserved default WooCommerce variation ordering for non-medal products.
- Improved WooCommerce compatibility and added safeguards for missing variation data.

---

## [2.3.2] - 2026-06-11
### Improved
- replace base theme `screenshot.png` appropriately. 

---

## [2.3.1] - 2026-05-19
### Added
- Added product-level STAR RANKING prioritization system.
- Added support for scheduled featured ranking periods using:
  - `_trophymonsta_star_ranking`
  - `_trophymonsta_start_date`
  - `_trophymonsta_end_date`
- Added active date-range validation for temporary ranking campaigns.
- Added automatic prioritization of active ranked products above supplier/media sorting.
- Added fallback-safe ranking behavior when start/end dates are empty.
- Added transient-aware STAR RANKING integration into cached WooCommerce product sorting.

---

## [2.3.0] - 2026-05-19
### Changed
- Final production fixes from RC testing.

---

## [2.3.0-rc.1] - 2026-05-19
### Added
- Implemented GitHub-based automatic theme updates using the Plugin Update Checker (PUC) library.
- Added spinning image/video bucket-hosted support with `trophymonstavalidator` plugin integration.
- Added frontend support for validated `_trophymonsta_image`.
- Added ACF toggle `Disable Hover Submenu` to switch menu dropdown behavior from hover to click.
- Added submenu caret indicators when hover mode is disabled.
- Added ACF Category Settings:
  - Parent category slug
  - Custom child category ordering
- Added dynamic category ordering across:
  - shortcodes
  - menu walker
  - category lists
- Added `handleMainMenuCarousel()` support to the main navigation menu.
- Added dynamic product mark logo support using ACF `site_product_mark_logo`.
- Added smart fallback support for `[acf_featured_products]` shortcode.
- Added `Monsta Unified Core` ACF Bulk Pricing integration to WooCommerce product card templates.
- Added `[custom_catalogues]` shortcode.
- Added `[custom_gallery]` shortcode with Lightbox support.

### Changed
- Refactored product sorting and ranking system.
- Simplified supplier-based product grouping logic.
- Prioritized:
  - supplier ranking
  - NEW products
  - media-enhanced products
- Improved stable product ordering consistency.
- Replaced JS-based sticky sidebar handling with native CSS `position: sticky`.
- Parent theme styles are no longer automatically loaded when using child themes.
- Child themes are now fully responsible for site-specific styling.
- Removed manual CSS class injections from `content-product-card.php`.

### Improved
- Improved child theme compatibility and parent version detection.
- Improved ACF auto-import reliability.
- Improved frontend handling for sites without S3 media data.
- Improved product ranking reliability across multiple categories.
- Improved session handling and cache stability.
- Reduced WooCommerce query complexity for better performance.
- Added transient caching to reduce repeated heavy database queries.
- Improved submenu JavaScript handling based on ACF settings.
- Moved validator plugin JS/CSS assets into the base theme.
- Improved general theme style compatibility.
- Optimized:
  - `inc/woocommerce.php`
  - `woocommerce/content-product-card.php`
- Lightbox2 is now globally available instead of WooCommerce-only.

### Fixed
- Fixed outdated WooCommerce template overrides.
- Fixed hardcoded category slug handling using ACF settings.
- Fixed `archive-product.php` sidebar markup issues.
- Fixed `archive-product.php` sidebar ACF logic.
- Fixed deprecated WooCommerce function usage.
- Fixed legacy `reset()` warnings.
- Fixed PHP fatal error when saving WordPress menus caused by incorrect meta hook argument handling.
- Fixed crashes caused by incomplete `deleted_postmeta` hook arguments.
- Fixed undefined `$term` warning in `[show_related_product]` shortcode.
- Removed unsafe manual term loop logic.
- Replaced deprecated `$product->id` usage with `$product->get_id()`.
- Fixed flagged issues:
  - `template-parts/subcategories-order.php`
  - `inc/accessories.php`

### Reverted
- Reverted `productsInfiniteResult()` AJAX implementation due to performance-related issues.

### Internal
- Tested Plugin Update Checker (PUC) reliability.
- Improved overall theme maintainability and WooCommerce compatibility.

---

## [2.2.12-alpha] - 2026-05-11
### Fixed
- fixed PHP warning caused by undefined `$term` variable on `[show_related_product]` shorcode.
- removed unsafe manual term loop logic.
- replaced deprecated `$product->id` usage with `$product->get_id()`.

---

## [2.2.11-alpha] - 2026-04-27
### Fixed
- fix flagged `BUG 1` and `BUG 2` with George
  - BUG 1 — template-parts/subcategories-order.php (lines 41–42)
  - BUG 2 — inc/accessories.php (line 46)

---

## [2.2.10-alpha] - 2026-04-20
### Fixed
- Resolved PHP fatal error when saving WordPress menus caused by incorrect argument handling in `clear_sorted_product_cache_on_meta_update()`.
- Prevented crashes from `deleted_postmeta` hook when fewer arguments are passed than expected.

---

## [2.2.9-alpha] - 2026-04-20
### Fixed
- implement `[custom_gallery]` lightbox.

---

## [2.2.8-alpha] - 2026-04-20
### Added
- implement `[custom_catalogues]` and `[custom_gallery]` shortcodes.

---

## [2.2.7-alpha] - 2026-04-15
### Improved
- optimize and improve product ranking.

---

## [2.2.6-alpha] - 2026-04-15
### Fixed
- fix `reset()` and WooCommerce legacy/deprecated functions.
- optimize `mohawkversionii/inc/woocommerce.php` and `mohawkversionii/woocommerce/content-product-card.php`

---

## [2.2.5-alpha] - 2026-04-15
### Revert
- revert `productsInfiniteResult()` ajax, it causes some performance bugs.

---

## [2.2.4-alpha] - 2026-04-15
### Improved
- mohawkversionii styles compability 1.

---

## [2.2.3-alpha] - 2026-04-15
### Changed
- simplified product sorting logic to prioritize supplier clustering and NEW products.
- ensured strict grouping of products by supplier (no more interleaving between suppliers).
- maintained consistent ordering: supplier rank → NEW products → stable fallback.

### Improved
- improved reliability for sites without S3 data (graceful handling of missing meta).
- more consistent ordering and better performance with reduced query complexity.

### Performance
- reduced query complexity by removing unnecessary ranking conditions.
- retained transient caching to prevent repeated heavy database queries.

---

## [2.2.2-alpha] - 2026-04-08
### Added
- hook `Monsta Unified Core` `ACF Bulk Pricing` feature on WooCommerce `content-product-card.php` override template.

---

## [2.2.1-alpha] - 2026-04-06
### Added
- Smart featured products shortcode fallback:
  - `[acf_featured_products]` now automatically falls back to default featured products when no ACF selection is set.
  - unified logic ensures frontend always displays products without empty states.

---

## [2.2.0-alpha] - 2026-04-02
### Improved
- refactor product sort order:
    - Rank → NEW + S3 Video OR NEW + S3 Image → 9
    - NEW + S3 Video OR NEW + Local Image → 8
    - NOT NEW + S3 Video OR NOT NEW + S3 Image → 7
    - NOT NEW + S3 Video OR NOT NEW + Local Image → 6
    - NEW + NO S3 Video + NEW + S3 Image → 5
    - NEW + NO S3 Video + NEW + Local Image → 4
    - NOT NEW + NO S3 Video + NOT NEW + S3 Image → 3
    - NOT NEW + NO S3 Video + NOT NEW + Local Image → 2
    - Others → 1
- improved product ranking system to include all products, handle multiple category rankings correctly, and ensure consistent results.
- fixed session handling, duplicate entries, and missing rank issues while adding caching for better performance.

---

## [2.1.2] - 2026-04-02
### Improved
- moved JS and CSS from `trophymonstavalidator` to `mohawkversionii` base code.

---

## [2.1.1] - 2026-04-02
### Added
- use the validated `_trophymonsta_image` by `trophymonstavalidator` plugin on the frontend.

---

## [2.1.0] - 2026-03-30
### Added
- spinning image/video `bucket hosted` support with still `trophymonstavalidator` plugin dependent.

---

## [2.0.12] - 2026-03-24
### Improved
- enqueue Lightbox2 global and not only for WooCommerce product only.

---

## [2.0.11] - 2026-03-23
### Added
- ACF toggle `Disable Hover Submenu` to switch main menu dropdowns from hover to click.
- Caret icon for submenu items when hover is disabled.

### Improved
- `headerSubmenu()` JS function updated to respect the ACF toggle.

---

## [2.0.10] - 2026-03-19
### Added
- added **ACF Category Settings** (parent slug + custom child category order)
- implemented dynamic category ordering across shortcode, menu walker, and category lists

---

## [2.0.9] - 2026-03-18
### Added
- added `handleMainMenuCarousel()` to the header main menu.

---

## [2.0.8] - 2026-03-18
### Improved
- added dynamic product mark logo using ACF `site_product_mark_logo` field for `.row-products .product-item-wrap`.
- removed manual class additions in `content-product-card.php`; CSS now handled automatically via inline style.

---

## [2.0.7] - 2026-03-18
### Fixed
- fix `archive-product.php` fix sidebar ACF logic.

---

## [2.0.6] - 2026-03-17
### Fixed
- fix `archive-product.php` sidebar markup.

---

## [2.0.5] - 2026-03-17
### Added
- **Child theme support improvements** updated logic to fetch parent theme version correctly even when a child theme is active, ensuring ACF auto-import works reliably.

### Changed
- parent theme styles (`style.css` / `style.min.css`) no longer loaded if a child theme is active.
- `mohawkversionii-child` theme are now responsible for all site-specific styles.
- removed JS driven `#with-sidebar` and convert to css `position: sticky;`.

### Fixed
- improved performance by preventing redundant style and script loading.

---

## [2.0.4] - 2026-03-17
### Improved
- enhanced compatibility with child themes by correctly detecting the parent theme version.

---

## [2.0.3] - 2026-03-17
### Fixed
- fix category slug via ACF `Grr Options > Category Settings` instead of hard coding it.

---

## [2.0.2] - 2026-03-17
### Fixed
- fix outdated copies of some WooCommerce template files.

---

## [2.0.1] - 2026-03-17
### Improved
- testing PUC reliability.

---

## [2.0.0] - 2026-03-12
### Added
- Implemented **GitHub-based automatic theme updates** using the [YahnisElsts Plugin Update Checker (PUC)](https://github.com/YahnisElsts/plugin-update-checker) library by Monsta John.