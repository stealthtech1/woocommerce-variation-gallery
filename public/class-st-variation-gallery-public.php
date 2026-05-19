<?php
/**
 * Public-facing functionality.
 */
class ST_VariationGallery_Public {

	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	public function enqueue_styles() {
		if ( is_product() ) {
			wp_enqueue_style( $this->plugin_name, ST_VARIATION_GALLERY_URL . 'public/css/st-variation-gallery-public.css', array(), $this->version );
		}
	}

	public function enqueue_scripts() {
		if ( is_product() ) {
			wp_enqueue_script( $this->plugin_name, ST_VARIATION_GALLERY_URL . 'public/js/st-variation-gallery-public.js', array( 'jquery' ), $this->version, true );
			wp_localize_script( $this->plugin_name, 'stVariationgallery', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'st_variation_gallery' ),
			) );
		}
	}

	public function ajax_get_variation_gallery() {
		check_ajax_referer( 'st_variation_gallery', 'nonce' );
		$variation_id = isset( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
		$product_id   = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;

		if ( $variation_id ) {
			$gallery = $this->get_variation_gallery_data( $variation_id );
			if ( ! empty( $gallery ) ) {
				wp_send_json_success( $gallery );
			}
		}

		// Return main product gallery as fallback.
		wp_send_json_success( $this->get_product_gallery_data( $product_id ) );
	}

	public function get_variation_gallery_data( $variation_id ) {
		$gallery_ids = get_post_meta( $variation_id, '_st_variation_gallery', true );
		if ( empty( $gallery_ids ) || ! is_array( $gallery_ids ) ) {
			return array();
		}
		return $this->format_gallery_data( $gallery_ids );
	}

	public function get_product_gallery_data( $product_id ) {
		$product = wc_get_product( $product_id );
		if ( ! $product ) {
			return array();
		}
		$ids = array();
		if ( $product->get_image_id() ) {
			$ids[] = $product->get_image_id();
		}
		$ids = array_merge( $ids, $product->get_gallery_image_ids() );
		return $this->format_gallery_data( $ids );
	}

	private function format_gallery_data( $ids ) {
		$images = array();
		foreach ( $ids as $id ) {
			$full  = wp_get_attachment_image_src( $id, 'large' );
			$thumb = wp_get_attachment_image_src( $id, 'thumbnail' );
			if ( $full && $thumb ) {
				$images[] = array(
					'id'    => $id,
					'full'  => $full[0],
					'thumb' => $thumb[0],
					'alt'   => get_post_meta( $id, '_wp_attachment_image_alt', true ),
				);
			}
		}
		return $images;
	}

	public function register_bricks_element() {
		if ( ! defined( 'BRICKS_VERSION' ) ) {
			return;
		}
		add_action( 'init', function() {
			if ( class_exists( '\Bricks\Elements' ) ) {
				\Bricks\Elements::register_element( ST_VARIATION_GALLERY_PATH . 'includes/class-st-bricks-product-gallery.php' );
			}
		}, 11 );
	}

	public static function render_gallery( $product_id = null, $variation_id = null ) {
		if ( ! $product_id ) {
			global $product;
			$product_id = $product ? $product->get_id() : 0;
		}
		if ( ! $product_id ) {
			return '';
		}

		$instance = new self( 'st-variation-gallery', ST_VARIATION_GALLERY_VERSION );
		$gallery  = array();

		if ( $variation_id ) {
			$gallery = $instance->get_variation_gallery_data( $variation_id );
		}
		if ( empty( $gallery ) ) {
			$gallery = $instance->get_product_gallery_data( $product_id );
		}
		if ( empty( $gallery ) ) {
			return '<div class="st-product-gallery" data-product="' . esc_attr( $product_id ) . '"><p>' . esc_html__( 'No images available.', 'st-variation-gallery' ) . '</p></div>';
		}

		ob_start();
		?>
		<div class="st-product-gallery" data-product="<?php echo esc_attr( $product_id ); ?>">
			<div class="st-gallery-featured">
				<img src="<?php echo esc_url( $gallery[0]['full'] ); ?>" alt="<?php echo esc_attr( $gallery[0]['alt'] ); ?>" class="st-featured-image" />
			</div>
			<div class="st-gallery-thumbnails">
			<?php if ( count( $gallery ) > 1 ) : ?>
				<?php foreach ( $gallery as $i => $img ) : ?>
					<div class="st-thumb<?php echo 0 === $i ? ' active' : ''; ?>" data-full="<?php echo esc_url( $img['full'] ); ?>">
						<img src="<?php echo esc_url( $img['thumb'] ); ?>" alt="<?php echo esc_attr( $img['alt'] ); ?>" />
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
