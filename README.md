# Variation Gallery

Assign image galleries to WooCommerce variations. When a shopper selects a variation (e.g. "Black"), the product gallery switches to that variation's images automatically.

**Requirements:** WordPress, WooCommerce. Bricks Builder is optional (required only for the custom Bricks element).

---

## Installation

1. Upload the `st-variation-gallery` folder to `wp-content/plugins/`, or install via **Plugins → Add New → Upload Plugin**.
2. Activate via the **Plugins** screen.
3. WooCommerce must be active — the plugin will self-deactivate with a notice if it is not.

---

## Assigning a Gallery to a Variation (Admin)

1. Edit a variable product in WooCommerce.
2. Open the **Variations** tab under **Product data**.
3. Expand any variation.
4. Scroll to the **Variation Gallery** field.
5. Click **Add Images** and select images from the Media Library.
6. Reorder images by dragging them.
7. Remove an image with the × button.

Changes save automatically via AJAX — there is no need to click the variation's Save Changes button.

---

## Front-End Behaviour

When a shopper selects a variation, the gallery updates immediately with a brief dip-to-white transition:

| Scenario | What is displayed |
|---|---|
| Variation has a custom gallery | Featured image (large) + thumbnails below |
| Variation has only a WooCommerce variation image | Single image, no thumbnails |
| No variation selected / no variation image | Main product featured image + product gallery |

If a variation is preselected on page load (e.g. via `?variation_id=` or default attributes), the gallery updates automatically.

Clicking a thumbnail swaps it into the large featured view and highlights it.

---

## Bricks Builder Integration

The plugin registers a **Variation Gallery** element in the Bricks editor under the `variation-gallery` category.

1. Open a single-product template in Bricks.
2. Find **Variation Gallery** in the elements panel (search or browse the `variation-gallery` category).
3. Drop it onto the canvas.
4. Style it using the built-in controls:
   - **Thumbnail Size** — width/height of each thumbnail (px)
   - **Thumbnail Gap** — spacing between thumbnails (px)
   - **Active Border Color** — border colour of the selected/hovered thumbnail

The element works inside Bricks query loops as long as `$GLOBALS['product']` is set.

---

## Data & Uninstall

Each variation's gallery is stored as the `_st_variation_gallery` post_meta key on the variation post. Deactivating or deleting the plugin does not remove this data. To purge it, delete the meta key manually (e.g. via a database tool or a short script using `delete_post_meta`).

---

## Version History

| Version | Change |
|---|---|
| 1.0.4 | Rebranded to generic ST namespace |
| 1.0.3 | Dip-to-white transition effect on gallery change |
| 1.0.2 | Rewrote save mechanism to use AJAX (instant save on add/remove/reorder) |
| 1.0.1 | Fixed variation gallery not saving |
| 1.0.0 | Initial release |

---

**Author:** stealthtech1 — License: GPL-2.0+
