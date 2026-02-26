# Mohawk V2 - Performance Audit & Speed Optimisation Recommendations

## Summary

After reviewing all PHP templates, CSS, JavaScript, images, and font assets in this theme, I've identified **15 actionable optimisations** across 5 categories. The most impactful changes relate to CSS/JS loading strategy, redundant library loading, and unminified assets.

---

## 1. CSS — Render-Blocking Overload (~770KB unminified)

### 1a. `style.css` is unminified (279KB)
**File:** `style.css`
**Impact:** HIGH
The main stylesheet is 279KB of unminified CSS. A minified version would likely be ~180-200KB. Add a build step (e.g., `sass --style=compressed`) or use a minification plugin. The `.sass-cache` directory exists, suggesting Sass is already in use — just compile with `--style=compressed`.

### 1b. `bootstrap.css` loaded instead of `bootstrap.min.css` (193KB vs 155KB)
**File:** `functions.php`, line 206
**Impact:** MEDIUM
```php
// CURRENT — loads unminified bootstrap
wp_enqueue_style( 'mohawkversionii-bootstrap', get_template_directory_uri() . '/inc/bootstrap/css/bootstrap.css', ...);

// RECOMMENDED — switch to minified
wp_enqueue_style( 'mohawkversionii-bootstrap', get_template_directory_uri() . '/inc/bootstrap/css/bootstrap.min.css', ...);
```
Saves ~38KB immediately.

### 1c. Full Font Awesome loaded (~59KB CSS + ~78KB woff2 for solid alone)
**File:** `functions.php`, line 210 + `inc/fontawesome/webfonts/` directory
**Impact:** HIGH
The entire Font Awesome library is loaded (all.min.css) including solid, regular, and brands icon sets. The template only uses a handful of icons (social icons in the footer via `fa-` classes). Options:
- **Quick fix:** Replace with a Font Awesome kit that subsets only the icons you use.
- **Better fix:** Replace the few Font Awesome icons with inline SVGs (the theme already uses inline SVGs extensively for cart, phone, and search icons). The footer social icons are the only Font Awesome usage I found.
- **Bonus:** Remove the legacy font formats (`.eot`, `.ttf`, `.woff`, `.svg`) from `inc/fontawesome/webfonts/`. Modern browsers only need `.woff2`. This removes ~2.3MB of unused font files from the theme.

### 1d. Both Slick AND Swiper are loaded on every page
**Files:** `functions.php`, lines 211-212, 225-226
**Impact:** MEDIUM
Two competing slider/carousel libraries are loaded globally:
- **Slick** (slick.css + slick.min.js = ~44KB)
- **Swiper** (swiper-bundle.min.css + swiper-bundle.min.js = ~168KB)

Slick is used for the featured categories mobile carousel in `custom-scripts.js`. Swiper is used for the homepage banner slider and testimonial slider. Consider:
- Consolidating to one library (Swiper is more modern and can replace Slick).
- At minimum, conditionally load them only on pages that use them (Slick only on archive/shop pages, Swiper only on the homepage).

### 1e. Lightbox2 loaded globally
**File:** `functions.php`, lines 213, 227
**Impact:** LOW-MEDIUM
Lightbox2 CSS and JS (~12KB) are loaded on every page but likely only used on product pages. Wrap in an `is_product()` conditional like you've already done for PhotoSwipe.

### 1f. WooCommerce core CSS loaded via hardcoded plugin paths
**File:** `functions.php`, lines 203-205
**Impact:** MEDIUM (fragility + missed caching)
```php
wp_enqueue_style( 'mohawkversionii-wc-layout', get_site_url() . '/wp-content/plugins/woocommerce/assets/css/woocommerce-layout.css', ...);
```
These use `get_site_url()` with hardcoded plugin paths instead of `WC()->plugin_url()`. This breaks if WooCommerce is in a non-standard location and bypasses WordPress's built-in dequeue/enqueue optimisation. The `inc/woocommerce.php` already disables default WC styles — consider whether all three WC stylesheets are truly needed.

