<?php
/**
 * Bricks Builder Element: Variation Gallery.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ST_Bricks_Product_Gallery extends \Bricks\Element {

	public $category = 'variation-gallery';
	public $name     = 'st-product-gallery';
	public $icon     = 'ti-gallery';

	public function get_label() {
		return esc_html__( 'Variation Gallery', 'st-variation-gallery' );
	}

	public function set_controls() {
		$this->controls['thumb_size'] = array(
			'tab'     => 'content',
			'label'   => esc_html__( 'Thumbnail Size', 'st-variation-gallery' ),
			'type'    => 'number',
			'default' => '60px',
			'min'     => 40,
			'max'     => 120,
			'css'     => array(
				array(
					'property' => 'width',
					'selector' => '.st-thumb',
					'unit'     => 'px',
				),
				array(
					'property' => 'height',
					'selector' => '.st-thumb',
					'unit'     => 'px',
				),
			),
		);

		$this->controls['thumb_gap'] = array(
			'tab'     => 'content',
			'label'   => esc_html__( 'Thumbnail Gap', 'st-variation-gallery' ),
			'type'    => 'number',
			'default' => '8px',
			'min'     => 0,
			'max'     => 30,
			'css'     => array(
				array(
					'property' => 'gap',
					'selector' => '.st-gallery-thumbnails',
					'unit'     => 'px',
				),
			),
		);

		$this->controls['active_border_color'] = array(
			'tab'     => 'content',
			'label'   => esc_html__( 'Active Border Color', 'st-variation-gallery' ),
			'type'    => 'color',
			'default' => array( 'hex' => '#333333' ),
			'css'     => array(
				array(
					'property' => 'border-color',
					'selector' => '.st-thumb.active, .st-thumb:hover',
				),
			),
		);
	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'st-variation-gallery', ST_VARIATION_GALLERY_URL . 'public/css/st-variation-gallery-public.css', array(), ST_VARIATION_GALLERY_VERSION );
		wp_enqueue_script( 'st-variation-gallery', ST_VARIATION_GALLERY_URL . 'public/js/st-variation-gallery-public.js', array( 'jquery' ), ST_VARIATION_GALLERY_VERSION, true );
		wp_localize_script( 'st-variation-gallery', 'stVariationgallery', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'st_variation_gallery' ),
		) );
	}

	public function render() {
		$product_id   = null;
		$variation_id = null;

		// Get product ID from Bricks loop or global product.
		if ( isset( $GLOBALS['product'] ) && is_a( $GLOBALS['product'], 'WC_Product' ) ) {
			$product_id = $GLOBALS['product']->get_id();
		} elseif ( function_exists( 'wc_get_product' ) ) {
			global $post;
			if ( $post && 'product' === get_post_type( $post ) ) {
				$product_id = $post->ID;
			}
		}

		// Check for preselected variation via URL.
		if ( isset( $_GET['variation_id'] ) ) {
			$variation_id = absint( $_GET['variation_id'] );
		}

		$output = ST_VariationGallery_Public::render_gallery( $product_id, $variation_id );

		echo "<div {$this->render_attributes('_root')}>{$output}</div>";
	}
}
