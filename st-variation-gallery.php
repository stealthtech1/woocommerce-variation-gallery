<?php
/**
 * Plugin Name:       Variation Gallery
 * Description:       Assign image galleries to WooCommerce variations.
 * Version:           1.0.4
 * Author:            stealthtech1
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       st-variation-gallery
 * Domain Path:       /languages
 * Requires Plugins:  woocommerce
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'ST_VARIATION_GALLERY_VERSION', '1.0.4' );
define( 'ST_VARIATION_GALLERY_PATH', plugin_dir_path( __FILE__ ) );
define( 'ST_VARIATION_GALLERY_URL', plugin_dir_url( __FILE__ ) );

function activate_st_variation_gallery() {
	require_once ST_VARIATION_GALLERY_PATH . 'includes/class-st-variation-gallery-activator.php';
	ST_VariationGallery_Activator::activate();
}

function deactivate_st_variation_gallery() {
	require_once ST_VARIATION_GALLERY_PATH . 'includes/class-st-variation-gallery-deactivator.php';
	ST_VariationGallery_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_st_variation_gallery' );
register_deactivation_hook( __FILE__, 'deactivate_st_variation_gallery' );

require ST_VARIATION_GALLERY_PATH . 'includes/class-st-variation-gallery.php';

function run_st_variation_gallery() {
	$plugin = new ST_VariationGallery();
	$plugin->run();
}
run_st_variation_gallery();