---

## 2. JavaScript — Blocking & Duplication (~310KB)

### 2a. Duplicate function definitions in `scripts.js`
**File:** `js/scripts.js`
**Impact:** LOW (code quality)
The functions `convertTableToMobile()` and `observeTableChanges()` are each defined **twice** (lines 312-426 and 376-440 are duplicates). This bloats the file and can cause unexpected behaviour. Remove the duplicate definitions.

### 2b. `custom-scripts.js` is 64KB and loaded everywhere
**File:** `js/custom-scripts.js`
**Impact:** MEDIUM
This is the largest custom JS file. Consider:
- Splitting into page-specific chunks (homepage scripts, product page scripts, archive scripts).
- Loading with `defer` or async strategy where possible.

### 2c. jQuery dependency not declared
**File:** `functions.php`, lines 223-238
**Impact:** LOW
Scripts that use jQuery (e.g., `scripts.js`, `custom-scripts.js`) don't declare `array('jquery')` as a dependency. While WordPress loads jQuery by default, declaring the dependency ensures correct load order and enables jQuery to be deferred.

### 2d. No script defer/async strategy
**File:** `functions.php`
**Impact:** MEDIUM
All scripts are loaded with the `in_footer: true` parameter (good), but none use WordPress's `wp_script_add_data()` for `defer` or `async`. Adding defer to non-critical scripts would improve First Contentful Paint:
```php
wp_script_add_data( 'mohawkversionii-slick', 'strategy', 'defer' );
wp_script_add_data( 'mohawkversionii-swiper', 'strategy', 'defer' );
wp_script_add_data( 'mohawkversionii-lightbox2', 'strategy', 'defer' );
```

---

## 3. Images & Media

### 3a. No lazy loading on product card images
**File:** `woocommerce/content-product-card.php`, lines 99-101
**Impact:** HIGH (on archive/shop pages with many products)
Product card images have no `loading="lazy"` attribute:
```php
// CURRENT
<img src="<?=$thumb_url;?>" alt="<?=basename( $thumb_url );?>">

// RECOMMENDED
<img src="<?=$thumb_url;?>" alt="<?=basename( $thumb_url );?>" loading="lazy">
```
On a category page with 20+ products, this means every product image downloads immediately. Adding `loading="lazy"` would dramatically reduce initial page weight.

### 3b. Banner slider images not lazy-loaded
**File:** `template-parts/content-banner-slider.php`, line 10
**Impact:** MEDIUM
All slider images load immediately. Only the first slide needs to be eager; subsequent slides should be lazy:
```php
<?php $index = 0; foreach( $monsta_slides as $slide ) : ?>
    <img src="<?php echo esc_attr( $slide['monsta_slide_image'] ); ?>"
         alt="<?php echo get_bloginfo( 'name' ); ?>"
         <?php echo ($index > 0) ? 'loading="lazy"' : ''; ?> />
<?php $index++; endforeach; ?>
```

### 3c. Product card autoplay videos on hover
**File:** `woocommerce/content-product-card.php`, lines 106-109
**Impact:** MEDIUM
Videos with `autoplay` load immediately even though they're only visible on hover. Consider loading the video source dynamically on hover via JavaScript, or use `preload="none"`:
```html
<video autoplay loop muted preload="none">
```

### 3d. SVGs inlined repeatedly in HTML
**Files:** `header.php`, `template-parts/header-mobile.php`
**Impact:** LOW-MEDIUM
The same cart SVG icon (~1.5KB) is inlined in both the desktop header and mobile header. The search SVG is also duplicated. Consider:
- Moving these to a single SVG sprite file.
- Or using `<use>` references to a hidden SVG definitions block.

---

## 4. PHP / Server-Side

