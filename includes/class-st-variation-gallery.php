<?php
/**
 * Core plugin class.
 */
class ST_VariationGallery {

	protected $loader;
	protected $plugin_name;
	protected $version;

	public function __construct() {
		$this->version = defined( 'ST_VARIATION_GALLERY_VERSION' ) ? ST_VARIATION_GALLERY_VERSION : '1.0.0';
		$this->plugin_name = 'st-variation-gallery';
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	private function load_dependencies() {
		require_once ST_VARIATION_GALLERY_PATH . 'includes/class-st-variation-gallery-loader.php';
		require_once ST_VARIATION_GALLERY_PATH . 'includes/class-st-variation-gallery-i18n.php';
		require_once ST_VARIATION_GALLERY_PATH . 'admin/class-st-variation-gallery-admin.php';
		require_once ST_VARIATION_GALLERY_PATH . 'public/class-st-variation-gallery-public.php';
		$this->loader = new ST_VariationGallery_Loader();
	}

	private function set_locale() {
		$plugin_i18n = new ST_VariationGallery_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	private function define_admin_hooks() {
		$plugin_admin = new ST_VariationGallery_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'woocommerce_product_after_variable_attributes', $plugin_admin, 'variation_gallery_field', 10, 3 );
		$this->loader->add_action( 'wp_ajax_st_save_variation_gallery', $plugin_admin, 'ajax_save_variation_gallery' );
	}

	private function define_public_hooks() {
		$plugin_public = new ST_VariationGallery_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_ajax_st_get_variation_gallery', $plugin_public, 'ajax_get_variation_gallery' );
		$this->loader->add_action( 'wp_ajax_nopriv_st_get_variation_gallery', $plugin_public, 'ajax_get_variation_gallery' );
		$this->loader->add_action( 'init', $plugin_public, 'register_bricks_element' );
	}

	public function run() {
		$this->loader->run();
	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_loader() {
		return $this->loader;
	}

	public function get_version() {
		return $this->version;
	}
}
