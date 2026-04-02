# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).
  
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