### 4a. Repeated database queries in `shortcode_featured_categories()`
**File:** `inc/shortcodes.php`, lines 108-226
**Impact:** MEDIUM
The same taxonomy queries (`get_term_by('slug', 'trophy-specialists', ...)`, `get_terms()`, etc.) are executed **twice** within a single shortcode call — once at the start (lines 113-156) and again at the end (lines 196-223). The second block appears to be leftover/dead code. Remove lines 196-223.

### 4b. `product_colors_or_sizes()` called multiple times per product card
**File:** `woocommerce/content-product-card.php`, lines 13, 39-40
**Impact:** HIGH (on archive pages)
Each product card calls `product_colors_or_sizes()` **three times** (once for size on line 13, once for color on line 39, once for size again on line 40). Each call runs `$product->get_available_variations()` which is an expensive database query. The first call on line 13 is redundant since the result is recalculated on line 40. Refactor to call once and cache:
```php
$all_colors = product_colors_or_sizes( $product, 'color' );
$all_sizes = product_colors_or_sizes( $product, 'size' );
```

### 4c. `WC()->cart->cart_contents_count` called in header template
**File:** `header.php`, line 54
**Impact:** LOW
This is called directly in the template output. If cart fragments are enabled (they are — see `woocommerce_add_to_cart_fragments`), the initial server-rendered count is replaced by AJAX anyway. Consider outputting a placeholder and letting cart fragments handle it.

---

## 5. Asset Organisation & Caching

### 5a. Version string is static
**File:** `functions.php`, line 12
**Impact:** LOW
```php
define( 'MOHAWK_VERSION', '1.2.6' );
```
All enqueued assets use this static version. If you update CSS/JS without bumping the version, browsers serve stale cached files. Consider using file modification time for cache-busting:
```php
wp_enqueue_style( 'mohawkversionii-style', get_stylesheet_uri(), array(), filemtime( get_stylesheet_directory() . '/style.css' ) );
```

### 5.b `.sass-cache` directory shipped with theme
**File:** `.sass-cache/` directory
**Impact:** NEGLIGIBLE (but untidy)
The Sass cache directory adds ~20 compiled cache files to the theme. Add it to `.gitignore` and remove from deployment.

---

## Priority Action Plan

| Priority | Action | Est. Saving | Effort |
|----------|--------|-------------|--------|
| 🔴 1 | Minify `style.css` | ~80-100KB | Low |
| 🔴 2 | Add `loading="lazy"` to product card images | Major on shop pages | Low |
| 🔴 3 | Switch to `bootstrap.min.css` | ~38KB | Trivial |
| 🔴 4 | Fix triple `product_colors_or_sizes()` calls | Reduces DB queries 3x per product | Low |
| 🟡 5 | Remove duplicate functions in `scripts.js` | ~3KB + cleaner code | Low |
| 🟡 6 | Conditionally load Slick/Swiper/Lightbox2 | ~224KB on non-applicable pages | Medium |
| 🟡 7 | Replace Font Awesome with inline SVGs | ~137KB (CSS + woff2) | Medium |
| 🟡 8 | Remove dead code in `shortcode_featured_categories` | Saves redundant DB queries | Low |
| 🟡 9 | Add `defer` strategy to non-critical scripts | Faster FCP | Low |
| 🟡 10 | Add `preload="none"` to product hover videos | Reduces bandwidth | Trivial |
| 🟢 11 | Remove legacy font formats (.eot, .ttf, .woff, .svg) | ~2.3MB from theme size | Low |
| 🟢 12 | Use `filemtime()` for cache-busting | Better cache invalidation | Low |
| 🟢 13 | Declare jQuery dependency on scripts | Correct load ordering | Trivial |
| 🟢 14 | Remove `.sass-cache` from deployment | Cleaner theme | Trivial |
| 🟢 15 | Lazy-load banner slider images (2nd+ slides) | Faster homepage load | Low |
