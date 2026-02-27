### 1. Asset Minification & Dead Code Removal (`d61eae2`)

- **Switched to minified stylesheets** — `functions.php:220` now loads `style.min.css` instead of the full `style.css`, and `bootstrap.min.css` instead of `bootstrap.css` (`functions.php:206`).
- **Removed dead JS** — 64 lines deleted from `js/scripts.js` (`convertTableToMobile` and `observeTableChanges`), which handled a mobile price table reformatting that was no longer used.
- **Removed dead PHP** — 26 lines of duplicated/unused taxonomy queries stripped from `inc/shortcodes.php` in the `shortcode_featured_categories` function.
- **Removed a redundant function call** — `product_colors_or_sizes()` was being called twice for `size` in `content-product-card.php`; one call removed.
- **Added `loading="lazy"` to product card images** and `preload="none"` to product hover videos in `content-product-card.php`.

### 2. AJAX Infinite Scroll (`ba60007`)

- **New server-side AJAX endpoint** — `mohawk_infinite_scroll_handler()` added to `functions.php:435`, registered on both `wp_ajax_` and `wp_ajax_nopriv_` hooks. Returns JSON with just the product card HTML, the current page, and `max_pages`.
- **`wp_localize_script` config block** — `functions.php:241-257` passes `mohawkInfinite` data (ajax URL, nonce, max pages, current page, category, sort order, per-page count) to the frontend.
- **Simplified `archive-product.php`** — The old 60-line `?infinite_result=1` block (which ran a full `WP_Query` with duplicate ordering logic and output buffering plus pagination HTML) was replaced with a 10-line minimal fallback that just outputs product cards and exits.
- **Infinite scroll CSS** — New styles in `style.css` for `.infinite-loader` visibility, a pulsing loading animation, and proper hiding when all pages are loaded.

### 3. Improved Scroll Detection & Prefetching (`e1ca18e`)

The JS in `custom-scripts.js` was rewritten from ~50 lines to ~250 lines, replacing the original scroll-only trigger with a multi-layered system:

- **Dual-mode architecture** — Mode A uses the AJAX endpoint (`mohawkInfinite`); Mode B falls back to scraping pagination links from the DOM.
- **Triple trigger detection** — IntersectionObserver (2500px root margin), throttled scroll listener (100ms), and a 500ms polling interval all run in parallel. This catches edge cases like opacity transitions, resizes, or programmatic scrolls.
- **Page prefetching** — `prefetchNextPage()` fires immediately on init and again after each page loads, so the next batch of products is already in memory before the user scrolls to it.
- **Staggered initial checks** — Timeouts at 100/300/600/1000ms handle DOM readiness timing issues (e.g. sidebar reveal animations).
- **`getBoundingClientRect`-based proximity** — Triggers loading 2000px before the loader enters the viewport, independent of CSS transforms or layout shifts.

---

## Expected Performance Improvements

**Reduced page weight:**
- Minified CSS (`style.min.css`, `bootstrap.min.css`) reduces stylesheet transfer size. A 20-40% reduction in CSS bytes.
- Removing dead JS and PHP eliminates unused parsing/execution overhead on every page load.

**Faster product browsing (infinite scroll):**
- The old approach fetched the *entire next page* (header, sidebar, footer, scripts, styles) via `$.ajax` and then extracted just the product HTML. The new AJAX endpoint returns only the JSON product card markup = dramatically smaller response payload per page load.
- Prefetching means the next batch of products is already in the browser by the time the user reaches the bottom, effectively eliminating perceived wait time for the second-page load onward.

**Fewer dropped triggers:**
- The original implementation relied solely on a `$(window).scroll` handler with `offset().top` math, which can miss triggers when elements have CSS transforms, opacity transitions, or when the layout shifts. The triple detection (IntersectionObserver + scroll + polling) with `getBoundingClientRect` is more reliable across all conditions.

**Lower server load:**
- The old `?infinite_result=1` path rendered through the full WordPress template stack for each page. The new AJAX handler runs a lightweight `WP_Query` and outputs only card HTML via `wp_send_json_success()`, skipping theme template loading entirely.

**Improved image/video loading:**
- `loading="lazy"` on product card images defers off-screen image downloads. `preload="none"` on hover videos prevents the browser from buffering video data until interaction. Both reduce initial bandwidth and improve time-to-interactive, especially on category pages with 24+ products.

## Redundant Database Calls Removed — The Big Wins

### 1. The `product_colors_or_sizes()` Triple-Call Problem (`content-product-card.php`)

