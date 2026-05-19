<?php
/**
 * Internationalization functionality.
 */
class ST_VariationGallery_i18n {

	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'st-variation-gallery',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}
