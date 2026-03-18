# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).
  
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