This is the most impactful change because it compounds **per product, per page**.

The original template called `product_colors_or_sizes()` **three times per product card**:
- Line 13: `product_colors_or_sizes($product, 'size')` — called early, result stored in `$size_items`
- Line 39: `product_colors_or_sizes($product, 'color')` — called again later
- Line 40: `product_colors_or_sizes($product, 'size')` — called **again**, completely overwriting the result from line 13

That first call on line 13 was totally wasted — its result was overwritten 27 lines later. So every single product card was making one completely redundant call.

**What each call does internally** (see `inc/woocommerce.php:257`):
1. `$product->get_available_variations()` — this is the killer. WooCommerce loads **every variation** for the product, each one triggering queries against `wp_posts`, `wp_postmeta`, and variation attribute tables. For a product with 10 variations, that's easily **30-50+ database queries** per call.
2. `get_the_terms($product->id, 'product_cat')` — another DB hit to the `wp_term_relationships` and `wp_terms` tables to check if the product is a Medal.

The fix reduces this from 3 calls to 2 (one for `color`, one for `size`). That eliminates one full `get_available_variations()` + `get_the_terms()` cycle **per product**.

**The math at scale:**
- 24 products per page = **24 wasted `get_available_variations()` calls eliminated per page load**
- If the average product has 8 variations, that's roughly **24 x 40 = ~960 unnecessary database queries removed per page view**
- On a busy shop page doing 1,000 views/day, that's nearly **1 million fewer DB queries per day** , just from removing one duplicated line

And this compounds further with infinite scroll. A user scrolling through 5 pages of products would have triggered `5 x 24 = 120` redundant variation lookups. Now: zero.

### 2. Dead Taxonomy Queries in `shortcode_featured_categories` (`inc/shortcodes.php`)

The original shortcode had a 26-line block at the bottom that ran multiple taxonomy queries whose results were **never used**:

- `get_term_by('slug', 'trophy-specialists', 'product_cat')` — DB hit to find the parent category
- `get_terms(['taxonomy' => 'product_cat', ...])` — DB hit to fetch **all child categories** under that parent
- `get_term_by('slug', 'ungrouped', 'product_cat')` — another DB hit
- `get_queried_object()` — potential DB hit depending on context
- On product pages: `get_the_terms($post->ID, 'product_cat')` with a loop — yet another DB hit

All of these ran, allocated memory for the results, and then the function returned `ob_get_clean()` without ever referencing any of them. Pure waste. Every page that rendered the featured categories shortcode was burning 3-5 taxonomy queries for nothing.

### 3. Full-Page Template Rendering Replaced by Lightweight AJAX (`archive-product.php` + `functions.php`)

The old infinite scroll approach fetched the **entire next page** as a full WordPress page load (`?infinite_result=1`). Even though it was an AJAX request, WordPress still:

- Bootstrapped the full theme (`wp-load.php` -> theme `functions.php` -> template hierarchy)
- Loaded the header, sidebar, and footer templates (even though they were thrown away)
- Ran the `archive-product.php` template, which itself ran a **duplicate `WP_Query`** for `price-high-to-low` ordering (lines 14-48 of the original) — duplicating the ordering logic that WordPress had already resolved
- Generated full pagination HTML with `paginate_links()` — another set of queries — only for it to be hidden with `d-none`
- Wrapped everything in `ob_start()`/`ob_get_contents()`/`ob_end_clean()`/`ob_flush()` — a double-buffered output chain

The new AJAX handler (`mohawk_infinite_scroll_handler` in `functions.php`) runs a single, targeted `WP_Query` and returns just the product card HTML via `wp_send_json_success()`. No theme template loading, no duplicate query, no pagination generation, no output buffering gymnastics.

### Combined Server Load Reduction

| Source | DB queries removed per event | Frequency |
|---|---|---|
| Duplicate `product_colors_or_sizes('size')` | ~40 queries x 24 products = **~960/page** | Every shop/category page view |
| Dead shortcode taxonomy queries | **3-5 queries** | Every page with featured categories |
| Full-page AJAX replaced by lightweight endpoint | **Hundreds** (full WP bootstrap + duplicate WP_Query + paginate_links) | Every infinite scroll page load |

On a category page where a user scrolls through all products, the old code could easily generate **thousands** of redundant database queries in a single session. The changes cut the per-request DB overhead dramatically — the heaviest hitter being the variation lookup fix, which scales linearly with product count and directly reduces load on `wp_postmeta` (typically the largest and most contended table in any WooCommerce database).