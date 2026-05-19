<?php
/**
 * Fired during plugin activation.
 */
class ST_VariationGallery_Activator {

	public static function activate() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die( esc_html__( 'This plugin requires WooCommerce to be installed and active.', 'st-variation-gallery' ) );
		}
	}
}
